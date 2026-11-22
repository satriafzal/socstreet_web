<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
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
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
            'role_id' => 1,
            'must_change_password' => false,
        ]);

        $this->customer = User::create([
            'username' => 'regular_member',
            'email' => 'regular@socstreet.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'role_id' => 2,
            'must_change_password' => false,
        ]);
    }

    public function test_guest_cannot_access_admin_settings()
    {
        $response = $this->get('/admin/settings');
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_settings()
    {
        $response = $this->actingAs($this->customer)->get('/admin/settings');
        $response->assertRedirect(route('booking.schedule'));
    }

    public function test_admin_can_view_settings_page_with_real_profile()
    {
        $response = $this->actingAs($this->admin)->get('/admin/settings');

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Sistem & Profil', false);
        $response->assertSee('admin_super');
        $response->assertSee('Primary Root Authority');
        $response->assertSee('Ubah Password');
    }

    public function test_admin_can_update_username_successfully()
    {
        $response = $this->actingAs($this->admin)->put('/admin/settings/profile', [
            'username' => 'new_admin_boss',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'username' => 'new_admin_boss',
        ]);

        $response->assertSessionHas('success');
    }

    public function test_admin_cannot_update_to_duplicate_username()
    {
        $response = $this->actingAs($this->admin)->put('/admin/settings/profile', [
            'username' => 'regular_member',
        ]);

        $response->assertSessionHasErrors(['username']);
        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'username' => 'admin_super',
        ]);
    }

    public function test_admin_cannot_update_username_with_invalid_characters()
    {
        $response = $this->actingAs($this->admin)->put('/admin/settings/profile', [
            'username' => 'invalid user name!',
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_admin_can_upload_avatar_successfully()
    {
        Storage::fake('public');

        $fakeAvatar = UploadedFile::fake()->image('custom_avatar.png', 200, 200);

        $response = $this->actingAs($this->admin)->post('/admin/settings/profile', [
            '_method' => 'PUT',
            'username' => 'admin_super',
            'avatar' => $fakeAvatar,
        ]);

        $this->admin->refresh();
        $this->assertNotNull($this->admin->avatar);
        Storage::disk('public')->assertExists($this->admin->avatar);

        $response->assertSessionHas('success');
    }

    public function test_admin_can_update_password_with_valid_current_password()
    {
        $response = $this->actingAs($this->admin)->put('/admin/settings/password', [
            'current_password' => 'admin12345',
            'new_password' => 'newSecurePassword999',
            'new_password_confirmation' => 'newSecurePassword999',
        ]);

        $this->admin->refresh();
        $this->assertTrue(Hash::check('newSecurePassword999', $this->admin->password));
        $response->assertSessionHas('success');
    }

    public function test_admin_cannot_update_password_with_wrong_current_password()
    {
        $response = $this->actingAs($this->admin)->put('/admin/settings/password', [
            'current_password' => 'wrongAdminPassword',
            'new_password' => 'newSecurePassword999',
            'new_password_confirmation' => 'newSecurePassword999',
        ]);

        $this->admin->refresh();
        $this->assertFalse(Hash::check('newSecurePassword999', $this->admin->password));
        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_admin_cannot_update_password_if_confirmation_mismatches_or_too_short()
    {
        // Short password
        $response1 = $this->actingAs($this->admin)->put('/admin/settings/password', [
            'current_password' => 'admin12345',
            'new_password' => 'short',
            'new_password_confirmation' => 'short',
        ]);
        $response1->assertSessionHasErrors(['new_password']);

        // Mismatched confirmation
        $response2 = $this->actingAs($this->admin)->put('/admin/settings/password', [
            'current_password' => 'admin12345',
            'new_password' => 'newSecurePassword999',
            'new_password_confirmation' => 'differentPassword999',
        ]);
        $response2->assertSessionHasErrors(['new_password']);
    }

    public function test_api_admin_settings_returns_json_profile()
    {
        $response = $this->actingAs($this->admin)->getJson('/api/admin/settings');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'id' => $this->admin->id,
                'username' => 'admin_super',
                'role' => 'Super Administrator',
            ],
        ]);
    }

    public function test_api_admin_update_profile_and_password()
    {
        // Update profile JSON
        $profileRes = $this->actingAs($this->admin)->putJson('/api/admin/settings/profile', [
            'username' => 'api_admin_boss',
        ]);

        $profileRes->assertStatus(200);
        $profileRes->assertJson([
            'status' => 'success',
            'data' => [
                'username' => 'api_admin_boss',
            ],
        ]);

        // Update password JSON
        $passRes = $this->actingAs($this->admin)->putJson('/api/admin/settings/password', [
            'current_password' => 'admin12345',
            'new_password' => 'newApiPassword123',
            'new_password_confirmation' => 'newApiPassword123',
        ]);

        $passRes->assertStatus(200);
        $passRes->assertJson([
            'status' => 'success',
            'message' => 'Password administrator berhasil diperbarui.',
        ]);

        $this->admin->refresh();
        $this->assertTrue(Hash::check('newApiPassword123', $this->admin->password));
    }
}
