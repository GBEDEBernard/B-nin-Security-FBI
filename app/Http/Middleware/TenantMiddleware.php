<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     * Vérifie que l'utilisateur appartient à un tenant actif
     * Gère à la fois User (SuperAdmin) et Employe
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ─────────────────────────────────────────────────────────────
        // 1. Vérifier si un SuperAdmin (User) est connecté
        // ─────────────────────────────────────────────────────────────
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            // Les super admins ont accès à tout
            if ($user->estSuperAdmin()) {
                return $next($request);
            }
        }

        // ─────────────────────────────────────────────────────────────
        // 2. Vérifier si un Employé est connecté
        // ─────────────────────────────────────────────────────────────
        if (Auth::guard('employe')->check()) {
            $employe = Auth::guard('employe')->user();

            // Vérifier que l'employé est actif
            if (!$employe->est_actif) {
                Auth::guard('employe')->logout();
                return redirect('/login')->with('error', 'Votre compte employé est inactif.');
            }

            // Vérifier le statut (en_poste ou conge)
            if (!in_array($employe->statut, ['en_poste', 'conge'])) {
                Auth::guard('employe')->logout();
                return redirect('/login')->with('error', 'Votre statut ne permet pas la connexion.');
            }

            // Vérifier que l'entreprise est active
            $entreprise = $employe->entreprise;
            if (!$entreprise || !$entreprise->est_active) {
                Auth::guard('employe')->logout();
                return redirect('/login')->with('error', 'Votre entreprise est inactive.');
            }

            return $next($request);
        }

        // ─────────────────────────────────────────────────────────────
        // 3. Aucun utilisateur connecté - rediriger vers login
        // ─────────────────────────────────────────────────────────────
        return redirect('/login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
    }
}
