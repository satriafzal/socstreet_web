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

    // Booking Flow
    Route::get('/schedule', [BookingController::class, 'schedule'])->name('booking.schedule');
    Route::get('/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('booking.my');

    // User Settings & Policy Accordion
    Route::get('/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/settings/username', [ProfileController::class, 'updateUsername'])->name('profile.update-username');
    Route::put('/settings/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});

// Protected Admin Panel Routes (Must be logged in as role 'admin' / role_id 1)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/schedule', [AdminController::class, 'schedule'])->name('schedule');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});
