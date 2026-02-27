<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedRedirect
{
    /**
     * Handle an incoming request.
     *
     * Ce middleware NE doit être appliqué QUE sur la route racine "/"
     * ou la page de login après connexion réussie.
     * Il NE doit PAS être appliqué sur les routes admin/* pour éviter les boucles.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // -------------------------------------------------------
        // RÈGLE ANTI-BOUCLE : si on est déjà sur une route admin,
        // NE JAMAIS rediriger — on laisse passer directement.
        // -------------------------------------------------------
        if ($request->is('admin/*')) {
            return $next($request);
        }

        // -------------------------------------------------------
        // Personne de connecté → laisser passer (login, register, etc.)
        // -------------------------------------------------------
        $webConnecte    = Auth::guard('web')->check();
        $employeConnecte = Auth::guard('employe')->check();
        $clientConnecte  = Auth::guard('client')->check();

        if (!$webConnecte && !$employeConnecte && !$clientConnecte) {
            return $next($request);
        }

        // -------------------------------------------------------
        // SuperAdmin (guard web) → dashboard superadmin
        // -------------------------------------------------------
        if ($webConnecte) {
            $user = Auth::guard('web')->user();
            if ($user->estSuperAdmin()) {
                return redirect()->route('admin.superadmin.index');
            }
        }

        // -------------------------------------------------------
        // Employé (guard employe) → dashboard selon son rôle
        // -------------------------------------------------------
        if ($employeConnecte) {
            $employe = Auth::guard('employe')->user();

            if ($employe->estDirection() || $employe->estSuperviseur() || $employe->estControleur()) {
                return redirect()->route('admin.entreprise.index');
            }

            if ($employe->estAgent()) {
                return redirect()->route('admin.agent.index');
            }

            // Rôle inconnu → dashboard entreprise par défaut
            return redirect()->route('admin.entreprise.index');
        }

        // -------------------------------------------------------
        // Client (guard client) → espace client
        // -------------------------------------------------------
        if ($clientConnecte) {
            return redirect()->route('admin.client.index');
        }

        return $next($request);
    }
}