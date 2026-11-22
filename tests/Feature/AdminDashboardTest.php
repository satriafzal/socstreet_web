<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'superadmin',
            'email' => 'admin@socstreet.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'role_id' => 1,
            'must_change_password' => false,
        ]);

        $this->customer = User::create([
            'username' => 'striker_user',
            'email' => 'striker@socstreet.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'role_id' => 2,
            'must_change_password' => false,
        ]);
    }

    public function test_guest_cannot_access_admin_dashboard()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->customer)->get('/admin/dashboard');
        $response->assertRedirect(route('booking.schedule'));
    }

    public function test_admin_can_view_dashboard_with_real_calculated_metrics_and_recent_bookings()
    {
        $today = Carbon::today()->format('Y-m-d');

        // Booking 1: Pending payment
        $booking1 = Booking::create([
            'booking_id_string' => 'INV-20261122-001',
            'user_id' => $this->customer->id,
            'book_date' => $today,
            'start_time' => '18:00',
            'end_time' => '20:00',
            'duration' => 2,
            'status' => 'pending_payment',
            'total_price' => 350000,
        ]);

        Payment::create([
            'booking_id' => $booking1->id,
            'payment_type' => 'DP',
            'payment_method' => 'QRIS',
            'amount' => 175000,
            'status' => 'pending',
        ]);

        // Booking 2: Confirmed today
        $booking2 = Booking::create([
            'booking_id_string' => 'INV-20261122-002',
            'user_id' => $this->customer->id,
            'book_date' => $today,
            'start_time' => '20:00',
            'end_time' => '22:00',
            'duration' => 2,
            'status' => 'confirmed',
            'total_price' => 350000,
        ]);

        Payment::create([
            'booking_id' => $booking2->id,
            'payment_type' => 'FULL',
            'payment_method' => 'BCA',
            'amount' => 350000,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);

        // Check metrics
        $response->assertSee('Admin Dashboard');
        $response->assertSee('INV-20261122-001');
        $response->assertSee('INV-20261122-002');
        $response->assertSee('striker_user');
        $response->assertSee('1 Booking'); // 1 pending booking
        $response->assertSee('1 Sesi'); // 1 confirmed session today
    }

    public function test_admin_can_confirm_booking_via_quick_action()
    {
        $today = Carbon::today()->format('Y-m-d');

        $booking = Booking::create([
            'booking_id_string' => 'INV-CONFIRM-01',
            'user_id' => $this->customer->id,
            'book_date' => $today,
            'start_time' => '16:00',
            'end_time' => '18:00',
            'duration' => 2,
            'status' => 'pending_payment',
            'total_price' => 300000,
        ]);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_type' => 'FULL',
            'payment_method' => 'QRIS',
            'amount' => 300000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->postJson("/admin/bookings/{$booking->id}/confirm");
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'booking_id' => 'INV-CONFIRM-01',
                'status' => 'confirmed',
            ],
        ]);

        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status);

        $payment->refresh();
        $this->assertEquals('paid', $payment->status);
    }

    public function test_admin_can_reject_booking_via_quick_action_and_slot_is_freed()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $booking = Booking::create([
            'booking_id_string' => 'INV-REJECT-01',
            'user_id' => $this->customer->id,
            'book_date' => $tomorrow,
            'start_time' => '18:00',
            'end_time' => '20:00',
            'duration' => 2,
            'status' => 'pending_payment',
            'total_price' => 350000,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'payment_type' => 'DP',
            'payment_method' => 'GoPay',
            'amount' => 175000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->postJson("/admin/bookings/{$booking->booking_id_string}/reject");
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'booking_id' => 'INV-REJECT-01',
                'status' => 'cancelled',
            ],
        ]);

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);

        // Verify customer schedule now sees this slot as available again
        $scheduleResponse = $this->actingAs($this->customer)->getJson("/api/schedule?date={$tomorrow}");
        $scheduleResponse->assertStatus(200);
        $slot = collect($scheduleResponse->json('slots'))->firstWhere('time', '18:00 - 20:00');
        $this->assertEquals('available', $slot['status']);
        $this->assertTrue($slot['is_available']);
    }

    public function test_api_admin_dashboard_returns_metrics_and_recent_bookings_json()
    {
        $response = $this->actingAs($this->admin)->getJson('/api/admin/dashboard');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'metrics' => [
                'revenue_raw',
                'revenue',
                'pending_count',
                'today_sessions',
            ],
            'recent_bookings',
        ]);
        $response->assertJson([
            'status' => 'success',
        ]);
    }
}
