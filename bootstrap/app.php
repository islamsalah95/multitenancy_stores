<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'tenant' => \Spatie\Multitenancy\Http\Middleware\NeedsTenant::class,
            'tenant.session' => \Spatie\Multitenancy\Http\Middleware\EnsureValidTenantSession::class,
            'tenant.param' => \App\Http\Middleware\SetTenantFromParameter::class,
            'auth.store' => \App\Http\Middleware\AuthenticateStore::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
