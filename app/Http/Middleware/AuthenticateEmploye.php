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
     * Vérifie que l'utilisateur est un employé connecté
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si un employé est connecté via le guard 'employe'
        if (!Auth::guard('employe')->check()) {
            // Si c'est un user (SuperAdmin) connecté, le laisser passer
            if (Auth::guard('web')->check() && Auth::guard('web')->user()->estSuperAdmin()) {
                return $next($request);
            }
            return redirect('/login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        $employe = Auth::guard('employe')->user();

        // Vérifier si l'employé est actif
        if (!$employe->est_actif) {
            Auth::guard('employe')->logout();
            return redirect('/login')->with('error', 'Votre compte employé est inactif.');
        }

        // Vérifier le statut (en_poste ou conge)
        if (!in_array($employe->statut, ['en_poste', 'conge'])) {
            Auth::guard('employe')->logout();
            return redirect('/login')->with('error', 'Votre statut ne vous permet pas de vous connecter.');
        }

        // Vérifier si l'entreprise est active
        $entreprise = $employe->entreprise;
        if (!$entreprise || !$entreprise->est_active) {
            Auth::guard('employe')->logout();
            return redirect('/login')->with('error', 'Votre entreprise est inactive.');
        }

        return $next($request);
    }
}
