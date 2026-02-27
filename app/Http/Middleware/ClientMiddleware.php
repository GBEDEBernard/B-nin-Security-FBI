<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     * Vérifie que l'utilisateur est un client actif connecté via le guard 'client'.
     * Utilisé pour les routes client (admin/client*).
     */
    public function handle(Request $request, Closure $next): Response
    {
        // SuperAdmin → toujours autorisé
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->estSuperAdmin()) {
            return $next($request);
        }

        // Client non connecté
        if (!Auth::guard('client')->check()) {
            if (Auth::guard('employe')->check()) {
                $employe = Auth::guard('employe')->user();
                // Rediriger l'employé vers son propre dashboard sans boucle
                if ($employe->estDirection() || $employe->estSuperviseur() || $employe->estControleur()) {
                    return redirect()->route('admin.entreprise.index')
                        ->with('error', 'Cette section est réservée aux clients.');
                }
                return redirect()->route('admin.agent.index')
                    ->with('error', 'Cette section est réservée aux clients.');
            }
            return redirect('/login')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        $client = Auth::guard('client')->user();

        if (!$client->est_actif) {
            Auth::guard('client')->logout();
            return redirect('/login')
                ->with('error', 'Votre compte client est inactif.');
        }

        $entreprise = $client->entreprise;
        if (!$entreprise || !$entreprise->est_active) {
            Auth::guard('client')->logout();
            return redirect('/login')
                ->with('error', 'Le service de sécurité de votre entreprise est inactif.');
        }

        return $next($request);
    }
}