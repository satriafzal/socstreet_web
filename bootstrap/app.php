<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\RedirectIfAuthenticatedToRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectTo(
            guests: '/login',
            users: function (Request $request) {
                $user = $request->user();
                if ($user && $user->isAdmin()) {
                    return route('admin.dashboard');
                }
                return route('booking.schedule');
            }
        );

        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'redirect.auth' => RedirectIfAuthenticatedToRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
