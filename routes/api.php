<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Socstreet Minisoccer
|--------------------------------------------------------------------------
| Handles registration, login (with role check: admin vs user), and user info.
*/

Route::post('/register', [AuthController::class, 'apiRegister'])->name('api.register');
Route::post('/login', [AuthController::class, 'apiLogin'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'apiMe'])->name('api.me');
    Route::post('/logout', [AuthController::class, 'apiLogout'])->name('api.logout');
    Route::get('/user/profile', [ProfileController::class, 'apiProfile'])->name('api.user.profile');
    Route::put('/user/username', [ProfileController::class, 'updateUsername'])->name('api.user.update-username');
    Route::put('/user/password', [ProfileController::class, 'updatePassword'])->name('api.user.update-password');
});

// Fallback session-based API auth if sanctum is not strictly used
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
