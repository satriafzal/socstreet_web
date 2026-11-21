<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard with summary metrics & recent incoming bookings.
     */
    public function dashboard(): View
    {
        $metrics = [
            'revenue' => 'Rp 12.450.000',
            'pending_count' => 3,
            'today_sessions' => 6,
        ];

        $recentBookings = [
            [
                'booking_id' => 'INV-012',
                'username' => 'satria_dev',
                'schedule' => Carbon::today()->isoFormat('D MMM') . ', 18:00 - 20:00',
                'payment_type' => 'DP 50%',
                'payment_method' => 'QRIS',
                'total' => 'Rp 150.000',
                'status' => 'pending',
            ],
            [
                'booking_id' => 'INV-011',
                'username' => 'bambang_fc',
                'schedule' => Carbon::today()->isoFormat('D MMM') . ', 20:00 - 22:00',
                'payment_type' => 'Lunas 100%',
                'payment_method' => 'BCA',
                'total' => 'Rp 300.000',
                'status' => 'pending',
            ],
            [
                'booking_id' => 'INV-010',
                'username' => 'tim_garuda',
                'schedule' => Carbon::today()->isoFormat('D MMM') . ', 16:00 - 18:00',
                'payment_type' => 'DP 50%',
                'payment_method' => 'GoPay',
                'total' => 'Rp 150.000',
                'status' => 'pending',
            ],
            [
                'booking_id' => 'INV-009',
                'username' => 'borneo_community',
                'schedule' => Carbon::yesterday()->isoFormat('D MMM') . ', 18:00 - 20:00',
                'payment_type' => 'Lunas 100%',
                'payment_method' => 'Mandiri',
                'total' => 'Rp 300.000',
                'status' => 'confirmed',
            ],
            [
                'booking_id' => 'INV-008',
                'username' => 'reza_striker',
                'schedule' => Carbon::yesterday()->isoFormat('D MMM') . ', 14:00 - 16:00',
                'payment_type' => 'DP 50%',
                'payment_method' => 'DANA',
                'total' => 'Rp 150.000',
                'status' => 'cancelled',
            ],
        ];

        return view('admin.dashboard', compact('metrics', 'recentBookings'));
    }

    /**
     * Display Manage Bookings with comprehensive filterable data table.
     */
    public function bookings(): View
    {
        $bookingStats = [
            'pending' => 2,
            'confirmed' => 24,
            'today_total' => 28,
            'total_received' => 'Rp 3.825k',
        ];

        $today = Carbon::today()->isoFormat('D MMM YYYY');
        $tomorrow = Carbon::tomorrow()->isoFormat('D MMM YYYY');
        $dayAfter = Carbon::today()->addDays(2)->isoFormat('D MMM YYYY');

        $bookings = [
            [
                'booking_id' => 'INV-012',
                'username' => 'satria_dev',
                'date' => $today,
                'time_slot' => '18:00 - 20:00',
                'payment_type_raw' => 'dp',
                'payment_method' => 'QRIS',
                'total_price' => 'Rp 300.000',
                'remaining_payment' => 'Rp 150.000',
                'status' => 'pending',
            ],
            [
                'booking_id' => 'INV-011',
                'username' => 'bambang_fc',
                'date' => $today,
                'time_slot' => '20:00 - 22:00',
                'payment_type_raw' => 'full',
                'payment_method' => 'BCA',
                'total_price' => 'Rp 300.000',
                'remaining_payment' => 'Rp 0',
                'status' => 'pending',
            ],
            [
                'booking_id' => 'INV-010',
                'username' => 'tim_garuda',
                'date' => $tomorrow,
                'time_slot' => '16:00 - 18:00',
                'payment_type_raw' => 'dp',
                'payment_method' => 'GoPay',
                'total_price' => 'Rp 300.000',
                'remaining_payment' => 'Rp 150.000',
                'status' => 'confirmed',
            ],
            [
                'booking_id' => 'INV-009',
                'username' => 'borneo_community',
                'date' => $tomorrow,
                'time_slot' => '18:00 - 20:00',
                'payment_type_raw' => 'full',
                'payment_method' => 'Mandiri',
                'total_price' => 'Rp 300.000',
                'remaining_payment' => 'Rp 0',
                'status' => 'confirmed',
            ],
            [
                'booking_id' => 'INV-008',
                'username' => 'reza_striker',
                'date' => $dayAfter,
                'time_slot' => '14:00 - 16:00',
                'payment_type_raw' => 'dp',
                'payment_method' => 'DANA',
                'total_price' => 'Rp 300.000',
                'remaining_payment' => 'Rp 150.000',
                'status' => 'cancelled',
            ],
            [
                'booking_id' => 'INV-007',
                'username' => 'kartika_fc',
                'date' => $dayAfter,
                'time_slot' => '18:00 - 20:00',
                'payment_type_raw' => 'full',
                'payment_method' => 'BRI',
                'total_price' => 'Rp 300.000',
                'remaining_payment' => 'Rp 0',
                'status' => 'confirmed',
            ],
            [
                'booking_id' => 'INV-006',
                'username' => 'alif_putra',
                'date' => $today,
                'time_slot' => '08:00 - 10:00',
                'payment_type_raw' => 'full',
                'payment_method' => 'QRIS',
                'total_price' => 'Rp 300.000',
                'remaining_payment' => 'Rp 0',
                'status' => 'confirmed',
            ],
        ];

        return view('admin.bookings', compact('bookingStats', 'bookings'));
    }

    /**
     * Display Schedule & Pricing management page with 2-hour fixed session controls.
     */
    public function schedule(): View
    {
        $pricing = [
            'price' => 300000,
            'price_formatted' => '300.000',
            'operating_hours' => '08:00 - 22:00',
            'duration' => '120 Menit',
            'total_slots' => 7,
        ];

        $blockedSchedules = [
            [
                'id' => 1,
                'date' => Carbon::today()->addDays(3)->isoFormat('D MMM YYYY'),
                'time_slot' => '18:00 - 20:00',
                'reason' => 'Perawatan Rumput Sintetis',
            ],
            [
                'id' => 2,
                'date' => Carbon::today()->addDays(5)->isoFormat('D MMM YYYY'),
                'time_slot' => '16:00 - 18:00',
                'reason' => 'Turnamen Internal RW 05',
            ],
        ];

        return view('admin.schedule', compact('pricing', 'blockedSchedules'));
    }

    /**
     * Display User Management page with reset password and delete action controls.
     */
    public function users(): View
    {
        $userStats = [
            'active_members' => 3,
            'admins' => 1,
            'total_sessions' => '9 Sesi',
            'action_needed' => '1 Akun',
        ];

        $users = [
            [
                'id' => 'USR-001',
                'username' => 'satria_dev',
                'role' => 'User',
                'total_bookings' => 5,
                'must_change_password' => false,
                'created_at' => '14 Okt 2026',
            ],
            [
                'id' => 'USR-002',
                'username' => 'tim_garuda',
                'role' => 'User',
                'total_bookings' => 3,
                'must_change_password' => false,
                'created_at' => '18 Okt 2026',
            ],
            [
                'id' => 'USR-003',
                'username' => 'borneo_community',
                'role' => 'User',
                'total_bookings' => 1,
                'must_change_password' => true,
                'created_at' => '25 Okt 2026',
            ],
            [
                'id' => 'ADM-001',
                'username' => 'admin_soc',
                'role' => 'Admin',
                'total_bookings' => 0,
                'must_change_password' => false,
                'created_at' => '1 Okt 2026',
            ],
        ];

        return view('admin.users', compact('userStats', 'users'));
    }

    /**
     * Display Admin Profile & Security Settings page.
     */
    public function settings(): View
    {
        $admin = [
            'id' => 'ADM-001',
            'username' => 'admin_soc',
            'role' => 'Super Administrator',
            'created_at' => '1 Oktober 2026',
        ];

        return view('admin.settings', compact('admin'));
    }
}
