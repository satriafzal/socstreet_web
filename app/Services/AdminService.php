<?php

namespace App\Services;

use App\Models\BlockedSchedule;
use App\Models\Booking;
use App\Models\FieldSetting;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    /**
     * Get summary metrics for admin dashboard (PRD 7.B.1).
     */
    public function getDashboardMetrics(): array
    {
        $now = Carbon::now();

        // Total revenue this month from paid payments or confirmed bookings
        $monthlyRevenue = Payment::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->where('status', 'paid')
            ->sum('amount');

        // If no paid payment records yet, calculate from confirmed bookings of this month
        if ($monthlyRevenue == 0) {
            $monthlyRevenue = (float) Booking::whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->where('status', 'confirmed')
                ->sum('total_price');
        }

        // Count of bookings needing quick confirmation
        $pendingCount = Booking::where('status', 'pending_payment')->count();

        // Count of today's confirmed match sessions
        $todaySessions = Booking::whereDate('book_date', Carbon::today())
            ->where('status', 'confirmed')
            ->count();

        return [
            'revenue_raw' => (float) $monthlyRevenue,
            'revenue' => 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'),
            'pending_count' => $pendingCount,
            'today_sessions' => $todaySessions,
        ];
    }

    /**
     * Get booking statistics for Admin Manage Bookings page (PRD 7.B.2).
     */
    public function getBookingStats(): array
    {
        $pending = Booking::where('status', 'pending_payment')->count();
        $confirmed = Booking::where('status', 'confirmed')->count();
        $todayTotal = Booking::whereDate('book_date', Carbon::today())->count();

        $totalPaid = (float) Payment::where('status', 'paid')->sum('amount');
        if ($totalPaid == 0) {
            $totalPaid = (float) Booking::where('status', 'confirmed')->sum('total_price');
        }

        return [
            'pending' => $pending,
            'confirmed' => $confirmed,
            'today_total' => $todayTotal,
            'total_received' => 'Rp ' . number_format($totalPaid, 0, ',', '.'),
            'total_received_raw' => $totalPaid,
        ];
    }

    /**
     * Get all bookings formatted for table/API with optional search, status, and payment type filters (PRD 7.B.2).
     */
    public function getAllBookings(array $filters = []): array
    {
        $query = Booking::with(['user', 'latestPayment', 'payments'])->latest();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('booking_id_string', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('username', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $status = strtolower($filters['status']);
            if ($status === 'pending') {
                $query->where('status', 'pending_payment');
            } elseif ($status === 'cancelled' || $status === 'rejected') {
                $query->where('status', 'cancelled');
            } else {
                $query->where('status', $status);
            }
        }

        if (!empty($filters['payment_type']) && $filters['payment_type'] !== 'all') {
            $type = strtoupper($filters['payment_type']); // DP or FULL
            $query->whereHas('payments', function ($pq) use ($type) {
                $pq->where('payment_type', $type);
            });
        }

        $bookings = $query->get();

        return $bookings->map(function (Booking $b) {
            $payment = $b->latestPayment;
            $isDp = $payment ? ($payment->payment_type === 'DP') : false;

            $totalPrice = (float) $b->total_price;
            $paidAmount = $payment ? (float) $payment->amount : ($isDp ? ($totalPrice / 2) : $totalPrice);
            $remainingRaw = $isDp ? max(0, $totalPrice - $paidAmount) : 0;

            $statusNormalized = match ($b->status) {
                'confirmed' => 'confirmed',
                'cancelled' => 'cancelled',
                default => 'pending',
            };

            return [
                'id' => $b->id,
                'booking_id' => $b->booking_id_string,
                'username' => $b->user->username ?? 'Member',
                'date' => Carbon::parse($b->book_date)->locale('id')->isoFormat('D MMM YYYY'),
                'date_raw' => $b->book_date instanceof Carbon ? $b->book_date->toDateString() : (string) $b->book_date,
                'time_slot' => $b->start_time . ' - ' . $b->end_time,
                'start_time' => $b->start_time,
                'end_time' => $b->end_time,
                'payment_type_raw' => $isDp ? 'dp' : 'full',
                'payment_type' => $isDp ? 'DP 50%' : 'Lunas 100%',
                'payment_method' => $payment ? $payment->payment_method : 'QRIS',
                'total_price' => 'Rp ' . number_format($totalPrice, 0, ',', '.'),
                'total_price_raw' => $totalPrice,
                'remaining_payment' => $remainingRaw > 0 ? 'Rp ' . number_format($remainingRaw, 0, ',', '.') : 'Rp 0',
                'remaining_payment_raw' => $remainingRaw,
                'status' => $statusNormalized,
                'status_raw' => $b->status,
            ];
        })->toArray();
    }

    /**
     * Get latest incoming bookings for dashboard recent action table (PRD 7.B.1).
     */
    public function getRecentBookings(int $limit = 5): array
    {
        $bookings = Booking::with(['user', 'latestPayment'])
            ->latest()
            ->take($limit)
            ->get();

        return $bookings->map(function (Booking $b) {
            $payment = $b->latestPayment;
            $paymentTypeLabel = $payment
                ? ($payment->payment_type === 'DP' ? 'DP 50%' : 'Lunas 100%')
                : '-';

            $statusFormatted = match ($b->status) {
                'confirmed' => 'confirmed',
                'cancelled' => 'cancelled',
                default => 'pending',
            };

            return [
                'id' => $b->id,
                'booking_id' => $b->booking_id_string,
                'username' => $b->user->username ?? 'Member',
                'schedule' => Carbon::parse($b->book_date)->locale('id')->isoFormat('D MMM') . ', ' . $b->start_time . ' - ' . $b->end_time,
                'payment_type' => $paymentTypeLabel,
                'payment_method' => $payment ? $payment->payment_method : '-',
                'total' => 'Rp ' . number_format($b->total_price, 0, ',', '.'),
                'total_price_raw' => (float) $b->total_price,
                'status' => $statusFormatted,
                'status_raw' => $b->status,
            ];
        })->toArray();
    }

    /**
     * Confirm a booking by id or booking_id_string.
     */
    public function confirmBooking(int|string $identifier): Booking
    {
        $booking = is_numeric($identifier)
            ? Booking::findOrFail($identifier)
            : Booking::where('booking_id_string', $identifier)->firstOrFail();

        $booking->update(['status' => 'confirmed']);

        // Mark associated payments as paid
        Payment::where('booking_id', $booking->id)->update(['status' => 'paid']);

        return $booking->load(['user', 'payments']);
    }

    /**
     * Reject a booking by id or booking_id_string (frees up the time slot).
     */
    public function rejectBooking(int|string $identifier): Booking
    {
        $booking = is_numeric($identifier)
            ? Booking::findOrFail($identifier)
            : Booking::where('booking_id_string', $identifier)->firstOrFail();

        $booking->update(['status' => 'cancelled']);

        // Mark associated payments as failed
        Payment::where('booking_id', $booking->id)
            ->where('status', '!=', 'paid')
            ->update(['status' => 'failed']);

        return $booking->load(['user', 'payments']);
    }

    /**
     * Get Schedule & Pricing data including current price, blocked slots, and availability (PRD 7.B.3).
     */
    public function getScheduleData(): array
    {
        $basePrice = (int) FieldSetting::get('base_price', 300000);
        $primePrice = (int) FieldSetting::get('prime_price', 350000);

        $pricing = [
            'price' => $basePrice,
            'price_formatted' => number_format($basePrice, 0, ',', '.'),
            'prime_price' => $primePrice,
            'prime_price_formatted' => number_format($primePrice, 0, ',', '.'),
            'operating_hours' => '08:00 - 22:00',
            'duration' => '120 Menit',
            'total_slots' => 7,
        ];

        $blockedSchedules = BlockedSchedule::orderBy('date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function (BlockedSchedule $block) {
                return [
                    'id' => $block->id,
                    'date' => Carbon::parse($block->date)->locale('id')->isoFormat('D MMM YYYY'),
                    'date_raw' => $block->date instanceof Carbon ? $block->date->toDateString() : (string) $block->date,
                    'time_slot' => $block->start_time . ' - ' . $block->end_time,
                    'start_time' => $block->start_time,
                    'end_time' => $block->end_time,
                    'reason' => $block->reason,
                ];
            })->toArray();

        // Calculate available slots today
        $todaySlots = app(BookingService::class)->getSlotsForDate(Carbon::today()->toDateString());
        $availableToday = collect($todaySlots)->where('status', 'available')->count();

        return [
            'pricing' => $pricing,
            'blocked_schedules' => $blockedSchedules,
            'available_today' => $availableToday,
            'total_today_slots' => 7,
        ];
    }

    /**
     * Update field rental base price (PRD 7.B.3).
     */
    public function updatePricing(int $basePrice): array
    {
        FieldSetting::set('base_price', $basePrice, 'Tarif dasar sewa per sesi 2 jam');

        // Prime price is set proportionally (+50.000 or ratio)
        $primePrice = $basePrice + 50000;
        FieldSetting::set('prime_price', $primePrice, 'Tarif prime-time (18:00 - 22:00)');

        return [
            'base_price' => $basePrice,
            'base_price_formatted' => number_format($basePrice, 0, ',', '.'),
            'prime_price' => $primePrice,
            'prime_price_formatted' => number_format($primePrice, 0, ',', '.'),
        ];
    }

    /**
     * Create a blocked schedule maintenance slot (PRD 7.B.3).
     */
    public function createBlockedSchedule(array $data): BlockedSchedule
    {
        $date = Carbon::parse($data['date'])->toDateString();

        if (!empty($data['time_slot'])) {
            // Strip any label like "(Prime)" from the slot string
            $cleanSlot = preg_replace('/\s*\(.*?\)/', '', $data['time_slot']);
            [$startTime, $endTime] = array_map('trim', explode('-', $cleanSlot));
        } else {
            $startTime = trim($data['start_time']);
            $endTime = trim($data['end_time']);
        }

        // Prevent duplicate blocks
        $existingBlock = BlockedSchedule::whereDate('date', $date)
            ->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->first();

        if ($existingBlock) {
            throw new \InvalidArgumentException("Sesi {$startTime} - {$endTime} pada tanggal {$date} sudah diblokir sebelumnya.");
        }

        // Prevent blocking a slot with active customer bookings
        $activeBooking = Booking::whereDate('book_date', $date)
            ->whereIn('status', ['pending_payment', 'confirmed'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($sub) use ($startTime, $endTime) {
                    $sub->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->first();

        if ($activeBooking) {
            throw new \InvalidArgumentException("Slot {$startTime} - {$endTime} pada tanggal {$date} sudah memiliki reservasi aktif ({$activeBooking->booking_id_string}). Batalkan atau konfirmasi reservasi terlebih dahulu.");
        }

        return BlockedSchedule::create([
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'reason' => $data['reason'] ?? 'Pemeliharaan Lapangan',
        ]);
    }

    /**
     * Delete/unblock a blocked schedule slot (PRD 7.B.3).
     */
    public function deleteBlockedSchedule(int $id): bool
    {
        $block = BlockedSchedule::findOrFail($id);
        return (bool) $block->delete();
    }

    /**
     * Get user management statistics (PRD 7.B.4).
     */
    public function getUserStats(): array
    {
        $activeMembers = User::where('role', 'user')->count();
        $admins = User::where('role', 'admin')->count();
        $newThisMonth = User::where('role', 'user')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        $totalSessions = Booking::where('status', 'confirmed')->count();
        $actionNeeded = User::where('role', 'user')->where('must_change_password', true)->count();

        return [
            'active_members' => $activeMembers,
            'admins' => $admins,
            'new_this_month' => $newThisMonth,
            'total_sessions' => $totalSessions . ' Sesi',
            'action_needed' => $actionNeeded . ' Akun',
        ];
    }

    /**
     * Get all customer users with optional search and password status filtering (PRD 7.B.4).
     * Excludes administrator accounts from the user management table.
     */
    public function getAllUsers(array $filters = []): array
    {
        // Exclude admin accounts - strictly manage customer accounts
        $query = User::where('role', 'user')->withCount('bookings')->latest();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where('username', 'like', "%{$search}%");
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            if ($filters['status'] === 'need_change') {
                $query->where('must_change_password', true);
            } elseif ($filters['status'] === 'active') {
                $query->where('must_change_password', false);
            }
        }

        return $query->get()->map(function (User $u) {
            $formattedId = 'USR-' . str_pad($u->id, 3, '0', STR_PAD_LEFT);

            return [
                'id' => $u->id,
                'user_id_string' => $formattedId,
                'username' => $u->username,
                'email' => $u->email,
                'role' => 'User',
                'role_raw' => 'user',
                'total_bookings' => $u->bookings_count ?? 0,
                'must_change_password' => (bool) $u->must_change_password,
                'created_at' => Carbon::parse($u->created_at)->locale('id')->isoFormat('D MMM YYYY'),
            ];
        })->toArray();
    }

    /**
     * Create a new customer user with default password and must_change_password flag (PRD 7.B.4).
     */
    public function createUser(array $data): User
    {
        $username = trim($data['username']);

        // Check username uniqueness
        if (User::where('username', $username)->exists()) {
            throw new \InvalidArgumentException("Username '{$username}' sudah terdaftar. Silakan pilih username lain.");
        }

        // Email generation (PRD specifies auth by username/password, email is auto-generated if omitted)
        $cleanUsername = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $username));
        $email = $cleanUsername . '@socstreet.local';
        if (User::where('email', $email)->exists()) {
            $email = $cleanUsername . '_' . time() . '@socstreet.local';
        }

        return User::create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make('socstreet123'),
            'role' => 'user',
            'role_id' => 2,
            'must_change_password' => true,
        ]);
    }

    /**
     * Reset user password to default 'socstreet123' and set must_change_password flag (PRD 7.B.4).
     */
    public function resetUserPassword(int|string $userId): User
    {
        $user = User::findOrFail($userId);

        $user->update([
            'password' => Hash::make('socstreet123'),
            'must_change_password' => true,
        ]);

        return $user;
    }

    /**
     * Delete user account (PRD 7.B.4).
     */
    public function deleteUser(int|string $userId, int $currentAdminId): bool
    {
        $user = User::findOrFail($userId);

        if ($user->isAdmin()) {
            throw new \InvalidArgumentException("Akun administrator tidak dapat dihapus melalui menu kelola pengguna.");
        }

        return (bool) $user->delete();
    }
}
