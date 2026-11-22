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

        $otherAdmin = User::create([
            'username' => 'other_admin_acc',
            'email' => 'otheradmin@socstreet.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'role_id' => 1,
            'must_change_password' => false,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('Kelola Pengguna Sistem');
        $response->assertSee('striker_dev');
        $response->assertDontSee('other_admin_acc'); // Admin accounts must NOT be displayed in user list
        $this->assertFalse(collect($response->viewData('users'))->contains('username', 'admin_boss'));
        $response->assertSee('1 Sesi'); // booking count for customer
        $response->assertSee('Status User');
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

        $this->assertSoftDeleted('users', [
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

    public function test_admin_cannot_create_another_admin_user()
    {
        $response = $this->actingAs($this->admin)->postJson('/admin/users', [
            'username' => 'second_admin_attempt',
            'role' => 'admin',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Penambahan akun administrator tidak diizinkan. Sistem hanya memiliki 1 administrator.',
        ]);

        $this->assertDatabaseMissing('users', [
            'username' => 'second_admin_attempt',
        ]);
    }

    public function test_admin_can_update_user_username()
    {
        $response = $this->actingAs($this->admin)->putJson("/admin/users/{$this->customer->id}", [
            'username' => 'striker_renamed',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'id' => $this->customer->id,
                'username' => 'striker_renamed',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->customer->id,
            'username' => 'striker_renamed',
        ]);
    }

    public function test_admin_can_update_user_status_without_changing_username()
    {
        // 1. Update only is_active without passing username
        $response1 = $this->actingAs($this->admin)->putJson("/admin/users/{$this->customer->id}", [
            'is_active' => false,
        ]);

        $response1->assertStatus(200);
        $this->customer->refresh();
        $this->assertFalse((bool) $this->customer->is_active);
        $this->assertEquals('striker_dev', $this->customer->username);

        // 2. Update with the exact same username kept unchanged
        $response2 = $this->actingAs($this->admin)->putJson("/admin/users/{$this->customer->id}", [
            'username' => 'striker_dev',
            'is_active' => true,
        ]);

        $response2->assertStatus(200);
        $this->customer->refresh();
        $this->assertTrue((bool) $this->customer->is_active);
        $this->assertEquals('striker_dev', $this->customer->username);
    }

    public function test_admin_can_deactivate_user_with_space_in_existing_username()
    {
        $spacedUser = User::create([
            'username' => 'satria afzal',
            'email' => 'satria@socstreet.test',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'role_id' => 2,
            'must_change_password' => false,
            'is_active' => true,
        ]);

        // Simulates submitting form where input still holds the existing username 'satria afzal'
        $response = $this->actingAs($this->admin)->putJson("/admin/users/{$spacedUser->id}", [
            'username' => 'satria afzal',
            'is_active' => false,
        ]);

        $response->assertStatus(200);
        $spacedUser->refresh();
        $this->assertFalse((bool) $spacedUser->is_active);
        $this->assertEquals('satria afzal', $spacedUser->username);
    }

    public function test_admin_can_deactivate_and_reactivate_user()
    {
        // Deactivate
        $response = $this->actingAs($this->admin)->putJson("/admin/users/{$this->customer->id}", [
            'username' => 'striker_dev',
            'is_active' => false,
        ]);

        $response->assertStatus(200);
        $this->customer->refresh();
        $this->assertFalse((bool) $this->customer->is_active);

        // Logout admin before testing login
        auth()->logout();

        // Deactivated user cannot login
        $loginRes = $this->post('/login', [
            'email' => $this->customer->email,
            'password' => 'password123',
        ]);
        $loginRes->assertSessionHasErrors(['email']);
        $this->assertGuest();

        // API login also rejected with 403
        $apiLoginRes = $this->postJson('/api/login', [
            'email' => $this->customer->email,
            'password' => 'password123',
        ]);
        $apiLoginRes->assertStatus(403);
        $apiLoginRes->assertJsonFragment(['status' => 'error']);

        // Reactivate user
        $reactivateRes = $this->actingAs($this->admin)->putJson("/admin/users/{$this->customer->id}", [
            'username' => 'striker_dev',
            'is_active' => true,
        ]);
        $reactivateRes->assertStatus(200);
        $this->customer->refresh();
        $this->assertTrue((bool) $this->customer->is_active);
    }

    public function test_admin_cannot_update_user_with_duplicate_username()
    {
        $anotherUser = User::create([
            'username' => 'existing_player',
            'email' => 'existing@socstreet.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'role_id' => 2,
            'must_change_password' => false,
        ]);

        $response = $this->actingAs($this->admin)->putJson("/admin/users/{$this->customer->id}", [
            'username' => 'existing_player',
        ]);

        $response->assertStatus(422);
    }

    public function test_admin_cannot_update_admin_user_via_users_endpoint()
    {
        $response = $this->actingAs($this->admin)->putJson("/admin/users/{$this->admin->id}", [
            'username' => 'admin_renamed_illegal',
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'status' => 'error',
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
