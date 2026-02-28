<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de portée de tenant (entreprise)
 * 
 * Ce middleware ajoute le contexte tenant sans bloquer l'accès
 */
class TenantScope
{
    /**
     * Handle an incoming request.
     * Ajoute le contexte tenant sans bloquer l'accès
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Chercher l'entreprise depuis l'utilisateur connecté
        $entrepriseId = $this->getEntrepriseIdFromUser();

        if ($entrepriseId) {
            // Stocker en session pour accès ultérieur
            session(['entreprise_id' => $entrepriseId]);
        }

        return $next($request);
    }

    /**
     * Obtenir l'ID de l'entreprise depuis l'utilisateur connecté
     */
    private function getEntrepriseIdFromUser(): ?int
    {
        // SuperAdmin en contexte entreprise spécifique
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            // Si SuperAdmin a sélectionné une entreprise
            if ($user->estSuperAdmin() && session()->has('entreprise_id')) {
                return session('entreprise_id');
            }

            // Si utilisateur entreprise (employé avec compte user)
            if (!empty($user->entreprise_id)) {
                return $user->entreprise_id;
            }
        }

        // Employé connecté via guard employe
        if (Auth::guard('employe')->check()) {
            $employe = Auth::guard('employe')->user();
            if (!empty($employe->entreprise_id)) {
                return $employe->entreprise_id;
            }
        }

        // Client connecté via guard client
        if (Auth::guard('client')->check()) {
            $client = Auth::guard('client')->user();
            if (!empty($client->entreprise_id)) {
                return $client->entreprise_id;
            }
        }

        return null;
    }
}
