<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

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
    }

    public function test_unauthenticated_user_cannot_access_booking_schedule_and_is_redirected_to_login()
    {
        $response = $this->get('/schedule');
        $response->assertRedirect(route('login'));
    }

    public function test_unauthenticated_user_cannot_access_checkout_and_is_redirected_to_login()
    {
        $response = $this->get('/checkout');
        $response->assertRedirect(route('login'));
    }

    public function test_unauthenticated_user_cannot_access_my_bookings_and_is_redirected_to_login()
    {
        $response = $this->get('/my-bookings');
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_schedule_with_two_hour_sessions()
    {
        $response = $this->actingAs($this->user)->get('/schedule');
        $response->assertStatus(200);
        $response->assertSee('Aturan Durasi Booking Lapangan');
        $response->assertSee('2 Jam');
        $response->assertSee('08:00 - 10:00');
        $response->assertSee('18:00 - 20:00');
    }

    public function test_authenticated_user_can_access_checkout_with_two_hour_duration()
    {
        $response = $this->actingAs($this->user)->get('/checkout?slot=18:00 - 20:00&duration=2&price=350000');
        $response->assertStatus(200);
        $response->assertSee('Checkout & Pembayaran');
        $response->assertSee('18:00 - 20:00');
        $response->assertSee('2 Jam');
    }

    public function test_authenticated_user_can_access_my_bookings()
    {
        $response = $this->actingAs($this->user)->get('/my-bookings');
        $response->assertStatus(200);
        $response->assertSee('Booking Saya');
        $response->assertSee('18:00 - 20:00');
    }
}
