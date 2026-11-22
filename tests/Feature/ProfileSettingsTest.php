<?php

namespace Tests\Feature;

use App\Models\FieldSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'username' => 'bambang_striker',
            'email' => 'bambang@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'role_id' => 2,
        ]);
    }

    public function test_settings_page_fetches_logged_in_user_data()
    {
        $response = $this->actingAs($this->user)->get('/settings');

        $response->assertStatus(200);
        $response->assertSee('bambang_striker');
        $response->assertSee('bambang@gmail.com');
        $response->assertSee('Preferensi Pengguna');
    }

    public function test_user_can_update_username_with_correct_password()
    {
        $response = $this->actingAs($this->user)->put('/settings/username', [
            'new_username' => 'bambang_garuda',
            'confirm_password' => 'password123',
        ]);

        $response->assertSessionHas('success_username');
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'username' => 'bambang_garuda',
        ]);
    }

    public function test_username_update_fails_if_password_incorrect()
    {
        $response = $this->actingAs($this->user)->put('/settings/username', [
            'new_username' => 'bambang_garuda',
            'confirm_password' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors(['confirm_password'], errorBag: 'updateUsername');
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'username' => 'bambang_striker',
        ]);
    }

    public function test_user_can_update_password()
    {
        $response = $this->actingAs($this->user)->put('/settings/password', [
            'old_password' => 'password123',
            'new_password' => 'newsecret456',
            'new_password_confirmation' => 'newsecret456',
        ]);

        $response->assertSessionHas('success_password');
        $this->user->refresh();
        $this->assertTrue(Hash::check('newsecret456', $this->user->password));
    }

    public function test_password_update_fails_if_old_password_incorrect()
    {
        $response = $this->actingAs($this->user)->put('/settings/password', [
            'old_password' => 'wrongoldpassword',
            'new_password' => 'newsecret456',
            'new_password_confirmation' => 'newsecret456',
        ]);

        $response->assertSessionHasErrors(['old_password'], errorBag: 'updatePassword');
        $this->user->refresh();
        $this->assertTrue(Hash::check('password123', $this->user->password));
    }

    public function test_user_with_must_change_password_flag_is_redirected_to_settings_until_password_updated()
    {
        $forcedUser = User::create([
            'username' => 'default_member',
            'email' => 'default@socstreet.com',
            'password' => Hash::make('socstreet123'),
            'role' => 'user',
            'role_id' => 2,
            'must_change_password' => true,
        ]);

        // Cannot access booking schedule while must_change_password is true
        $scheduleRes = $this->actingAs($forcedUser)->get('/schedule');
        $scheduleRes->assertRedirect(route('profile.settings'));
        $scheduleRes->assertSessionHas('warning_must_change_password');

        // API booking request also returns 403
        $apiRes = $this->actingAs($forcedUser)->getJson('/api/my-bookings');
        $apiRes->assertStatus(403);
        $apiRes->assertJson(['must_change_password' => true]);

        // Updating password resets must_change_password to false
        $updateRes = $this->actingAs($forcedUser)->put('/settings/password', [
            'old_password' => 'socstreet123',
            'new_password' => 'mynewsecurepass123',
            'new_password_confirmation' => 'mynewsecurepass123',
        ]);
        $updateRes->assertSessionHas('success_password');

        $forcedUser->refresh();
        $this->assertFalse($forcedUser->must_change_password);

        // Now user can access booking schedule normally
        $this->actingAs($forcedUser)->get('/schedule')->assertStatus(200);
    }

    public function test_landing_page_displays_dynamic_base_price_from_field_settings()
    {
        FieldSetting::set('base_price', '325000');

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Rp 325.000');
    }
}
