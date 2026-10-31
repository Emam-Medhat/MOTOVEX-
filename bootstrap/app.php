<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register SetLocale as a global web middleware so every
        // request — authenticated or not — has its locale resolved
        // before any controller or Blade template executes.
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->alias([
            'role'              => \App\Http\Middleware\CheckRole::class,
            'brand.access'      => \App\Http\Middleware\CheckBrandAccess::class,
            'active'            => \App\Http\Middleware\EnsureUserIsActive::class,
            'merchant.approved' => \App\Http\Middleware\EnsureMerchantIsApproved::class,
        ]);

        $middleware->redirectTo(
            guests: '/login',
            users: '/dashboard',
        );
    })
    ->withProviders([
        \App\Providers\AuthServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
