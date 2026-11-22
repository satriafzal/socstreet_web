<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMustChangePassword
{
    /**
     * Redirect users whose `must_change_password` flag is true to the password change page (PRD Section 8.2).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'must_change_password' => true,
                    'message' => 'Demi keamanan akun, Anda wajib mengganti kata sandi bawaan terlebih dahulu.',
                    'redirect_url' => route('profile.settings'),
                ], 403);
            }

            return redirect()
                ->route('profile.settings')
                ->with('warning_must_change_password', 'Demi keamanan akun, Anda wajib mengganti kata sandi default terlebih dahulu sebelum mengakses fitur pemesanan.');
        }

        return $next($request);
    }
}
