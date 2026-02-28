<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Stancl\Tenancy\Facades\Tenancy;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     * Vérifie que l'utilisateur appartient à un tenant actif
     * Gère User (SuperAdmin), Employe et Client
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ─────────────────────────────────────────────────────────────
        // Ne pas vérifier sur les routes publiques (login, register, etc.)
        // ─────────────────────────────────────────────────────────────
        $publicRoutes = ['login', 'register', 'logout', 'password.reset'];

        // Si c'est une route publique, laisser passer
        if ($request->routeIs($publicRoutes) || $request->is('login/*') || $request->is('register/*')) {
            return $next($request);
        }

        // ─────────────────────────────────────────────────────────────
        // 1. Vérifier si un SuperAdmin (User) est connecté
        // ─────────────────────────────────────────────────────────────
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            // Les super admins ont accès à tout
            if ($user->estSuperAdmin()) {
                // Stocker le contexte entreprise si sélectionné
                if (session()->has('entreprise_id')) {
                    $this->initializeTenancyByEntreprise(session('entreprise_id'));
                }
                return $next($request);
            }

            // Utilisateur entreprise (employé avec compte user)
            if ($user->entreprise_id) {
                $this->initializeTenancyByEntreprise($user->entreprise_id);
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

            // Initialiser le contexte tenant pour cet employé
            $this->initializeTenancyByEntreprise($employe->entreprise_id);

            return $next($request);
        }

        // ─────────────────────────────────────────────────────────────
        // 3. Vérifier si un Client est connecté
        // ─────────────────────────────────────────────────────────────
        if (Auth::guard('client')->check()) {
            $client = Auth::guard('client')->user();

            // Vérifier que le client est actif
            if (!$client->est_actif) {
                Auth::guard('client')->logout();
                return redirect('/login')->with('error', 'Votre compte client est inactif.');
            }

            // Vérifier que l'entreprise associée est active
            $entreprise = $client->entreprise;
            if (!$entreprise || !$entreprise->est_active) {
                Auth::guard('client')->logout();
                return redirect('/login')->with('error', 'Le service de sécurité de votre entreprise est inactif.');
            }

            // Initialiser le contexte tenant pour ce client
            $this->initializeTenancyByEntreprise($client->entreprise_id);

            return $next($request);
        }

        // ─────────────────────────────────────────────────────────────
        // 4. Aucun utilisateur connecté - laisser le middleware 'multi-guest' gérer
        // ─────────────────────────────────────────────────────────────
        return $next($request);
    }

    /**
     * Initialiser le contexte tenant basé sur l'entreprise
     * Utilise la approche column-based (entreprise_id) au lieu de database-per-tenant
     */
    private function initializeTenancyByEntreprise(int $entrepriseId): void
    {
        // Stocker l'entreprise_id en session pour les scopes globaux
        if (!session()->has('entreprise_id') || session('entreprise_id') != $entrepriseId) {
            session(['entreprise_id' => $entrepriseId]);
        }

        // Si vous utilisez le système database-per-tenant de Stancl,
        // décommentez les lignes suivantes:
        /*
        $tenant = \App\Models\Tenant::find($entrepriseId);
        if ($tenant) {
            Tenancy::initialize($tenant);
        }
        */
    }
}
