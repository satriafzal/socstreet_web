<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Display the Schedule Grid for the single field: Socstreet Main Arena.
     */
    public function schedule(Request $request): View
    {
        $selectedDate = $request->query('date', Carbon::today()->format('Y-m-d'));

        // Generate 7 upcoming days for the quick date picker
        $availableDates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->addDays($i);
            $availableDates[] = [
                'full_date' => $date->format('Y-m-d'),
                'day_name' => $i === 0 ? 'Hari Ini' : ($i === 1 ? 'Besok' : $date->locale('id')->isoFormat('dddd')),
                'date_short' => $date->isoFormat('D MMM'),
                'is_selected' => $date->format('Y-m-d') === $selectedDate,
            ];
        }

        // Available time slots (Strict 2-hour sessions: 08:00 to 22:00)
        // Minimum booking is 2 hours (1 session). Adding hours must be in increments of 2 hours.
        $allSlots = [
            ['time' => '08:00 - 10:00', 'duration' => 2, 'price' => 300000, 'category' => 'Pagi', 'is_booked' => false],
            ['time' => '10:00 - 12:00', 'duration' => 2, 'price' => 300000, 'category' => 'Siang', 'is_booked' => false],
            ['time' => '12:00 - 14:00', 'duration' => 2, 'price' => 300000, 'category' => 'Siang', 'is_booked' => false],
            ['time' => '14:00 - 16:00', 'duration' => 2, 'price' => 300000, 'category' => 'Sore', 'is_booked' => false],
            ['time' => '16:00 - 18:00', 'duration' => 2, 'price' => 300000, 'category' => 'Sore', 'is_booked' => true],
            ['time' => '18:00 - 20:00', 'duration' => 2, 'price' => 350000, 'category' => 'Malam (Prime)', 'is_booked' => false],
            ['time' => '20:00 - 22:00', 'duration' => 2, 'price' => 350000, 'category' => 'Malam (Prime)', 'is_booked' => true],
        ];

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
        $basePrice = (int) $request->query('price', 350000);

        $paymentMethods = [
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

        return view('pages.booking.checkout', compact('bookDate', 'selectedSlot', 'duration', 'basePrice', 'paymentMethods'));
    }

    /**
     * Display the My Bookings data table (PRD 4.5: No action buttons on rows).
     */
    public function myBookings(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');

        $bookings = [
            [
                'id' => 1,
                'booking_id_string' => 'INV-20261021-001',
                'book_date' => Carbon::today()->format('d M Y'),
                'start_time' => '18:00',
                'end_time' => '20:00',
                'duration' => 2,
                'payment_type' => 'DP (50%)',
                'payment_method' => 'QRIS',
                'total_price' => 350000,
                'amount_paid' => 175000,
                'status' => 'confirmed', // pending_payment, confirmed, cancelled
                'status_label' => 'Dikonfirmasi',
            ],
            [
                'id' => 2,
                'booking_id_string' => 'INV-20261022-004',
                'book_date' => Carbon::today()->addDays(1)->format('d M Y'),
                'start_time' => '20:00',
                'end_time' => '22:00',
                'duration' => 2,
                'payment_type' => 'Lunas (100%)',
                'payment_method' => 'BCA Virtual Account',
                'total_price' => 350000,
                'amount_paid' => 350000,
                'status' => 'pending_payment',
                'status_label' => 'Menunggu Pembayaran',
            ],
            [
                'id' => 3,
                'booking_id_string' => 'INV-20261018-009',
                'book_date' => Carbon::today()->subDays(3)->format('d M Y'),
                'start_time' => '14:00',
                'end_time' => '18:00',
                'duration' => 4,
                'payment_type' => 'Lunas (100%)',
                'payment_method' => 'GoPay',
                'total_price' => 600000,
                'amount_paid' => 600000,
                'status' => 'confirmed',
                'status_label' => 'Selesai',
            ],
            [
                'id' => 4,
                'booking_id_string' => 'INV-20261012-015',
                'book_date' => Carbon::today()->subDays(9)->format('d M Y'),
                'start_time' => '08:00',
                'end_time' => '10:00',
                'duration' => 2,
                'payment_type' => 'DP (50%)',
                'payment_method' => 'Mandiri',
                'total_price' => 300000,
                'amount_paid' => 0,
                'status' => 'cancelled',
                'status_label' => 'Dibatalkan',
            ],
        ];

        // Filter bookings if requested
        if ($statusFilter !== 'all') {
            $bookings = array_values(array_filter($bookings, fn($b) => $b['status'] === $statusFilter));
        }

        return view('pages.booking.my-bookings', compact('bookings', 'statusFilter'));
    }
}

