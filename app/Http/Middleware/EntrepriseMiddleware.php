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
     *
     * Autorise l'accès aux routes admin/entreprise* pour :
     *   1. Un Employé actif (Direction, Superviseur, Contrôleur, Agent)
     *   2. Un SuperAdmin qui a sélectionné une entreprise en session
     *
     * En cas d'échec → redirection vers /login (jamais vers admin/entreprise).
     */
    public function handle(Request $request, Closure $next): Response
    {
        // -----------------------------------------------------------
        // CAS 1 : SuperAdmin (guard web) en contexte entreprise
        // -----------------------------------------------------------
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            if ($user->estSuperAdmin()) {
                if (session()->has('entreprise_id') && session('entreprise_id')) {
                    // SuperAdmin avec une entreprise sélectionnée → OK
                    return $next($request);
                }
                // SuperAdmin sans entreprise sélectionnée → son propre dashboard
                return redirect()->route('admin.superadmin.index')
                    ->with('info', 'Veuillez sélectionner une entreprise pour accéder à ce tableau de bord.');
            }

            // Un User (web) non-superadmin n'a rien à faire ici
            return redirect()->route('admin.superadmin.index');
        }

        // -----------------------------------------------------------
        // CAS 2 : Employé (guard employe)
        // -----------------------------------------------------------
        if (!Auth::guard('employe')->check()) {
            // Client essayant d'accéder à l'espace entreprise
            if (Auth::guard('client')->check()) {
                return redirect()->route('admin.client.index')
                    ->with('error', 'Cette section est réservée aux employés.');
            }
            // Non connecté
            return redirect('/login')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        $employe = Auth::guard('employe')->user();

        // Compte désactivé
        if (!$employe->est_actif) {
            Auth::guard('employe')->logout();
            return redirect('/login')
                ->with('error', 'Votre compte est désactivé. Contactez votre administrateur.');
        }

        // Statut invalide
        if (!in_array($employe->statut, ['en_poste', 'conge'])) {
            Auth::guard('employe')->logout();
            return redirect('/login')
                ->with('error', 'Votre statut ne permet pas la connexion.');
        }

        // Entreprise inactive
        $entreprise = $employe->entreprise;
        if (!$entreprise || !$entreprise->est_active) {
            Auth::guard('employe')->logout();
            return redirect('/login')
                ->with('error', 'Votre entreprise est inactive. Contactez l\'administrateur.');
        }

        return $next($request);
    }
}