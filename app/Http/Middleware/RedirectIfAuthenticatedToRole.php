<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedToRole
{
    /**
     * Handle an incoming request.
     * Redirects authenticated users away from guest pages (like '/', '/login', '/register')
     * to their respective dashboard depending on their role.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('booking.schedule');
        }

        return $next($request);
    }
}
