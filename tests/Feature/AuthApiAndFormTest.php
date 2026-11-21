<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiAndFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default admin
        User::create([
            'username' => 'admin',
            'email' => 'adminsocer@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'role_id' => 1,
        ]);
    }

    public function test_login_page_renders_single_unified_form_with_email_and_password()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Alamat Email');
        $response->assertSee('Kata Sandi');
        // Must NOT expose admin tabs or demo pills
        $response->assertDontSee('role-tab-admin');
        $response->assertDontSee('User Demo');
    }

    public function test_register_page_renders_with_email_input()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Alamat Email Aktif');
        $response->assertSee('email');
    }

    public function test_admin_logs_in_and_is_routed_to_admin_dashboard()
    {
        $response = $this->post('/login', [
            'email' => 'adminsocer@gmail.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('admin', auth()->user()->role);
        $this->assertEquals(1, auth()->user()->role_id);
    }

    public function test_user_logs_in_and_is_routed_to_booking_schedule()
    {
        $user = User::create([
            'username' => 'testcustomer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('mypassword123'),
            'role' => 'user',
            'role_id' => 2,
        ]);

        $response = $this->post('/login', [
            'email' => 'customer@gmail.com',
            'password' => 'mypassword123',
        ]);

        $response->assertRedirect(route('booking.schedule'));
        $this->assertAuthenticated();
        $this->assertEquals('user', auth()->user()->role);
        $this->assertEquals(2, auth()->user()->role_id);
    }

    public function test_web_login_fails_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'adminsocer@gmail.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_api_login_success_for_admin_and_returns_admin_dashboard_url()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'adminsocer@gmail.com',
            'password' => 'admin123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'user' => [
                'email' => 'adminsocer@gmail.com',
                'role' => 'admin',
                'role_id' => 1,
            ],
            'redirect_url' => route('admin.dashboard'),
        ]);
    }

    public function test_api_login_success_for_user_and_returns_schedule_url()
    {
        User::create([
            'username' => 'customer_two',
            'email' => 'customer2@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'role_id' => 2,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'customer2@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'user' => [
                'email' => 'customer2@gmail.com',
                'role' => 'user',
                'role_id' => 2,
            ],
            'redirect_url' => route('booking.schedule'),
        ]);
    }

    public function test_api_register_creates_new_user_with_role_user()
    {
        $response = $this->postJson('/api/register', [
            'username' => 'newplayer',
            'email' => 'newplayer@gmail.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'user' => [
                'username' => 'newplayer',
                'email' => 'newplayer@gmail.com',
                'role' => 'user',
                'role_id' => 2,
            ],
            'redirect_url' => route('booking.schedule'),
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newplayer@gmail.com',
            'role_id' => 2,
            'role' => 'user',
        ]);
    }
}
