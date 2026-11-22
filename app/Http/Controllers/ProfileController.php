<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the Settings page with real user profile data & bottom legal accordion.
     */
    public function settings(Request $request): View
    {
        $user = $request->user();

        return view('pages.profile.settings', compact('user'));
    }

    /**
     * Update current user's username.
     */
    public function updateUsername(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'new_username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'confirm_password' => ['required', 'string'],
        ], [
            'new_username.required' => 'Username baru wajib diisi.',
            'new_username.min' => 'Username baru minimal 3 karakter.',
            'new_username.unique' => 'Username ini sudah digunakan akun lain.',
            'confirm_password.required' => 'Kata sandi verifikasi wajib diisi.',
        ]);

        // Verify that current password is correct before allowing username change
        if (!Hash::check($validated['confirm_password'], $user->password)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kata sandi verifikasi tidak cocok.',
                ], 422);
            }

            return back()
                ->withInput($request->only('new_username'))
                ->withErrors(['confirm_password' => 'Kata sandi verifikasi tidak cocok.'], 'updateUsername');
        }

        $user->username = $validated['new_username'];
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Username berhasil diperbarui.',
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'role_id' => $user->role_id,
                ],
            ]);
        }

        return back()->with('success_username', 'Username berhasil diperbarui menjadi: ' . $user->username);
    }

    /**
     * Update current user's password.
     */
    public function updatePassword(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'old_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'old_password.required' => 'Kata sandi saat ini wajib diisi.',
            'new_password.required' => 'Kata sandi baru wajib diisi.',
            'new_password.min' => 'Kata sandi baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        // Verify old password
        if (!Hash::check($validated['old_password'], $user->password)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kata sandi saat ini tidak cocok.',
                ], 422);
            }

            return back()
                ->withErrors(['old_password' => 'Kata sandi saat ini tidak cocok.'], 'updatePassword');
        }

        $user->password = Hash::make($validated['new_password']);
        $user->must_change_password = false;
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kata sandi berhasil diperbarui.',
                'must_change_password' => false,
            ]);
        }

        return back()->with('success_password', 'Kata sandi berhasil diperbarui. Silakan gunakan kata sandi baru Anda.');
    }

    /**
     * API: Get user profile details.
     */
    public function apiProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'role_id' => $user->role_id,
                'created_at' => $user->created_at ? $user->created_at->toIso8601String() : null,
                'total_bookings' => $user->bookings()->count(),
            ],
        ]);
    }
}
