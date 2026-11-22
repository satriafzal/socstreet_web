<?php

namespace Tests\Feature;

use App\Models\BlockedSchedule;
use App\Models\Booking;
use App\Models\FieldSetting;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BookingFlowTest extends TestCase
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

    public function test_schedule_reflects_blocked_and_booked_slots_from_database()
    {
        $today = Carbon::today()->format('Y-m-d');

        // Slot 1: Blocked by admin
        BlockedSchedule::create([
            'date' => $today,
            'start_time' => '08:00',
            'end_time' => '10:00',
            'reason' => 'Perawatan Rumput Sintetis',
        ]);

        // Slot 2: Booked by other user
        Booking::create([
            'booking_id_string' => 'INV-TEST-001',
            'user_id' => $this->otherUser->id,
            'book_date' => $today,
            'start_time' => '18:00',
            'end_time' => '20:00',
            'duration' => 2,
            'status' => 'confirmed',
            'total_price' => 350000,
        ]);

        $response = $this->actingAs($this->user)->get("/schedule?date={$today}");
        $response->assertStatus(200);
        $response->assertSee('Perawatan Rumput Sintetis');
        $response->assertSee('Maintenance');
        $response->assertSee('Terisi');
    }

    public function test_authenticated_user_can_access_checkout_with_two_hour_duration()
    {
        $response = $this->actingAs($this->user)->get('/checkout?slot=18:00 - 20:00&duration=2&price=350000');
        $response->assertStatus(200);
        $response->assertSee('Checkout & Pembayaran');
        $response->assertSee('18:00 - 20:00');
        $response->assertSee('2 Jam');
    }

    public function test_user_can_submit_booking_via_web_form_and_it_creates_records_in_db()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $response = $this->actingAs($this->user)->post('/bookings', [
            'book_date' => $tomorrow,
            'time_slot' => '18:00 - 20:00',
            'payment_type' => 'DP',
            'payment_method' => 'QRIS',
        ]);

        $response->assertRedirect(route('booking.my'));
        $response->assertSessionHas('success');

        $booking = Booking::where('user_id', $this->user->id)->first();
        $this->assertNotNull($booking);
        $this->assertEquals($tomorrow, $booking->book_date->format('Y-m-d'));
        $this->assertEquals('18:00', $booking->start_time);
        $this->assertEquals('20:00', $booking->end_time);
        $this->assertEquals(2, $booking->duration);
        $this->assertEquals('pending_payment', $booking->status);
        $this->assertEquals(350000, $booking->total_price);

        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'payment_type' => 'DP',
            'payment_method' => 'QRIS',
            'amount' => 175000, // 50% of 350000
            'status' => 'pending',
        ]);
    }

    public function test_cannot_double_book_already_reserved_slot()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        // Other user booked it first
        Booking::create([
            'booking_id_string' => 'INV-FIRST-001',
            'user_id' => $this->otherUser->id,
            'book_date' => $tomorrow,
            'start_time' => '18:00',
            'end_time' => '20:00',
            'duration' => 2,
            'status' => 'confirmed',
            'total_price' => 350000,
        ]);

        // User attempts to book the exact same slot
        $response = $this->actingAs($this->user)->from('/checkout')->post('/bookings', [
            'book_date' => $tomorrow,
            'time_slot' => '18:00 - 20:00',
            'payment_type' => 'FULL',
            'payment_method' => 'BCA',
        ]);

        $response->assertSessionHasErrors('time_slot');
        $this->assertEquals(1, Booking::whereDate('book_date', $tomorrow)->count());
    }

    public function test_authenticated_user_can_access_my_bookings_page()
    {
        $today = Carbon::today()->format('Y-m-d');

        $booking = Booking::create([
            'booking_id_string' => 'INV-USER-999',
            'user_id' => $this->user->id,
            'book_date' => $today,
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

        $response = $this->actingAs($this->user)->get('/my-bookings');
        $response->assertStatus(200);
        $response->assertSee('Booking Saya');
        $response->assertSee('INV-USER-999');
        $response->assertSee('18:00 - 20:00');
        $response->assertSee('Dikonfirmasi');
    }

    /*
    |--------------------------------------------------------------------------
    | API TESTS (Customer Flow)
    |--------------------------------------------------------------------------
    */

    public function test_api_schedule_returns_slots_json()
    {
        $today = Carbon::today()->format('Y-m-d');

        $response = $this->getJson("/api/schedule?date={$today}");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'arena',
            'date',
            'slots' => [
                '*' => ['time', 'start_time', 'end_time', 'duration', 'price', 'category', 'status', 'is_available', 'is_booked', 'is_blocked'],
            ],
        ]);
        $response->assertJson([
            'status' => 'success',
            'arena' => 'Socstreet Main Arena',
            'date' => $today,
        ]);
    }

    public function test_api_create_booking_creates_booking_and_payment_successfully()
    {
        $targetDate = Carbon::today()->addDays(2)->format('Y-m-d');

        $response = $this->actingAs($this->user)->postJson('/api/bookings', [
            'book_date' => $targetDate,
            'time_slot' => '20:00 - 22:00',
            'payment_type' => 'FULL',
            'payment_method' => 'GoPay',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Booking berhasil dibuat.',
            'data' => [
                'book_date' => $targetDate,
                'time_slot' => '20:00 - 22:00',
                'duration' => 2,
                'total_price' => 350000,
                'status' => 'pending_payment',
                'payment' => [
                    'payment_type' => 'FULL',
                    'payment_method' => 'GoPay',
                    'amount' => 350000,
                    'status' => 'pending',
                ],
            ],
        ]);
    }

    public function test_api_my_bookings_returns_user_booking_history()
    {
        $today = Carbon::today()->format('Y-m-d');

        $booking = Booking::create([
            'booking_id_string' => 'INV-API-001',
            'user_id' => $this->user->id,
            'book_date' => $today,
            'start_time' => '14:00',
            'end_time' => '16:00',
            'duration' => 2,
            'status' => 'confirmed',
            'total_price' => 300000,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'payment_type' => 'DP',
            'payment_method' => 'BCA',
            'amount' => 150000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/my-bookings');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => ['id', 'booking_id_string', 'book_date', 'start_time', 'end_time', 'duration', 'payment_type', 'payment_method', 'total_price', 'amount_paid', 'status', 'status_label'],
            ],
        ]);
        $response->assertJsonFragment([
            'booking_id_string' => 'INV-API-001',
            'status' => 'confirmed',
        ]);
    }

    public function test_api_show_booking_detail()
    {
        $today = Carbon::today()->format('Y-m-d');

        $booking = Booking::create([
            'booking_id_string' => 'INV-DETAIL-001',
            'user_id' => $this->user->id,
            'book_date' => $today,
            'start_time' => '10:00',
            'end_time' => '12:00',
            'duration' => 2,
            'status' => 'pending_payment',
            'total_price' => 300000,
        ]);

        $response = $this->actingAs($this->user)->getJson("/api/bookings/{$booking->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'id' => $booking->id,
                'booking_id_string' => 'INV-DETAIL-001',
                'username' => 'testuser',
                'time_slot' => '10:00 - 12:00',
                'total_price' => 300000,
            ],
        ]);
    }

    public function test_past_slots_today_are_marked_as_past_and_cannot_be_booked()
    {
        // Freeze time to today at 12:30 PM (e.g. Sunday afternoon)
        $simulatedNow = Carbon::today()->setTime(12, 30);
        Carbon::setTestNow($simulatedNow);

        try {
            $today = Carbon::today()->format('Y-m-d');

            // 1. Check schedule page: 08:00 - 10:00 must show as 'Selesai' and be disabled
            $response = $this->actingAs($this->user)->get("/schedule?date={$today}");
            $response->assertStatus(200);
            $response->assertSee('Selesai');

            // 2. API inquiry: 08:00 - 10:00 and 10:00 - 12:00 must have status 'past'
            $apiResponse = $this->getJson("/api/schedule?date={$today}");
            $apiResponse->assertStatus(200);
            $slots = $apiResponse->json('slots');
            
            $slot0810 = collect($slots)->firstWhere('time', '08:00 - 10:00');
            $this->assertEquals('past', $slot0810['status']);
            $this->assertTrue($slot0810['is_past']);
            $this->assertFalse($slot0810['is_available']);

            // 3. Slot 14:00 - 16:00 is later in the day, so it should still be available
            $slot1416 = collect($slots)->firstWhere('time', '14:00 - 16:00');
            $this->assertEquals('available', $slot1416['status']);
            $this->assertFalse($slot1416['is_past']);
            $this->assertTrue($slot1416['is_available']);

            // 4. Attempting to book the past slot 08:00 - 10:00 must fail with validation error
            $bookResponse = $this->actingAs($this->user)->post('/bookings', [
                'book_date' => $today,
                'time_slot' => '08:00 - 10:00',
                'payment_type' => 'DP',
                'payment_method' => 'QRIS',
            ]);

            $bookResponse->assertSessionHasErrors('time_slot');
        } finally {
            Carbon::setTestNow(); // Reset test time
        }
    }
}
