<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function login(): View
    {
        return view('pages.auth.login');
    }

    /**
     * Show the registration page.
     */
    public function register(): View
    {
        return view('pages.auth.register');
    }

    /**
     * Handle Web Login.
     * Authenticates by email & password, then transparently routes based on role.
     */
    public function handleLogin(Request $request): RedirectResponse|JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau kata sandi tidak cocok.']);
        }

        if (!$user->is_active) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akun Anda dinonaktifkan oleh administrator karena pelanggaran atau kecurangan. Silakan hubungi admin.',
                ], 403);
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun Anda dinonaktifkan oleh administrator karena pelanggaran atau kecurangan. Silakan hubungi admin.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Force change password check (PRD 8.2)
        if ($user->must_change_password) {
            return redirect()
                ->route('profile.settings')
                ->with('warning_must_change_password', 'Demi keamanan akun, Anda wajib mengganti kata sandi default terlebih dahulu sebelum mengakses fitur pemesanan.');
        }

        // Role-based internal routing (Admin -> Dashboard Admin, User -> Dashboard Jadwal)
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Selamat datang kembali, {$user->username}!");
        }

        return redirect()->intended(route('booking.schedule'))
            ->with('success', "Selamat datang kembali, {$user->username}!");
    }

    /**
     * Handle Web Registration for Customers.
     * All registered customers automatically get role 'user' (role_id: 2).
     */
    public function handleRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan masuk.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'role_id' => 2,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('booking.schedule')
            ->with('success', "Selamat datang, {$user->username}!");
    }

    /**
     * Handle Logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * API: Login endpoint.
     */
    public function apiLogin(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau kata sandi tidak cocok.',
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun Anda dinonaktifkan oleh administrator karena pelanggaran atau kecurangan. Silakan hubungi admin.',
            ], 403);
        }

        Auth::login($user, $request->boolean('remember'));

        if ($user->must_change_password) {
            $redirectUrl = route('profile.settings');
        } else {
            $redirectUrl = $user->isAdmin() ? route('admin.dashboard') : route('booking.schedule');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'role_id' => $user->role_id,
                'must_change_password' => (bool) $user->must_change_password,
            ],
            'redirect_url' => $redirectUrl,
        ]);
    }

    /**
     * API: Registration endpoint for Customers.
     */
    public function apiRegister(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'role_id' => 2,
        ]);

        Auth::login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Pendaftaran akun berhasil.',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'role_id' => $user->role_id,
            ],
            'redirect_url' => route('booking.schedule'),
        ], 201);
    }

    /**
     * API: Authenticated User Profile.
     */
    public function apiMe(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'role_id' => $user->role_id,
            ],
        ]);
    }

    /**
     * API: Logout.
     */
    public function apiLogout(Request $request): JsonResponse
    {
        Auth::logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil.',
        ]);
    }
}
