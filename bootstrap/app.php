<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Listeners\LogUserLogin;
use App\Listeners\LogUserLogout;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Twilio webhook CSRF exclusion
        $middleware->validateCsrfTokens(except: [
            'conversations/receive',
        ]);

        // Middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // Global web middleware append
        $middleware->web(append: [
            // \App\Http\Middleware\CheckCountry::class,  // ← Temporarily disabled
            \App\Http\Middleware\TrackUserActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage() ?: 'Non hai i permessi necessari.'
                ], 403);
            }

            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => $e->getMessage() ?: 'Non hai i permessi necessari per accedere a questa risorsa.'
            ]);
        });
    })->create();

// ইভেন্ট লিসেনার নিবন্ধন
Event::listen(Login::class, LogUserLogin::class);
Event::listen(Logout::class, LogUserLogout::class);