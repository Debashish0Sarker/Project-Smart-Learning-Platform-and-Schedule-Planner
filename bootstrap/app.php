<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(DIR))
    ->withRouting(
        web: DIR.'/../routes/web.php',
        commands: DIR.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust the Railway proxy headers
        $middleware->trustProxies(at: '*');

        // Add CSRF middleware with proper handling
        $middleware->validateCsrfTokens(except: [
            // Add any routes that should skip CSRF validation here if needed
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
