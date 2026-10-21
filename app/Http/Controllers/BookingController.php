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

        // Available time slots from 08:00 to 23:00
        $allSlots = [
            ['time' => '08:00 - 09:00', 'price' => 200000, 'category' => 'Pagi', 'is_booked' => false],
            ['time' => '09:00 - 10:00', 'price' => 200000, 'category' => 'Pagi', 'is_booked' => true],
            ['time' => '10:00 - 11:00', 'price' => 200000, 'category' => 'Pagi', 'is_booked' => false],
            ['time' => '11:00 - 12:00', 'price' => 200000, 'category' => 'Siang', 'is_booked' => false],
            ['time' => '13:00 - 14:00', 'price' => 220000, 'category' => 'Siang', 'is_booked' => false],
            ['time' => '14:00 - 15:00', 'price' => 220000, 'category' => 'Siang', 'is_booked' => false],
            ['time' => '15:00 - 16:00', 'price' => 250000, 'category' => 'Sore', 'is_booked' => true],
            ['time' => '16:00 - 17:00', 'price' => 250000, 'category' => 'Sore', 'is_booked' => true],
            ['time' => '17:00 - 18:00', 'price' => 250000, 'category' => 'Sore', 'is_booked' => false],
            ['time' => '18:00 - 19:00', 'price' => 275000, 'category' => 'Malam (Prime)', 'is_booked' => false],
            ['time' => '19:00 - 20:00', 'price' => 275000, 'category' => 'Malam (Prime)', 'is_booked' => true],
            ['time' => '20:00 - 21:00', 'price' => 275000, 'category' => 'Malam (Prime)', 'is_booked' => false],
            ['time' => '21:00 - 22:00', 'price' => 250000, 'category' => 'Malam', 'is_booked' => false],
            ['time' => '22:00 - 23:00', 'price' => 250000, 'category' => 'Malam', 'is_booked' => false],
        ];

        return view('pages.booking.schedule', compact('selectedDate', 'availableDates', 'allSlots'));
    }

    /**
     * Display the Checkout & Payment page.
     */
    public function checkout(Request $request): View
    {
        $bookDate = $request->query('date', Carbon::today()->format('Y-m-d'));
        $selectedSlot = $request->query('slot', '20:00 - 21:00');
        $basePrice = (int) $request->query('price', 275000);

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

        return view('pages.booking.checkout', compact('bookDate', 'selectedSlot', 'basePrice', 'paymentMethods'));
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
                'start_time' => '20:00',
                'end_time' => '21:00',
                'payment_type' => 'DP (50%)',
                'payment_method' => 'QRIS',
                'total_price' => 275000,
                'amount_paid' => 137500,
                'status' => 'confirmed', // pending_payment, confirmed, cancelled
                'status_label' => 'Dikonfirmasi',
            ],
            [
                'id' => 2,
                'booking_id_string' => 'INV-20261022-004',
                'book_date' => Carbon::today()->addDays(1)->format('d M Y'),
                'start_time' => '18:00',
                'end_time' => '19:00',
                'payment_type' => 'Lunas (100%)',
                'payment_method' => 'BCA Virtual Account',
                'total_price' => 275000,
                'amount_paid' => 275000,
                'status' => 'pending_payment',
                'status_label' => 'Menunggu Pembayaran',
            ],
            [
                'id' => 3,
                'booking_id_string' => 'INV-20261018-009',
                'book_date' => Carbon::today()->subDays(3)->format('d M Y'),
                'start_time' => '16:00',
                'end_time' => '17:00',
                'payment_type' => 'Lunas (100%)',
                'payment_method' => 'GoPay',
                'total_price' => 250000,
                'amount_paid' => 250000,
                'status' => 'confirmed',
                'status_label' => 'Selesai',
            ],
            [
                'id' => 4,
                'booking_id_string' => 'INV-20261012-015',
                'book_date' => Carbon::today()->subDays(9)->format('d M Y'),
                'start_time' => '21:00',
                'end_time' => '22:00',
                'payment_type' => 'DP (50%)',
                'payment_method' => 'Mandiri',
                'total_price' => 250000,
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

