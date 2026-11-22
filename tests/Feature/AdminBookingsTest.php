<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminBookingsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin_super',
            'email' => 'admin@socstreet.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'role_id' => 1,
            'must_change_password' => false,
        ]);

        $this->customer = User::create([
            'username' => 'bambang_fc',
            'email' => 'bambang@socstreet.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'role_id' => 2,
            'must_change_password' => false,
        ]);
    }

    public function test_guest_cannot_access_admin_bookings()
    {
        $response = $this->get('/admin/bookings');
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_bookings()
    {
        $response = $this->actingAs($this->customer)->get('/admin/bookings');
        $response->assertRedirect(route('booking.schedule'));
    }

    public function test_admin_can_view_manage_bookings_page_with_real_data()
    {
        $today = Carbon::today()->toDateString();

        $booking1 = Booking::create([
            'booking_id_string' => 'INV-20261122-001',
            'user_id' => $this->customer->id,
            'book_date' => $today,
            'start_time' => '18:00',
            'end_time' => '20:00',
            'duration' => 2,
            'status' => 'pending_payment',
            'total_price' => 300000,
        ]);

        Payment::create([
            'booking_id' => $booking1->id,
            'payment_type' => 'DP',
            'payment_method' => 'QRIS',
            'amount' => 150000,
            'status' => 'pending',
        ]);

        $booking2 = Booking::create([
            'booking_id_string' => 'INV-20261122-002',
            'user_id' => $this->customer->id,
            'book_date' => $today,
            'start_time' => '20:00',
            'end_time' => '22:00',
            'duration' => 2,
            'status' => 'confirmed',
            'total_price' => 300000,
        ]);

        Payment::create([
            'booking_id' => $booking2->id,
            'payment_type' => 'FULL',
            'payment_method' => 'BCA',
            'amount' => 300000,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/bookings');

        $response->assertStatus(200);
        $response->assertSee('Kelola Booking &amp; Pembayaran', false);
        $response->assertSee('INV-20261122-001');
        $response->assertSee('INV-20261122-002');
        $response->assertSee('bambang_fc');
        $response->assertSee('DP 50%');
        $response->assertSee('Lunas 100%');
        $response->assertSee('Rp 150.000'); // Sisa bayar
        $response->assertSee('Rp 300.000'); // Total harga
    }

    public function test_admin_can_filter_bookings_by_search_status_and_payment_type()
    {
        $today = Carbon::today()->toDateString();

        $booking1 = Booking::create([
            'booking_id_string' => 'INV-001',
            'user_id' => $this->customer->id,
            'book_date' => $today,
            'start_time' => '14:00',
            'end_time' => '16:00',
            'duration' => 2,
            'status' => 'pending_payment',
            'total_price' => 300000,
        ]);

        Payment::create([
            'booking_id' => $booking1->id,
            'payment_type' => 'DP',
            'payment_method' => 'QRIS',
            'amount' => 150000,
            'status' => 'pending',
        ]);

        $booking2 = Booking::create([
            'booking_id_string' => 'INV-002',
            'user_id' => $this->customer->id,
            'book_date' => $today,
            'start_time' => '16:00',
            'end_time' => '18:00',
            'duration' => 2,
            'status' => 'confirmed',
            'total_price' => 300000,
        ]);

        Payment::create([
            'booking_id' => $booking2->id,
            'payment_type' => 'FULL',
            'payment_method' => 'GoPay',
            'amount' => 300000,
            'status' => 'paid',
        ]);

        // Filter status=pending
        $responsePending = $this->actingAs($this->admin)->get('/admin/bookings?status=pending');
        $responsePending->assertStatus(200);
        $responsePending->assertSee('INV-001');
        $responsePending->assertDontSee('INV-002');

        // Filter status=confirmed
        $responseConfirmed = $this->actingAs($this->admin)->get('/admin/bookings?status=confirmed');
        $responseConfirmed->assertStatus(200);
        $responseConfirmed->assertSee('INV-002');
        $responseConfirmed->assertDontSee('INV-001');

        // Filter payment_type=dp
        $responseDp = $this->actingAs($this->admin)->get('/admin/bookings?payment_type=dp');
        $responseDp->assertStatus(200);
        $responseDp->assertSee('INV-001');
        $responseDp->assertDontSee('INV-002');

        // Search by booking ID
        $responseSearch = $this->actingAs($this->admin)->get('/admin/bookings?search=INV-001');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('INV-001');
        $responseSearch->assertDontSee('INV-002');
    }

    public function test_admin_can_export_bookings_to_csv()
    {
        $today = Carbon::today()->toDateString();

        $booking = Booking::create([
            'booking_id_string' => 'INV-EXPORT-001',
            'user_id' => $this->customer->id,
            'book_date' => $today,
            'start_time' => '18:00',
            'end_time' => '20:00',
            'duration' => 2,
            'status' => 'confirmed',
            'total_price' => 300000,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'payment_type' => 'FULL',
            'payment_method' => 'BCA',
            'amount' => 300000,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/bookings/export');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('"Booking ID"', $content);
        $this->assertStringContainsString('INV-EXPORT-001', $content);
        $this->assertStringContainsString('bambang_fc', $content);
    }

    public function test_api_admin_bookings_returns_json_with_filters()
    {
        $today = Carbon::today()->toDateString();

        $booking = Booking::create([
            'booking_id_string' => 'INV-API-001',
            'user_id' => $this->customer->id,
            'book_date' => $today,
            'start_time' => '10:00',
            'end_time' => '12:00',
            'duration' => 2,
            'status' => 'pending_payment',
            'total_price' => 300000,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'payment_type' => 'DP',
            'payment_method' => 'QRIS',
            'amount' => 150000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->getJson('/api/admin/bookings');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'stats' => [
                'pending',
                'confirmed',
                'today_total',
                'total_received',
                'total_received_raw',
            ],
            'data' => [
                '*' => [
                    'id',
                    'booking_id',
                    'username',
                    'date',
                    'time_slot',
                    'payment_type_raw',
                    'payment_type',
                    'payment_method',
                    'total_price',
                    'remaining_payment',
                    'status',
                ],
            ],
            'count',
        ]);
        $response->assertJsonFragment([
            'booking_id' => 'INV-API-001',
            'username' => 'bambang_fc',
            'payment_type_raw' => 'dp',
            'status' => 'pending',
        ]);
    }

    public function test_admin_confirm_booking_locks_slot_and_marks_payment_paid()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $booking = Booking::create([
            'booking_id_string' => 'INV-CONFIRM-TEST',
            'user_id' => $this->customer->id,
            'book_date' => $tomorrow,
            'start_time' => '18:00',
            'end_time' => '20:00',
            'duration' => 2,
            'status' => 'pending_payment',
            'total_price' => 300000,
        ]);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_type' => 'DP',
            'payment_method' => 'QRIS',
            'amount' => 150000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->postJson("/admin/bookings/{$booking->booking_id_string}/confirm");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'booking_id' => 'INV-CONFIRM-TEST',
                'status' => 'confirmed',
            ],
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'paid',
        ]);

        // Verify slot is booked in BookingService
        $bookingService = app(BookingService::class);
        $slots = $bookingService->getSlotsForDate($tomorrow);
        $slot18 = collect($slots)->firstWhere('time', '18:00 - 20:00');
        $this->assertEquals('booked', $slot18['status']);
    }

    public function test_admin_reject_booking_frees_up_slot_and_marks_cancelled()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $booking = Booking::create([
            'booking_id_string' => 'INV-REJECT-TEST',
            'user_id' => $this->customer->id,
            'book_date' => $tomorrow,
            'start_time' => '20:00',
            'end_time' => '22:00',
            'duration' => 2,
            'status' => 'pending_payment',
            'total_price' => 300000,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'payment_type' => 'FULL',
            'payment_method' => 'BCA',
            'amount' => 300000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->postJson("/admin/bookings/{$booking->booking_id_string}/reject");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'booking_id' => 'INV-REJECT-TEST',
                'status' => 'cancelled',
            ],
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);

        // Slot must now be available!
        $bookingService = app(BookingService::class);
        $slots = $bookingService->getSlotsForDate($tomorrow);
        $slot20 = collect($slots)->firstWhere('time', '20:00 - 22:00');
        $this->assertEquals('available', $slot20['status']);
    }
}
