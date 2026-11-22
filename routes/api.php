<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Socstreet Minisoccer
|--------------------------------------------------------------------------
| Handles registration, login (with role check: admin vs user), schedule inquiry,
| and customer booking management.
*/

// Public Endpoints
Route::post('/register', [AuthController::class, 'apiRegister'])->name('api.register');
Route::post('/login', [AuthController::class, 'apiLogin'])->name('api.login');
Route::get('/schedule', [BookingController::class, 'apiSchedule'])->name('api.schedule');

// Protected Customer & User Flow
Route::middleware('auth')->group(function () {
    Route::get('/me', [AuthController::class, 'apiMe'])->name('api.me');
    Route::post('/logout', [AuthController::class, 'apiLogout'])->name('api.logout');
    
    // Profile Management
    Route::get('/user/profile', [ProfileController::class, 'apiProfile'])->name('api.user.profile');
    Route::put('/user/username', [ProfileController::class, 'updateUsername'])->name('api.user.update-username');
    Route::put('/user/password', [ProfileController::class, 'updatePassword'])->name('api.user.update-password');

    // Customer Booking Flow (Requires password change if account uses default password)
    Route::middleware('password.changed')->group(function () {
        Route::post('/bookings', [BookingController::class, 'apiStore'])->name('api.bookings.store');
        Route::get('/my-bookings', [BookingController::class, 'apiMyBookings'])->name('api.my-bookings');
        Route::get('/bookings/{id}', [BookingController::class, 'apiShow'])->name('api.bookings.show');
    });
});

// Admin API Endpoints
Route::prefix('admin')->name('api.admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'apiDashboard'])->name('dashboard');
    Route::get('/bookings', [AdminController::class, 'apiBookings'])->name('bookings');
    Route::post('/bookings/{id}/confirm', [AdminController::class, 'confirmBooking'])->name('bookings.confirm');
    Route::post('/bookings/{id}/reject', [AdminController::class, 'rejectBooking'])->name('bookings.reject');
    Route::get('/schedule', [AdminController::class, 'apiSchedule'])->name('schedule');
    Route::post('/schedule/pricing', [AdminController::class, 'updatePricing'])->name('schedule.pricing');
    Route::post('/schedule/block', [AdminController::class, 'blockSlot'])->name('schedule.block');
    Route::delete('/schedule/block/{id}', [AdminController::class, 'unblockSlot'])->name('schedule.unblock');
    Route::get('/users', [AdminController::class, 'apiUsers'])->name('users');
    Route::post('/users', [AdminController::class, 'createUser'])->name('users.create');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{id}/reset-password', [AdminController::class, 'resetUserPassword'])->name('users.reset-password');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/settings', [AdminController::class, 'apiSettings'])->name('settings');
    Route::match(['put', 'post'], '/settings/profile', [AdminController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [AdminController::class, 'updatePassword'])->name('settings.password');
});
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.session.logout');
Route::get('/auth/user', function (Request $request) {
    if ($request->user()) {
        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $request->user()->id,
                'username' => $request->user()->username,
                'email' => $request->user()->email,
                'role' => $request->user()->role,
                'role_id' => $request->user()->role_id,
            ],
        ]);
    }
    return response()->json([
        'status' => 'error',
        'message' => 'Unauthenticated.',
    ], 401);
});
