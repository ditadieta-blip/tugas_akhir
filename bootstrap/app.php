<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Alias untuk middleware role kamu
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        // 2. Kecualikan route callback dari proteksi CSRF
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback', // Ini yang paling penting, sesuai dengan route kamu
            'https://*.ngrok-free.app/*', // Supaya domain ngrok tidak dicegat
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
