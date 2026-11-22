<?php

namespace Tests\Feature;

use App\Models\BlockedSchedule;
use App\Models\Booking;
use App\Models\FieldSetting;
use App\Models\User;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin_arena',
            'email' => 'admin@socstreet.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'role_id' => 1,
            'must_change_password' => false,
        ]);

        $this->customer = User::create([
            'username' => 'player_one',
            'email' => 'player@socstreet.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'role_id' => 2,
            'must_change_password' => false,
        ]);
    }

    public function test_guest_cannot_access_admin_schedule()
    {
        $response = $this->get('/admin/schedule');
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_schedule()
    {
        $response = $this->actingAs($this->customer)->get('/admin/schedule');
        $response->assertRedirect(route('booking.schedule'));
    }

    public function test_admin_can_view_schedule_page_with_real_data()
    {
        FieldSetting::set('base_price', 350000);

        $futureDate = Carbon::today()->addDays(2)->toDateString();
        BlockedSchedule::create([
            'date' => $futureDate,
            'start_time' => '14:00',
            'end_time' => '16:00',
            'reason' => 'Perawatan Rumput Khusus',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/schedule');

        $response->assertStatus(200);
        $response->assertSee('Tarif & Ketersediaan Sesi', false);
        $response->assertSee('350.000');
        $response->assertSee('Perawatan Rumput Khusus');
        $response->assertSee('14:00 - 16:00');
    }

    public function test_admin_can_update_field_rental_price_via_api_and_web()
    {
        $response = $this->actingAs($this->admin)->postJson('/admin/schedule/pricing', [
            'price' => '400.000',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'base_price' => 400000,
                'base_price_formatted' => '400.000',
            ],
        ]);

        $this->assertEquals('400000', FieldSetting::get('base_price'));

        // Customer schedule should immediately reflect new price
        $bookingService = app(BookingService::class);
        $slots = $bookingService->getSlotsForDate(Carbon::tomorrow()->toDateString());
        $slotMorning = collect($slots)->firstWhere('time', '08:00 - 10:00');
        $this->assertEquals(400000, $slotMorning['price']);
    }

    public function test_admin_cannot_update_price_with_invalid_range()
    {
        $response = $this->actingAs($this->admin)->postJson('/admin/schedule/pricing', [
            'price' => '10000', // below 50.000 minimum
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
        ]);
    }

    public function test_admin_can_block_a_two_hour_schedule_slot()
    {
        $futureDate = Carbon::today()->addDays(3)->toDateString();

        $response = $this->actingAs($this->admin)->postJson('/admin/schedule/block', [
            'date' => $futureDate,
            'time_slot' => '18:00 - 20:00 (Prime)',
            'reason' => 'Turnamen Mini Soccer Cup',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'time_slot' => '18:00 - 20:00',
                'reason' => 'Turnamen Mini Soccer Cup',
            ],
        ]);

        $this->assertDatabaseHas('blocked_schedules', [
            'start_time' => '18:00',
            'end_time' => '20:00',
            'reason' => 'Turnamen Mini Soccer Cup',
        ]);

        // Customer schedule for that date must now show slot as blocked!
        $bookingService = app(BookingService::class);
        $slots = $bookingService->getSlotsForDate($futureDate);
        $slot18 = collect($slots)->firstWhere('time', '18:00 - 20:00');
        $this->assertEquals('blocked', $slot18['status']);
        $this->assertEquals('Turnamen Mini Soccer Cup', $slot18['block_reason']);
    }

    public function test_admin_cannot_block_same_slot_twice()
    {
        $futureDate = Carbon::today()->addDays(3)->toDateString();

        BlockedSchedule::create([
            'date' => $futureDate,
            'start_time' => '18:00',
            'end_time' => '20:00',
            'reason' => 'Perawatan Rumput',
        ]);

        $response = $this->actingAs($this->admin)->postJson('/admin/schedule/block', [
            'date' => $futureDate,
            'time_slot' => '18:00 - 20:00',
            'reason' => 'Acara Lain',
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'status' => 'error',
        ]);
    }

    public function test_admin_cannot_block_slot_with_active_booking()
    {
        $futureDate = Carbon::today()->addDays(3)->toDateString();

        Booking::create([
            'booking_id_string' => 'INV-ACTIVE-BOOK',
            'user_id' => $this->customer->id,
            'book_date' => $futureDate,
            'start_time' => '16:00',
            'end_time' => '18:00',
            'duration' => 2,
            'status' => 'confirmed',
            'total_price' => 300000,
        ]);

        $response = $this->actingAs($this->admin)->postJson('/admin/schedule/block', [
            'date' => $futureDate,
            'time_slot' => '16:00 - 18:00',
            'reason' => 'Maintenance Darurat',
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'status' => 'error',
        ]);
        $this->assertStringContainsString('INV-ACTIVE-BOOK', $response->json('message'));
    }

    public function test_admin_can_unblock_slot_and_it_becomes_available()
    {
        $futureDate = Carbon::today()->addDays(4)->toDateString();

        $block = BlockedSchedule::create([
            'date' => $futureDate,
            'start_time' => '20:00',
            'end_time' => '22:00',
            'reason' => 'Renovasi Lampu',
        ]);

        // Slot is initially blocked
        $bookingService = app(BookingService::class);
        $slotsBefore = $bookingService->getSlotsForDate($futureDate);
        $this->assertEquals('blocked', collect($slotsBefore)->firstWhere('time', '20:00 - 22:00')['status']);

        // Admin unblocks
        $response = $this->actingAs($this->admin)->deleteJson("/admin/schedule/block/{$block->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);

        $this->assertDatabaseMissing('blocked_schedules', [
            'id' => $block->id,
        ]);

        // Slot becomes available again for customer booking!
        $slotsAfter = $bookingService->getSlotsForDate($futureDate);
        $this->assertEquals('available', collect($slotsAfter)->firstWhere('time', '20:00 - 22:00')['status']);
    }

    public function test_api_admin_schedule_returns_json_with_pricing_and_blocks()
    {
        $response = $this->actingAs($this->admin)->getJson('/api/admin/schedule');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'pricing' => [
                    'price',
                    'price_formatted',
                    'operating_hours',
                    'duration',
                    'total_slots',
                ],
                'blocked_schedules',
                'available_today',
                'total_today_slots',
            ],
        ]);
    }
}
