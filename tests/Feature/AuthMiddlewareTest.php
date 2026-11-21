<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin',
            'email' => 'adminsocer@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'role_id' => 1,
        ]);

        $this->user = User::create([
            'username' => 'memberone',
            'email' => 'member@gmail.com',
            'password' => Hash::make('member123'),
            'role' => 'user',
            'role_id' => 2,
        ]);
    }

    public function test_guest_can_access_home_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Sensasi Bermain Minisoccer');
    }

    public function test_authenticated_user_cannot_access_home_and_is_redirected_to_schedule()
    {
        $response = $this->actingAs($this->user)->get('/');
        $response->assertRedirect(route('booking.schedule'));
    }

    public function test_authenticated_admin_cannot_access_home_and_is_redirected_to_admin_dashboard()
    {
        $response = $this->actingAs($this->admin)->get('/');
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_authenticated_user_cannot_access_login_or_register_page()
    {
        $responseLogin = $this->actingAs($this->user)->get('/login');
        $responseLogin->assertRedirect(route('booking.schedule'));

        $responseRegister = $this->actingAs($this->user)->get('/register');
        $responseRegister->assertRedirect(route('booking.schedule'));
    }

    public function test_guest_cannot_access_user_dashboard_pages()
    {
        $this->get('/schedule')->assertRedirect(route('login'));
        $this->get('/checkout')->assertRedirect(route('login'));
        $this->get('/my-bookings')->assertRedirect(route('login'));
        $this->get('/settings')->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_admin_panel()
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
        $this->get('/admin/bookings')->assertRedirect(route('login'));
        $this->get('/admin/schedule')->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_panel_and_is_redirected_to_schedule()
    {
        $response = $this->actingAs($this->user)->get('/admin/dashboard');
        $response->assertRedirect(route('booking.schedule'));
        $response->assertSessionHas('error');
    }

    public function test_admin_can_access_admin_panel()
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_navbar_and_homepage_buttons_point_to_login_for_guests()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Navbar Booking Saya link goes to /login for guest
        $response->assertSee('href="' . route('login') . '"', false);
    }
}
