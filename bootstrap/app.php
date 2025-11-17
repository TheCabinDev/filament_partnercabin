<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckFrontendSecret;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // --- Register the custom middleware alias here ---
        $middleware->alias([
            'frontend.secret' => CheckFrontendSecret::class,
        ]);
        // -------------------------------------------------

        // You can also add it to the 'api' group if ALL API routes need it
        $middleware->api(prepend: [
            // CheckFrontendSecret::class, // <-- Uncomment to apply globally to API routes
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
