<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Socstreet Minisoccer Booking
|--------------------------------------------------------------------------
| Public/Guest routes redirect authenticated users away to their dashboard.
| User and Admin dashboards are protected strictly by authentication middleware.
*/

// Guest & Landing Routes (Redirects authenticated users away from home, login, register)
Route::middleware('redirect.auth')->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
});

// Authentication Action Endpoints
Route::post('/login', [AuthController::class, 'handleLogin'])->name('login.post');
Route::post('/register', [AuthController::class, 'handleRegister'])->name('register.post');

// Protected Customer/User Flow (Must be logged in to book & view bookings)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User Settings, Force Change Password & Policy Accordion
    Route::get('/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::get('/change-password', [ProfileController::class, 'settings'])->name('profile.change-password');
    Route::put('/settings/username', [ProfileController::class, 'updateUsername'])->name('profile.update-username');
    Route::put('/settings/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Booking Flow (Protected by CheckMustChangePassword middleware per PRD 8.2)
    Route::middleware('password.changed')->group(function () {
        Route::get('/schedule', [BookingController::class, 'schedule'])->name('booking.schedule');
        Route::get('/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
        Route::post('/bookings', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('booking.my');
    });
});

// Protected Admin Panel Routes (Must be logged in as role 'admin' / role_id 1)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/export', [AdminController::class, 'exportBookings'])->name('bookings.export');
    Route::post('/bookings/{id}/confirm', [AdminController::class, 'confirmBooking'])->name('bookings.confirm');
    Route::post('/bookings/{id}/reject', [AdminController::class, 'rejectBooking'])->name('bookings.reject');
    Route::get('/schedule', [AdminController::class, 'schedule'])->name('schedule');
    Route::post('/schedule/pricing', [AdminController::class, 'updatePricing'])->name('schedule.pricing');
    Route::post('/schedule/block', [AdminController::class, 'blockSlot'])->name('schedule.block');
    Route::delete('/schedule/block/{id}', [AdminController::class, 'unblockSlot'])->name('schedule.unblock');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'createUser'])->name('users.create');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{id}/reset-password', [AdminController::class, 'resetUserPassword'])->name('users.reset-password');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::match(['put', 'post'], '/settings/profile', [AdminController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [AdminController::class, 'updatePassword'])->name('settings.password');
});
