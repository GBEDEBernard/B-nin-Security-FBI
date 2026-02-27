<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateEmploye
{
    /**
     * Handle an incoming request.
     * Vérifie que l'utilisateur est un employé actif connecté via le guard 'employe'.
     * Utilisé pour les routes agent (admin/agent*).
     */
    public function handle(Request $request, Closure $next): Response
    {
        // SuperAdmin → toujours autorisé
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->estSuperAdmin()) {
            return $next($request);
        }

        // Employé non connecté
        if (!Auth::guard('employe')->check()) {
            if (Auth::guard('client')->check()) {
                return redirect()->route('admin.client.index')
                    ->with('error', 'Cette section est réservée aux employés.');
            }
            return redirect('/login')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        $employe = Auth::guard('employe')->user();

        if (!$employe->est_actif) {
            Auth::guard('employe')->logout();
            return redirect('/login')
                ->with('error', 'Votre compte employé est inactif.');
        }

        if (!in_array($employe->statut, ['en_poste', 'conge'])) {
            Auth::guard('employe')->logout();
            return redirect('/login')
                ->with('error', 'Votre statut ne vous permet pas de vous connecter.');
        }

        $entreprise = $employe->entreprise;
        if (!$entreprise || !$entreprise->est_active) {
            Auth::guard('employe')->logout();
            return redirect('/login')
                ->with('error', 'Votre entreprise est inactive.');
        }

        return $next($request);
    }
}