<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     * Ensures only authenticated Admins (role: admin, role_id: 1) can access admin panel routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $user = Auth::user();

        if (!$user->isAdmin()) {
            // Non-admin trying to access admin panel
            return redirect()->route('booking.schedule')->with('error', 'Akses ditolak. Anda tidak memiliki izin Administrator.');
        }

        return $next($request);
    }
}
