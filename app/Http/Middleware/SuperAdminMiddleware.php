<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     * Vérifie que l'utilisateur est un Super Administrateur
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est connecté
        if (!$user) {
            return redirect('/login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérifier que l'utilisateur est un Super Admin
        if (!$user->estSuperAdmin()) {
            // Rediriger vers le dashboard approprié selon le rôle
            return redirect()->route($user->getAdminRoute())
                ->with('error', 'Vous n\'avez pas accès à cette section. Cette zone est réservée aux administrateurs de la plateforme.');
        }

        return $next($request);
    }
}
