<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EntrepriseMiddleware
{
    /**
     * Handle an incoming request.
     * Vérifie que l'utilisateur est un membre interne de l'entreprise
     * (Direction, Superviseur, Contrôleur, Agent) via la table Employe
     * OU que c'est un SuperAdmin en contexte entreprise
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si un Employé est connecté
        if (!Auth::guard('employe')->check()) {
            // Si c'est un SuperAdmin
            if (Auth::guard('web')->check() && Auth::guard('web')->user()->estSuperAdmin()) {
                // Vérifier si le SuperAdmin est en contexte entreprise
                if (session()->has('entreprise_id') && session('entreprise_id')) {
                    // SuperAdmin en contexte entreprise - autorisé
                    return $next($request);
                }
                // SuperAdmin pas en contexte - rediriger vers son dashboard
                return redirect()->route('admin.superadmin.index')
                    ->with('error', 'Veuillez sélectionner une entreprise pour accéder à ce tableau de bord.');
            }
            return redirect('/login');
        }

        $employe = Auth::guard('employe')->user();

        // Vérifier si l'employé est actif
        if (!$employe->est_actif) {
            Auth::guard('employe')->logout();
            return redirect('/login')->with('error', 'Votre compte est désactivé.');
        }

        // Vérifier le statut (en_poste ou conge)
        if (!in_array($employe->statut, ['en_poste', 'conge'])) {
            Auth::guard('employe')->logout();
            return redirect('/login')->with('error', 'Votre statut ne permet pas la connexion.');
        }

        // L'entreprise doit être active
        $entreprise = $employe->entreprise;
        if (!$entreprise || !$entreprise->est_active) {
            Auth::guard('employe')->logout();
            return redirect('/login')->with('error', 'Votre entreprise est inactive. Contactez l\'administrateur.');
        }

        return $next($request);
    }
}
