<?php

namespace App\Services;

use App\Models\BlockedSchedule;
use App\Models\Booking;
use App\Models\FieldSetting;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Define the 7 canonical 2-hour sessions for Socstreet Main Arena (08:00 - 22:00).
     */
    public const CANONICAL_SLOTS = [
        ['start' => '08:00', 'end' => '10:00', 'time' => '08:00 - 10:00', 'category' => 'Pagi', 'is_prime' => false],
        ['start' => '10:00', 'end' => '12:00', 'time' => '10:00 - 12:00', 'category' => 'Siang', 'is_prime' => false],
        ['start' => '12:00', 'end' => '14:00', 'time' => '12:00 - 14:00', 'category' => 'Siang', 'is_prime' => false],
        ['start' => '14:00', 'end' => '16:00', 'time' => '14:00 - 16:00', 'category' => 'Sore', 'is_prime' => false],
        ['start' => '16:00', 'end' => '18:00', 'time' => '16:00 - 18:00', 'category' => 'Sore', 'is_prime' => false],
        ['start' => '18:00', 'end' => '20:00', 'time' => '18:00 - 20:00', 'category' => 'Malam (Prime)', 'is_prime' => true],
        ['start' => '20:00', 'end' => '22:00', 'time' => '20:00 - 22:00', 'category' => 'Malam (Prime)', 'is_prime' => true],
    ];

    /**
     * Get quick date picker array (default 7 upcoming days).
     */
    public function getAvailableDates(string $selectedDate, int $days = 7): array
    {
        $dates = [];
        $today = Carbon::today();

        for ($i = 0; $i < $days; $i++) {
            $date = (clone $today)->addDays($i);
            $formatted = $date->format('Y-m-d');
            $dates[] = [
                'full_date' => $formatted,
                'day_name' => $i === 0 ? 'Hari Ini' : ($i === 1 ? 'Besok' : $date->locale('id')->isoFormat('dddd')),
                'date_short' => $date->locale('id')->isoFormat('D MMM'),
                'is_selected' => $formatted === $selectedDate,
            ];
        }

        return $dates;
    }

    /**
     * Get slot availability for a specific date according to automated rules (PRD Section 8.1).
     */
    public function getSlotsForDate(string $dateString): array
    {
        $date = Carbon::parse($dateString)->format('Y-m-d');
        $todayStr = Carbon::today()->format('Y-m-d');
        $isPastDate = ($date < $todayStr);
        $isToday = ($date === $todayStr);
        $currentTime = Carbon::now()->format('H:i');
        $basePrice = (int) FieldSetting::get('base_price', 300000);
        $primePrice = (int) FieldSetting::get('prime_price', 350000);

        // Fetch active bookings on that date (pending_payment or confirmed lock the slot)
        $activeBookings = Booking::whereDate('book_date', $date)
            ->whereIn('status', ['pending_payment', 'confirmed'])
            ->get(['start_time', 'end_time', 'booking_id_string', 'status']);

        // Fetch blocked slots (maintenance / turnamen)
        $blockedSchedules = BlockedSchedule::whereDate('date', $date)
            ->get(['start_time', 'end_time', 'reason']);

        $result = [];

        foreach (self::CANONICAL_SLOTS as $slot) {
            $price = $slot['is_prime'] ? $primePrice : $basePrice;

            // Check if slot has already passed for today (or if date is in the past)
            $isPast = $isPastDate || ($isToday && ($currentTime >= $slot['start']));

            // Check if slot overlaps with active booking
            $isBooked = $activeBookings->contains(function ($booking) use ($slot) {
                return $this->timesOverlap($booking->start_time, $booking->end_time, $slot['start'], $slot['end']);
            });

            // Check if slot overlaps with blocked maintenance schedule
            $blockedEntry = $blockedSchedules->first(function ($blocked) use ($slot) {
                return $this->timesOverlap($blocked->start_time, $blocked->end_time, $slot['start'], $slot['end']);
            });

            $status = 'available';
            if ($blockedEntry) {
                $status = 'blocked';
            } elseif ($isBooked) {
                $status = 'booked';
            } elseif ($isPast) {
                $status = 'past';
            }

            $result[] = [
                'time' => $slot['time'],
                'start_time' => $slot['start'],
                'end_time' => $slot['end'],
                'duration' => 2,
                'price' => $price,
                'category' => $slot['category'],
                'status' => $status,
                'is_available' => $status === 'available',
                'is_booked' => $status === 'booked',
                'is_blocked' => $status === 'blocked',
                'is_past' => $isPast,
                'block_reason' => $blockedEntry ? $blockedEntry->reason : null,
            ];
        }

        return $result;
    }

    /**
     * Check if two time intervals overlap.
     */
    public function timesOverlap(string $startA, string $endA, string $startB, string $endB): bool
    {
        return ($startA < $endB) && ($endA > $startB);
    }

    /**
     * Create a booking with atomic concurrency protection & payment record (PRD Section 4 & 5.A).
     *
     * @throws ValidationException
     */
    public function createBooking(User $user, array $data): Booking
    {
        $bookDate = Carbon::parse($data['book_date'])->format('Y-m-d');
        $timeSlot = trim($data['time_slot']); // e.g. "18:00 - 20:00" or "16:00 - 18:00, 18:00 - 20:00"
        $paymentType = strtoupper($data['payment_type']); // "DP" or "FULL"
        $paymentMethod = $data['payment_method']; // QRIS, BCA, Mandiri, BRI, DANA, GoPay

        $basePrice = (int) FieldSetting::get('base_price', 300000);
        $primePrice = (int) FieldSetting::get('prime_price', 350000);

        $rawSlots = array_map('trim', explode(',', $timeSlot));
        $parsedSegments = [];
        $durationHours = 0;
        $totalPrice = 0;

        foreach ($rawSlots as $rawSlot) {
            $times = explode('-', $rawSlot);
            if (count($times) !== 2) {
                throw ValidationException::withMessages([
                    'time_slot' => ['Format slot waktu tidak valid. Gunakan format JJ:MM - JJ:MM (misal 18:00 - 20:00).'],
                ]);
            }

            $s = trim($times[0]);
            $e = trim($times[1]);

            $startCarbon = Carbon::createFromFormat('H:i', $s);
            $endCarbon = Carbon::createFromFormat('H:i', $e);
            $segHours = $startCarbon->diffInHours($endCarbon);

            if ($segHours < 2 || $segHours % 2 !== 0) {
                throw ValidationException::withMessages([
                    'time_slot' => ['Durasi sewa wajib minimal 2 jam dan dalam kelipatan 2 jam.'],
                ]);
            }

            $durationHours += $segHours;
            $segPrice = ($s >= '18:00') ? $primePrice : $basePrice;
            $totalPrice += ($segHours / 2) * $segPrice;
            $parsedSegments[] = ['start' => $s, 'end' => $e];
        }

        usort($parsedSegments, fn ($a, $b) => strcmp($a['start'], $b['start']));
        $startTime = $parsedSegments[0]['start'];
        $endTime = $parsedSegments[count($parsedSegments) - 1]['end'];

        $todayStr = Carbon::today()->format('Y-m-d');
        if ($bookDate < $todayStr || ($bookDate === $todayStr && Carbon::now()->format('H:i') >= $startTime)) {
            throw ValidationException::withMessages([
                'time_slot' => ['Sesi waktu pertandingan ini sudah lewat dan tidak dapat dipesan.'],
            ]);
        }

        return DB::transaction(function () use (
            $user,
            $bookDate,
            $startTime,
            $endTime,
            $durationHours,
            $totalPrice,
            $paymentType,
            $paymentMethod
        ) {
            // Concurrency / Double Booking check
            $conflictBooking = Booking::whereDate('book_date', $bookDate)
                ->whereIn('status', ['pending_payment', 'confirmed'])
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                })
                ->lockForUpdate()
                ->first();

            if ($conflictBooking) {
                throw ValidationException::withMessages([
                    'time_slot' => ['Mohon maaf, sesi waktu ini baru saja dipesan oleh pengguna lain. Silakan pilih sesi lainnya.'],
                ]);
            }

            // Check if slot is blocked by admin maintenance
            $conflictBlocked = BlockedSchedule::whereDate('date', $bookDate)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                })
                ->first();

            if ($conflictBlocked) {
                throw ValidationException::withMessages([
                    'time_slot' => ['Sesi waktu ini sedang dinonaktifkan oleh pengelola lapangan: ' . $conflictBlocked->reason],
                ]);
            }

            // Generate clean sequential / timestamped booking invoice ID
            $bookingIdString = $this->generateBookingId();

            $booking = Booking::create([
                'booking_id_string' => $bookingIdString,
                'user_id' => $user->id,
                'book_date' => $bookDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration' => $durationHours,
                'status' => 'pending_payment',
                'total_price' => $totalPrice,
            ]);

            // Calculate payment amount based on DP (50%) or FULL (100%)
            $amountToPay = ($paymentType === 'DP') ? ($totalPrice * 0.5) : $totalPrice;

            Payment::create([
                'booking_id' => $booking->id,
                'payment_type' => $paymentType,
                'payment_method' => $paymentMethod,
                'amount' => $amountToPay,
                'status' => 'pending',
            ]);

            return $booking->load(['payments', 'user']);
        });
    }

    /**
     * Generate unique booking code e.g. INV-20261122-001
     */
    protected function generateBookingId(): string
    {
        $datePrefix = Carbon::today()->format('Ymd');
        $countToday = Booking::whereDate('created_at', Carbon::today())->count() + 1;
        $sequence = str_pad((string) $countToday, 3, '0', STR_PAD_LEFT);
        $candidate = "INV-{$datePrefix}-{$sequence}";

        // Ensure absolute uniqueness
        while (Booking::where('booking_id_string', $candidate)->exists()) {
            $countToday++;
            $sequence = str_pad((string) $countToday, 3, '0', STR_PAD_LEFT);
            $candidate = "INV-{$datePrefix}-{$sequence}";
        }

        return $candidate;
    }

    /**
     * Get formatted customer bookings query with status filter.
     */
    public function getUserBookings(User $user, ?string $statusFilter = 'all')
    {
        $query = Booking::with('latestPayment')
            ->where('user_id', $user->id)
            ->latest();

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        return $query->get()->map(function (Booking $booking) {
            $latestPayment = $booking->latestPayment;
            $paymentTypeLabel = $latestPayment 
                ? ($latestPayment->payment_type === 'DP' ? 'DP (50%)' : 'Lunas (100%)') 
                : '-';

            $statusLabel = match ($booking->status) {
                'confirmed' => 'Dikonfirmasi',
                'cancelled' => 'Dibatalkan',
                default => 'Menunggu Pembayaran',
            };

            return [
                'id' => $booking->id,
                'booking_id_string' => $booking->booking_id_string,
                'book_date' => Carbon::parse($booking->book_date)->locale('id')->isoFormat('D MMM Y'),
                'start_time' => $booking->start_time,
                'end_time' => $booking->end_time,
                'duration' => $booking->duration ?? 2,
                'payment_type' => $paymentTypeLabel,
                'payment_method' => $latestPayment ? $latestPayment->payment_method : '-',
                'total_price' => (float) $booking->total_price,
                'amount_paid' => $latestPayment ? (float) $latestPayment->amount : 0,
                'status' => $booking->status,
                'status_label' => $statusLabel,
            ];
        });
    }
}
