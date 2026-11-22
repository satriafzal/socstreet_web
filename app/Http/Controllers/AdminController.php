<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AdminService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ) {}

    /**
     * Display the Admin Dashboard with summary metrics & recent incoming bookings (PRD 7.B.1).
     */
    public function dashboard(): View
    {
        $metrics = $this->adminService->getDashboardMetrics();
        $recentBookings = $this->adminService->getRecentBookings(5);

        return view('admin.dashboard', compact('metrics', 'recentBookings'));
    }

    /**
     * Confirm a booking (status -> confirmed).
     */
    public function confirmBooking(string|int $id, Request $request): JsonResponse|RedirectResponse
    {
        $booking = $this->adminService->confirmBooking($id);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => "Booking {$booking->booking_id_string} berhasil dikonfirmasi dan slot dikunci.",
                'data' => [
                    'booking_id' => $booking->booking_id_string,
                    'status' => $booking->status,
                ],
            ]);
        }

        return back()->with('success', "Booking {$booking->booking_id_string} berhasil dikonfirmasi.");
    }

    /**
     * Reject a booking (status -> cancelled, slot is freed).
     */
    public function rejectBooking(string|int $id, Request $request): JsonResponse|RedirectResponse
    {
        $booking = $this->adminService->rejectBooking($id);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => "Reservasi {$booking->booking_id_string} ditolak dan slot dibebaskan.",
                'data' => [
                    'booking_id' => $booking->booking_id_string,
                    'status' => $booking->status,
                ],
            ]);
        }

        return back()->with('success', "Booking {$booking->booking_id_string} berhasil ditolak.");
    }

    /**
     * API: Get admin dashboard metrics & recent bookings.
     * Endpoint: GET /api/admin/dashboard
     */
    public function apiDashboard(): JsonResponse
    {
        $metrics = $this->adminService->getDashboardMetrics();
        $recentBookings = $this->adminService->getRecentBookings(10);

        return response()->json([
            'status' => 'success',
            'metrics' => $metrics,
            'recent_bookings' => $recentBookings,
        ]);
    }

    /**
     * Display Manage Bookings with comprehensive filterable data table (PRD 7.B.2).
     */
    public function bookings(Request $request): View
    {
        $bookingStats = $this->adminService->getBookingStats();
        $bookings = $this->adminService->getAllBookings($request->all());

        return view('admin.bookings', compact('bookingStats', 'bookings'));
    }

    /**
     * API: Get filterable bookings list and stats (PRD 7.B.2).
     * Endpoint: GET /api/admin/bookings
     */
    public function apiBookings(Request $request): JsonResponse
    {
        $bookingStats = $this->adminService->getBookingStats();
        $bookings = $this->adminService->getAllBookings($request->all());

        return response()->json([
            'status' => 'success',
            'stats' => $bookingStats,
            'data' => $bookings,
            'count' => count($bookings),
        ]);
    }

    /**
     * Export bookings to CSV file (PRD 7.B.2).
     * Endpoint: GET /admin/bookings/export
     */
    public function exportBookings(Request $request): StreamedResponse
    {
        $bookings = $this->adminService->getAllBookings($request->all());
        $fileName = 'socstreet-bookings-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel

            fputcsv($handle, [
                'Booking ID',
                'Username',
                'Tanggal Main',
                'Jam Main',
                'Tipe Bayar',
                'Metode Pembayaran',
                'Total Tagihan',
                'Sisa Bayar',
                'Status',
            ]);

            foreach ($bookings as $b) {
                fputcsv($handle, [
                    $b['booking_id'],
                    $b['username'],
                    $b['date'],
                    $b['time_slot'],
                    $b['payment_type'],
                    $b['payment_method'],
                    $b['total_price'],
                    $b['remaining_payment'],
                    strtoupper($b['status']),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Display Schedule & Pricing management page with 2-hour fixed session controls (PRD 7.B.3).
     */
    public function schedule(): View
    {
        $data = $this->adminService->getScheduleData();
        $pricing = $data['pricing'];
        $blockedSchedules = $data['blocked_schedules'];
        $availableToday = $data['available_today'];

        return view('admin.schedule', compact('pricing', 'blockedSchedules', 'availableToday'));
    }

    /**
     * API: Get Schedule & Pricing data (PRD 7.B.3).
     * Endpoint: GET /api/admin/schedule
     */
    public function apiSchedule(): JsonResponse
    {
        $data = $this->adminService->getScheduleData();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * Update field rental base price (PRD 7.B.3).
     * Endpoint: POST /admin/schedule/pricing
     */
    public function updatePricing(Request $request): JsonResponse|RedirectResponse
    {
        $rawPrice = $request->input('price');
        $cleanedPrice = (int) preg_replace('/[^0-9]/', '', (string) $rawPrice);

        if ($cleanedPrice < 50000 || $cleanedPrice > 5000000) {
            $msg = 'Harga sewa per sesi harus antara Rp 50.000 hingga Rp 5.000.000.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $msg], 422);
            }
            return back()->withErrors(['price' => $msg]);
        }

        $result = $this->adminService->updatePricing($cleanedPrice);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Tarif sewa lapangan berhasil diperbarui.',
                'data' => $result,
            ]);
        }

        return back()->with('success', 'Tarif sewa lapangan berhasil diperbarui.');
    }

    /**
     * Block a 2-hour time slot for maintenance or internal event (PRD 7.B.3).
     * Endpoint: POST /admin/schedule/block
     */
    public function blockSlot(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'time_slot' => 'required|string',
            'reason' => 'required|string|max:255',
        ], [
            'date.required' => 'Tanggal wajib dipilih.',
            'date.date' => 'Format tanggal tidak valid.',
            'time_slot.required' => 'Sesi waktu wajib dipilih.',
            'reason.required' => 'Alasan pemblokiran wajib diisi.',
            'reason.max' => 'Alasan pemblokiran maksimal 255 karakter.',
        ]);

        try {
            $block = $this->adminService->createBlockedSchedule($request->all());

            $formatted = [
                'id' => $block->id,
                'date' => Carbon::parse($block->date)->locale('id')->isoFormat('D MMM YYYY'),
                'date_raw' => $block->date instanceof Carbon ? $block->date->toDateString() : (string) $block->date,
                'time_slot' => $block->start_time . ' - ' . $block->end_time,
                'reason' => $block->reason,
            ];

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => "Slot {$formatted['time_slot']} pada tanggal {$formatted['date']} berhasil diblokir.",
                    'data' => $formatted,
                ], 201);
            }

            return back()->with('success', "Slot berhasil diblokir.");
        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Unblock / remove a blocked schedule slot (PRD 7.B.3).
     * Endpoint: DELETE /admin/schedule/block/{id}
     */
    public function unblockSlot(int $id, Request $request): JsonResponse|RedirectResponse
    {
        $this->adminService->deleteBlockedSchedule($id);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Blokir jadwal berhasil dibuka kembali untuk pemesanan umum.',
                'data' => ['id' => $id],
            ]);
        }

        return back()->with('success', 'Blokir jadwal berhasil dibuka.');
    }

    /**
     * Display User Management page with reset password and delete action controls (PRD 7.B.4).
     */
    public function users(Request $request): View
    {
        $userStats = $this->adminService->getUserStats();
        $users = $this->adminService->getAllUsers($request->all());

        return view('admin.users', compact('userStats', 'users'));
    }

    /**
     * API: Get filterable user list and stats (PRD 7.B.4).
     * Endpoint: GET /api/admin/users
     */
    public function apiUsers(Request $request): JsonResponse
    {
        $userStats = $this->adminService->getUserStats();
        $users = $this->adminService->getAllUsers($request->all());

        return response()->json([
            'status' => 'success',
            'stats' => $userStats,
            'data' => $users,
            'count' => count($users),
        ]);
    }

    /**
     * Create a new user with default password 'socstreet123' (PRD 7.B.4).
     * Endpoint: POST /admin/users
     */
    public function createUser(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50'],
            'role' => ['nullable', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.max' => 'Username maksimal 50 karakter.',
        ]);

        if (strtolower((string) $request->input('role')) === 'admin') {
            $msg = 'Penambahan akun administrator tidak diizinkan. Sistem hanya memiliki 1 administrator.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $msg], 422);
            }
            return back()->withErrors(['role' => $msg]);
        }

        try {
            $user = $this->adminService->createUser($request->all());

            $formatted = [
                'id' => $user->id,
                'user_id_string' => 'USR-' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
                'username' => $user->username,
                'email' => $user->email,
                'role' => strtolower($user->role) === 'admin' ? 'Admin' : 'User',
                'role_raw' => strtolower($user->role),
                'total_bookings' => 0,
                'must_change_password' => true,
                'created_at' => Carbon::parse($user->created_at)->locale('id')->isoFormat('D MMM YYYY'),
            ];

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => "Pengguna {$user->username} berhasil dibuat dengan password default (socstreet123).",
                    'data' => $formatted,
                ], 201);
            }

            return back()->with('success', "Pengguna {$user->username} berhasil dibuat.");
        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->withErrors(['username' => $e->getMessage()]);
        }
    }

    /**
     * Update customer user details (PRD 7.B.4).
     * Endpoint: PUT /admin/users/{id}
     */
    public function updateUser(int $id, Request $request): JsonResponse|RedirectResponse
    {
        $targetUser = User::findOrFail($id);

        $rules = [
            'is_active' => ['nullable'],
        ];

        // Only validate username if it is present and actually different from existing username
        $hasNewUsername = $request->filled('username') && trim((string)$request->input('username')) !== $targetUser->username;

        if ($hasNewUsername) {
            $rules['username'] = [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('users', 'username')->ignore($id),
            ];
        }

        $request->validate($rules, [
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.max' => 'Username maksimal 50 karakter.',
            'username.unique' => 'Username ini sudah digunakan akun lain.',
        ]);

        try {
            $data = $request->all();
            if (!$hasNewUsername) {
                unset($data['username']);
            }

            $user = $this->adminService->updateUser($id, $data);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => "Data pengguna {$user->username} berhasil diperbarui.",
                    'data' => [
                        'id' => $user->id,
                        'user_id_string' => 'USR-' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
                        'username' => $user->username,
                        'is_active' => (bool) $user->is_active,
                    ],
                ]);
            }

            return back()->with('success', "Data pengguna {$user->username} berhasil diperbarui.");
        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->withErrors(['username' => $e->getMessage()]);
        }
    }

    /**
     * Reset user password to default 'socstreet123' and set must_change_password flag (PRD 7.B.4).
     * Endpoint: POST /admin/users/{id}/reset-password
     */
    public function resetUserPassword(int $id, Request $request): JsonResponse|RedirectResponse
    {
        $user = $this->adminService->resetUserPassword($id);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => "Kata sandi user {$user->username} telah direset ke default (socstreet123).",
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'must_change_password' => true,
                ],
            ]);
        }

        return back()->with('success', "Kata sandi user {$user->username} telah direset.");
    }

    /**
     * Delete user account (PRD 7.B.4).
     * Endpoint: DELETE /admin/users/{id}
     */
    public function deleteUser(int $id, Request $request): JsonResponse|RedirectResponse
    {
        try {
            $currentAdminId = (int) $request->user()->id;
            $this->adminService->deleteUser($id, $currentAdminId);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Akun pengguna berhasil dihapus.',
                    'data' => ['id' => $id],
                ]);
            }

            return back()->with('success', 'Akun pengguna berhasil dihapus.');
        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display Admin Profile & Security Settings page (PRD 7.B.5).
     */
    public function settings(Request $request): View
    {
        $admin = $this->adminService->getAdminProfile($request->user()->id);

        return view('admin.settings', compact('admin'));
    }

    /**
     * API: Get Admin Profile & Settings (PRD 7.B.5).
     * Endpoint: GET /api/admin/settings
     */
    public function apiSettings(Request $request): JsonResponse
    {
        $admin = $this->adminService->getAdminProfile($request->user()->id);

        return response()->json([
            'status' => 'success',
            'data' => $admin,
        ]);
    }

    /**
     * Update Admin Profile (Username and Avatar, PRD 7.B.5).
     * Endpoint: PUT/POST /admin/settings/profile, PUT/POST /api/admin/settings/profile
     */
    public function updateProfile(Request $request): JsonResponse|RedirectResponse
    {
        $adminId = $request->user()->id;

        $request->validate([
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users', 'username')->ignore($adminId),
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.max' => 'Username maksimal 50 karakter.',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, dan underscore (_).',
            'username.unique' => 'Username ini sudah digunakan akun lain.',
            'avatar.image' => 'File avatar harus berupa gambar.',
            'avatar.mimes' => 'Format avatar harus JPEG, PNG, JPG, atau WEBP.',
            'avatar.max' => 'Ukuran avatar maksimal 2MB.',
        ]);

        try {
            $avatarFile = $request->file('avatar');
            $updatedAdmin = $this->adminService->updateAdminProfile($adminId, $request->only('username'), $avatarFile);
            $profile = $this->adminService->getAdminProfile($updatedAdmin->id);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Profil administrator berhasil diperbarui.',
                    'data' => $profile,
                ]);
            }

            return back()->with('success', 'Profil administrator berhasil diperbarui.');
        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['username' => $e->getMessage()]);
        }
    }

    /**
     * Update Admin Password (PRD 7.B.5).
     * Endpoint: PUT /admin/settings/password, PUT /api/admin/settings/password
     */
    public function updatePassword(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal harus 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        try {
            $this->adminService->updateAdminPassword(
                $request->user()->id,
                $request->input('current_password'),
                $request->input('new_password')
            );

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Password administrator berhasil diperbarui.',
                ]);
            }

            return back()->with('success', 'Password administrator berhasil diperbarui.');
        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['current_password' => $e->getMessage()]);
        }
    }
}
