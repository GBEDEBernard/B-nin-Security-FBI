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
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias pour les middlewares
        $middleware->alias([
            'tenant' => \App\Http\Middleware\TenantMiddleware::class,
            'role.redirect' => \App\Http\Middleware\RoleBasedRedirect::class,
            'session.timeout' => \App\Http\Middleware\SessionTimeout::class,
            // Middleware de rôle
            'superadmin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'entreprise' => \App\Http\Middleware\EntrepriseMiddleware::class,
            'client' => \App\Http\Middleware\ClientMiddleware::class,
        ]);

        // Middleware global pour la redirection par rôle et le timeout de session
        $middleware->web(prepend: [
            \App\Http\Middleware\RoleBasedRedirect::class,
            \App\Http\Middleware\SessionTimeout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
