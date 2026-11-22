<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUsersTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin_boss',
            'email' => 'admin@socstreet.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'role_id' => 1,
            'must_change_password' => false,
        ]);

        $this->customer = User::create([
            'username' => 'striker_dev',
            'email' => 'striker@socstreet.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'role_id' => 2,
            'must_change_password' => false,
        ]);
    }

    public function test_guest_cannot_access_admin_users()
    {
        $response = $this->get('/admin/users');
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_users()
    {
        $response = $this->actingAs($this->customer)->get('/admin/users');
        $response->assertRedirect(route('booking.schedule'));
    }

    public function test_admin_can_view_users_management_page_with_real_data()
    {
        Booking::create([
            'booking_id_string' => 'INV-USER-TEST',
            'user_id' => $this->customer->id,
            'book_date' => Carbon::today()->toDateString(),
            'start_time' => '18:00',
            'end_time' => '20:00',
            'duration' => 2,
            'status' => 'confirmed',
            'total_price' => 300000,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('Kelola Pengguna Sistem');
        $response->assertSee('striker_dev');
        $response->assertDontSee('admin_boss'); // Admin accounts must NOT be displayed
        $response->assertSee('1 Sesi'); // booking count for customer
    }

    public function test_admin_can_filter_users_by_search_and_status()
    {
        User::create([
            'username' => 'second_player',
            'email' => 'player2@socstreet.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'role_id' => 2,
            'must_change_password' => true,
        ]);

        // Filter status=need_change
        $responseNeedChange = $this->actingAs($this->admin)->get('/admin/users?status=need_change');
        $responseNeedChange->assertStatus(200);
        $responseNeedChange->assertSee('second_player');
        $responseNeedChange->assertDontSee('striker_dev');

        // Filter status=active
        $responseActive = $this->actingAs($this->admin)->get('/admin/users?status=active');
        $responseActive->assertStatus(200);
        $responseActive->assertSee('striker_dev');
        $responseActive->assertDontSee('second_player');

        // Search username
        $responseSearch = $this->actingAs($this->admin)->get('/admin/users?search=second');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('second_player');
        $responseSearch->assertDontSee('striker_dev');
    }

    public function test_admin_can_create_new_user_with_default_password_and_must_change_password_flag()
    {
        $response = $this->actingAs($this->admin)->postJson('/admin/users', [
            'username' => 'new_striker',
            'role' => 'user',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'username' => 'new_striker',
                'role' => 'User',
                'must_change_password' => true,
            ],
        ]);

        $newUser = User::where('username', 'new_striker')->first();
        $this->assertNotNull($newUser);
        $this->assertTrue(Hash::check('socstreet123', $newUser->password));
        $this->assertTrue((bool) $newUser->must_change_password);
        $this->assertEquals('user', $newUser->role);
    }

    public function test_admin_cannot_create_user_with_duplicate_username()
    {
        $response = $this->actingAs($this->admin)->postJson('/admin/users', [
            'username' => 'striker_dev', // already taken
            'role' => 'user',
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'status' => 'error',
        ]);
    }

    public function test_admin_can_reset_user_password_to_default()
    {
        $this->assertFalse((bool) $this->customer->must_change_password);

        $response = $this->actingAs($this->admin)->postJson("/admin/users/{$this->customer->id}/reset-password");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'id' => $this->customer->id,
                'username' => 'striker_dev',
                'must_change_password' => true,
            ],
        ]);

        $this->customer->refresh();
        $this->assertTrue(Hash::check('socstreet123', $this->customer->password));
        $this->assertTrue((bool) $this->customer->must_change_password);
    }

    public function test_admin_can_delete_user_account()
    {
        $targetUser = User::create([
            'username' => 'user_to_delete',
            'email' => 'delete@socstreet.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'role_id' => 2,
        ]);

        $response = $this->actingAs($this->admin)->deleteJson("/admin/users/{$targetUser->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $targetUser->id,
        ]);
    }

    public function test_admin_cannot_delete_their_own_account()
    {
        $response = $this->actingAs($this->admin)->deleteJson("/admin/users/{$this->admin->id}");

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'status' => 'error',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
        ]);
    }

    public function test_api_admin_users_returns_json_with_stats_and_data()
    {
        $response = $this->actingAs($this->admin)->getJson('/api/admin/users');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'stats' => [
                'active_members',
                'admins',
                'total_sessions',
                'action_needed',
            ],
            'data' => [
                '*' => [
                    'id',
                    'user_id_string',
                    'username',
                    'email',
                    'role',
                    'role_raw',
                    'total_bookings',
                    'must_change_password',
                    'created_at',
                ],
            ],
            'count',
        ]);
    }
}
