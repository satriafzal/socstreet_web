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
            ],
            [
                'id' => 'BCA',
                'name' => 'BCA Virtual Account',
                'category' => 'Virtual Account',
                'desc' => 'Verifikasi instan via m-BCA / KlikBCA',
                'badge' => 'Otomatis',
            ],
            [
                'id' => 'Mandiri',
                'name' => 'Mandiri Livin',
                'category' => 'Virtual Account',
                'desc' => 'Bayar via Livin by Mandiri & ATM',
                'badge' => 'Otomatis',
            ],
            [
                'id' => 'BRI',
                'name' => 'BRI BRIVA',
                'category' => 'Virtual Account',
                'desc' => 'Bayar via BRImo atau ATM BRI',
                'badge' => 'Otomatis',
            ],
            [
                'id' => 'DANA',
                'name' => 'DANA Wallet',
                'category' => 'E-Wallet',
                'desc' => 'Pembayaran langsung akun dompet DANA',
                'badge' => 'Instan',
            ],
            [
                'id' => 'GoPay',
                'name' => 'GoPay',
                'category' => 'E-Wallet',
                'desc' => 'Konfirmasi pembayaran via aplikasi Gojek',
                'badge' => 'Instan',
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
        $booking = $this->bookingService->createBooking($request->user(), $request->validated());

        return redirect()
            ->route('booking.my')
            ->with('success', "Booking lapangan berhasil dibuat dengan kode {$booking->booking_id_string}!");
    }

    /**
     * Display the My Bookings data table (PRD 4.5: No action buttons on rows).
     */
    public function myBookings(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $bookings = $this->bookingService->getUserBookings($request->user(), $statusFilter);

        return view('pages.booking.my-bookings', compact('bookings', 'statusFilter'));
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
