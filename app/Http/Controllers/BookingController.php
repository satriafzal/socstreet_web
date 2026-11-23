<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\FieldSetting;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    /**
     * Standard list of payment methods supported by Socstreet (PRD 4.4).
     */
    protected function getPaymentMethods(): array
    {
        return [
            [
                'id' => 'QRIS',
                'name' => 'QRIS',
                'category' => 'Instant QR',
                'desc' => 'BCA, Mandiri, GoPay, OVO, ShopeePay & semua bank',
                'badge' => 'Otomatis',
                'logo' => 'images/payments/qris.svg',
            ],
            [
                'id' => 'BCA',
                'name' => 'BCA Virtual Account',
                'category' => 'Virtual Account',
                'desc' => 'Verifikasi instan via m-BCA / KlikBCA',
                'badge' => 'Otomatis',
                'logo' => 'images/payments/bca.svg',
            ],
            [
                'id' => 'Mandiri',
                'name' => 'Mandiri Livin',
                'category' => 'Virtual Account',
                'desc' => 'Bayar via Livin by Mandiri & ATM',
                'badge' => 'Otomatis',
                'logo' => 'images/payments/mandiri.svg',
            ],
            [
                'id' => 'BRI',
                'name' => 'BRI BRIVA',
                'category' => 'Virtual Account',
                'desc' => 'Bayar via BRImo atau ATM BRI',
                'badge' => 'Otomatis',
                'logo' => 'images/payments/bri.svg',
            ],
            [
                'id' => 'DANA',
                'name' => 'DANA Wallet',
                'category' => 'E-Wallet',
                'desc' => 'Pembayaran langsung akun dompet DANA',
                'badge' => 'Instan',
                'logo' => 'images/payments/dana.svg',
            ],
            [
                'id' => 'GoPay',
                'name' => 'GoPay',
                'category' => 'E-Wallet',
                'desc' => 'Konfirmasi pembayaran via aplikasi Gojek',
                'badge' => 'Instan',
                'logo' => 'images/payments/gopay.svg',
            ],
        ];
    }

    /**
     * Display the Schedule Grid for the single field: Socstreet Main Arena.
     */
    public function schedule(Request $request): View
    {
        $selectedDate = $request->query('date', Carbon::today()->format('Y-m-d'));
        $availableDates = $this->bookingService->getAvailableDates($selectedDate);
        $allSlots = $this->bookingService->getSlotsForDate($selectedDate);

        return view('pages.booking.schedule', compact('selectedDate', 'availableDates', 'allSlots'));
    }

    /**
     * Display the Checkout & Payment page.
     */
    public function checkout(Request $request): View
    {
        $bookDate = $request->query('date', Carbon::today()->format('Y-m-d'));
        $selectedSlot = $request->query('slot', '18:00 - 20:00');
        $duration = (int) $request->query('duration', 2);
        if ($duration < 2 || $duration % 2 !== 0) {
            $duration = 2;
        }

        $basePrice = (int) $request->query('price', 0);
        if ($basePrice <= 0) {
            $defaultBase = (int) FieldSetting::get('base_price', 300000);
            $defaultPrime = (int) FieldSetting::get('prime_price', 350000);
            $startTime = trim(explode('-', $selectedSlot)[0] ?? '18:00');
            $sessionPrice = ($startTime >= '18:00') ? $defaultPrime : $defaultBase;
            $basePrice = ($duration / 2) * $sessionPrice;
        }

        $paymentMethods = $this->getPaymentMethods();

        return view('pages.booking.checkout', compact('bookDate', 'selectedSlot', 'duration', 'basePrice', 'paymentMethods'));
    }

    /**
     * Handle Web form booking submission.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $booking = $this->bookingService->createBooking($request->user(), $validated);
        $isDp = strtoupper($validated['payment_type'] ?? '') === 'DP';

        if ($isDp) {
            $total = (float) $booking->total_price;
            $dpPaid = $total / 2;
            $remaining = $total - $dpPaid;
            $msg = "Pembayaran DP 50% sebesar Rp " . number_format($dpPaid, 0, ',', '.') . " berhasil! Kode booking: {$booking->booking_id_string}. Sisa tagihan Rp " . number_format($remaining, 0, ',', '.') . " dapat dilunasi secara online di halaman 'Booking Saya' atau langsung di kasir sebelum kickoff (batas toleransi 1 jam setelah jam mulai).";
        } else {
            $msg = "Pembayaran Lunas 100% sebesar Rp " . number_format($booking->total_price, 0, ',', '.') . " berhasil dikonfirmasi! Kode booking: {$booking->booking_id_string}.";
        }

        return redirect()
            ->route('booking.my')
            ->with('success', $msg);
    }

    /**
     * Display the My Bookings data table with settlement options.
     */
    public function myBookings(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $bookings = $this->bookingService->getUserBookings($request->user(), $statusFilter);
        $paymentMethods = $this->getPaymentMethods();

        // Check if user has active confirmed DP bookings with remaining balance
        $hasUnpaidDp = $bookings->contains(function ($b) {
            return $b['status'] === 'confirmed' && !($b['is_fully_paid'] ?? false) && ($b['remaining_amount'] ?? 0) > 0;
        });

        return view('pages.booking.my-bookings', compact('bookings', 'statusFilter', 'paymentMethods', 'hasUnpaidDp'));
    }

    /**
     * Display the Settlement Checkout & Payment page for an active DP booking.
     */
    public function settlePage(Request $request, string|int $id): View|RedirectResponse
    {
        $this->bookingService->checkAndExpireOverdueBookings();

        $booking = is_numeric($id)
            ? Booking::with(['payments', 'user'])->where('id', $id)->where('user_id', $request->user()->id)->firstOrFail()
            : Booking::with(['payments', 'user'])->where('booking_id_string', $id)->where('user_id', $request->user()->id)->firstOrFail();

        if ($booking->status === 'cancelled') {
            return redirect()
                ->route('booking.my')
                ->with('error', "Booking {$booking->booking_id_string} telah dibatalkan atau waktu toleransinya telah habis (DP Hangus).");
        }

        $remainingAmount = $booking->remainingAmount();
        if ($remainingAmount <= 0) {
            return redirect()
                ->route('booking.my')
                ->with('info', "Booking {$booking->booking_id_string} sudah berstatus LUNAS 100%.");
        }

        $totalPrice = (float) $booking->total_price;
        $paidAmount = $booking->paidAmount();
        $paymentMethods = $this->getPaymentMethods();

        return view('pages.booking.settle-checkout', compact('booking', 'remainingAmount', 'paidAmount', 'totalPrice', 'paymentMethods'));
    }

    /**
     * Settle remaining balance online via Web Payment Gateway simulation.
     */
    public function settleOnline(Request $request, int|string $id): JsonResponse|RedirectResponse
    {
        $request->validate([
            'payment_method' => ['required', 'string', 'in:QRIS,BCA,Mandiri,BRI,DANA,GoPay'],
        ]);

        $booking = is_numeric($id)
            ? Booking::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail()
            : Booking::where('booking_id_string', $id)->where('user_id', $request->user()->id)->firstOrFail();

        if ($booking->status === 'cancelled') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking ini telah dibatalkan atau hangus.',
                ], 422);
            }
            return redirect()->route('booking.my')->with('error', "Booking {$booking->booking_id_string} telah dibatalkan atau hangus.");
        }

        $remaining = $booking->remainingAmount();
        if ($remaining <= 0) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'info',
                    'message' => "Booking {$booking->booking_id_string} sudah lunas.",
                    'redirect_url' => route('booking.my'),
                ]);
            }
            return redirect()->route('booking.my')->with('info', "Booking {$booking->booking_id_string} sudah lunas.");
        }

        $settledBooking = $this->bookingService->settleBookingOnline($booking, $request->input('payment_method'));
        $formattedRemaining = number_format($remaining, 0, ',', '.');
        $msg = "Pelunasan online booking {$settledBooking->booking_id_string} sebesar Rp {$formattedRemaining} via {$request->input('payment_method')} berhasil! Status booking Anda kini telah LUNAS 100%.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $msg,
                'redirect_url' => route('booking.my'),
                'data' => [
                    'booking_id' => $settledBooking->booking_id_string,
                    'status' => $settledBooking->status,
                    'is_fully_paid' => true,
                    'remaining_amount' => 0,
                ],
            ]);
        }

        return redirect()
            ->route('booking.my')
            ->with('success', $msg);
    }

    /*
    |--------------------------------------------------------------------------
    | API ENDPOINTS (Customer Flow)
    |--------------------------------------------------------------------------
    */

    /**
     * API: Get slot availability for a specified date (PRD 8.1).
     * Endpoint: GET /api/schedule?date=YYYY-MM-DD
     */
    public function apiSchedule(Request $request): JsonResponse
    {
        $date = $request->query('date', Carbon::today()->format('Y-m-d'));
        $slots = $this->bookingService->getSlotsForDate($date);

        return response()->json([
            'status' => 'success',
            'arena' => FieldSetting::get('arena_name', 'Socstreet Main Arena'),
            'date' => $date,
            'slots' => $slots,
        ]);
    }

    /**
     * API: Create a booking and its payment record (PRD 4.4 & 6).
     * Endpoint: POST /api/bookings
     */
    public function apiStore(StoreBookingRequest $request): JsonResponse
    {
        $booking = $this->bookingService->createBooking($request->user(), $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Booking berhasil dibuat.',
            'data' => [
                'booking_id' => $booking->id,
                'booking_id_string' => $booking->booking_id_string,
                'book_date' => $booking->book_date->format('Y-m-d'),
                'time_slot' => "{$booking->start_time} - {$booking->end_time}",
                'duration' => $booking->duration,
                'total_price' => (float) $booking->total_price,
                'status' => $booking->status,
                'payment' => $booking->latestPayment ? [
                    'id' => $booking->latestPayment->id,
                    'payment_type' => $booking->latestPayment->payment_type,
                    'payment_method' => $booking->latestPayment->payment_method,
                    'amount' => (float) $booking->latestPayment->amount,
                    'status' => $booking->latestPayment->status,
                ] : null,
            ],
        ], 201);
    }

    /**
     * API: Get list of logged-in customer's bookings (PRD 4.5).
     * Endpoint: GET /api/my-bookings
     */
    public function apiMyBookings(Request $request): JsonResponse
    {
        $statusFilter = $request->query('status', 'all');
        $bookings = $this->bookingService->getUserBookings($request->user(), $statusFilter);

        return response()->json([
            'status' => 'success',
            'data' => $bookings,
        ]);
    }

    /**
     * API: Get specific booking details.
     * Endpoint: GET /api/bookings/{id}
     */
    public function apiShow(int $id, Request $request): JsonResponse
    {
        $booking = Booking::with(['payments', 'user'])->find($id);

        if (! $booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data booking tidak ditemukan.',
            ], 404);
        }

        // Authorization: only the owner or an admin can view
        if ($booking->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki hak akses untuk booking ini.',
            ], 403);
        }

        $latestPayment = $booking->latestPayment;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $booking->id,
                'booking_id_string' => $booking->booking_id_string,
                'username' => $booking->user->username,
                'book_date' => $booking->book_date->format('Y-m-d'),
                'time_slot' => "{$booking->start_time} - {$booking->end_time}",
                'duration' => $booking->duration,
                'total_price' => (float) $booking->total_price,
                'status' => $booking->status,
                'payment' => $latestPayment ? [
                    'payment_type' => $latestPayment->payment_type,
                    'payment_method' => $latestPayment->payment_method,
                    'amount' => (float) $latestPayment->amount,
                    'status' => $latestPayment->status,
                ] : null,
                'created_at' => $booking->created_at->toIso8601String(),
            ],
        ]);
    }
}
