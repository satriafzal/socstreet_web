<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Socstreet Minisoccer Booking
|--------------------------------------------------------------------------
| Strictly User/Customer Flow Only (Single Field: Socstreet Main Arena)
| Authentication Constraint: Username & Password only
*/

// Landing Page & Arena Showcase
Route::get('/', [PageController::class, 'home'])->name('home');

// Authentication (Minimal: Username & Password only)
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');

// Booking Flow
Route::get('/schedule', [BookingController::class, 'schedule'])->name('booking.schedule');
Route::get('/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('booking.my');

// User Settings & Policy Accordion
Route::get('/settings', [ProfileController::class, 'settings'])->name('profile.settings');
