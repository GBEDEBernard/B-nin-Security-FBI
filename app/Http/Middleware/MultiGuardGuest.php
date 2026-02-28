<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware MultiGuardGuest
 * 
 * Vérifie qu'aucun utilisateur n'est connecté sur AUCUN des guards utilisés.
 * Utilisé pour les routes d'authentification (login, register) pour éviter
 * qu'un utilisateur déjà connecté via un guard (web, employe, client) 
 * n'accède à nouveau à ces pages.
 * 
 * Ce middleware remplace le guest middleware par défaut de Laravel qui ne
 * vérifie que le guard 'web'.
 */
class MultiGuardGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si un utilisateur est connecté sur n'importe lequel des guards
        $isLoggedIn =
            Auth::guard('web')->check() ||
            Auth::guard('employe')->check() ||
            Auth::guard('client')->check();

        // Si l'utilisateur est déjà connecté, le rediriger vers son dashboard
        if ($isLoggedIn) {
            return $this->redirectToDashboard($request);
        }

        return $next($request);
    }

    /**
     * Redirige l'utilisateur vers son dashboard approprié selon le guard
     */
    private function redirectToDashboard(Request $request): Response
    {
        // Priorité 1: Guard web (SuperAdmin)
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->estSuperAdmin()) {
                return redirect()->route('admin.superadmin.index');
            }
            return redirect()->route('admin.superadmin.index');
        }

        // Priorité 2: Guard employe
        if (Auth::guard('employe')->check()) {
            $employe = Auth::guard('employe')->user();
            return redirect()->to($employe->getDashboardUrl());
        }

        // Priorité 3: Guard client
        if (Auth::guard('client')->check()) {
            $client = Auth::guard('client')->user();
            return redirect()->to($client->getDashboardUrl());
        }

        // Fallback: redirection vers la page d'accueil
        return redirect('/');
    }
}
