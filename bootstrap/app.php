<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: \App\Http\Middleware\TrustProxies::class);
        $middleware->validateCsrfTokens(except: [
            '/payment/cryptomus/callback',
        ]);
        
        // Register middleware alias
        $middleware->alias([
            'check.deactivated' => \App\Http\Middleware\CheckDeactivatedAccount::class,
            'check.maintenance' => \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
        
        // Add maintenance check first (prepend), then deactivated check
        $middleware->web(prepend: [
            \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
        
        $middleware->web(append: [
            \App\Http\Middleware\CheckDeactivatedAccount::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
