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
            // Middleware pour vérifier qu'aucun utilisateur n'est connecté (multi-guard)
            'multi-guest' => \App\Http\Middleware\MultiGuardGuest::class,
        ]);

        // MIDDLEWARE GLOBAL UNIQUEMENT pour le timeout de session
        // NE PAS appliquer RoleBasedRedirect ici car il est déjà géré dans les routes
        $middleware->web(prepend: [
            \App\Http\Middleware\SessionTimeout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
