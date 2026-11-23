<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\FieldSetting;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BookingSettleTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'username' => 'testuser',
            'email' => 'test@socstreet.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'role_id' => 2,
        ]);

        $this->otherUser = User::create([
            'username' => 'otheruser',
            'email' => 'other@socstreet.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'role_id' => 2,
        ]);

        FieldSetting::set('arena_name', 'Socstreet Main Arena');
        FieldSetting::set('base_price', '300000');
        FieldSetting::set('prime_price', '350000');
    }

    public function test_customer_can_access_settle_page_for_dp_booking()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $booking = Booking::create([
            'booking_id_string' => 'INV-TEST-DP-001',
            'user_id' => $this->user->id,
            'book_date' => $tomorrow,
            'start_time' => '18:00',
            'end_time' => '20:00',
            'duration' => 2,
            'status' => 'confirmed',
            'total_price' => 350000,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'payment_type' => 'DP',
            'payment_method' => 'QRIS',
            'amount' => 175000,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($this->user)->get("/bookings/{$booking->booking_id_string}/settle");
        $response->assertStatus(200);
        $response->assertSee('Konfirmasi Pelunasan Sisa Pembayaran DP');
        $response->assertSee('INV-TEST-DP-001');
        $response->assertSee('175.000');
        $response->assertSee('function openPaymentModal', false);
        $response->assertSee('handlePaymentSubmit', false);
    }

    public function test_customer_can_submit_online_settlement_and_it_becomes_fully_paid()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $booking = Booking::create([
            'booking_id_string' => 'INV-TEST-DP-002',
            'user_id' => $this->user->id,
            'book_date' => $tomorrow,
            'start_time' => '18:00',
            'end_time' => '20:00',
            'duration' => 2,
            'status' => 'confirmed',
            'total_price' => 350000,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'payment_type' => 'DP',
            'payment_method' => 'BCA',
            'amount' => 175000,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($this->user)->post("/bookings/{$booking->booking_id_string}/settle", [
            'payment_method' => 'QRIS',
        ]);

        $response->assertRedirect(route('booking.my'));
        $response->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals(0, $booking->remainingAmount());
        $this->assertTrue($booking->isFullyPaid());
        $this->assertEquals('confirmed', $booking->status);

        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'payment_type' => 'FULL',
            'payment_method' => 'QRIS',
            'amount' => 175000,
            'status' => 'paid',
        ]);
    }

    public function test_settle_page_redirects_if_already_fully_paid()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $booking = Booking::create([
            'booking_id_string' => 'INV-TEST-DP-003',
            'user_id' => $this->user->id,
            'book_date' => $tomorrow,
            'start_time' => '18:00',
            'end_time' => '20:00',
            'duration' => 2,
            'status' => 'confirmed',
            'total_price' => 350000,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'payment_type' => 'FULL',
            'payment_method' => 'QRIS',
            'amount' => 350000,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($this->user)->get("/bookings/{$booking->booking_id_string}/settle");
        $response->assertRedirect(route('booking.my'));
        $response->assertSessionHas('info');
    }

    public function test_customer_cannot_settle_another_users_booking()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $booking = Booking::create([
            'booking_id_string' => 'INV-TEST-DP-004',
            'user_id' => $this->otherUser->id,
            'book_date' => $tomorrow,
            'start_time' => '18:00',
            'end_time' => '20:00',
            'duration' => 2,
            'status' => 'confirmed',
            'total_price' => 350000,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'payment_type' => 'DP',
            'payment_method' => 'QRIS',
            'amount' => 175000,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($this->user)->get("/bookings/{$booking->booking_id_string}/settle");
        $response->assertStatus(404);
    }
}
