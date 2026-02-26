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
     * Vérifie que l'utilisateur est un client connecté via le guard 'client'
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si un client est connecté via le guard 'client'
        if (!Auth::guard('client')->check()) {
            // Si c'est un SuperAdmin connecté, le laisser passer
            if (Auth::guard('web')->check() && Auth::guard('web')->user()->estSuperAdmin()) {
                return $next($request);
            }
            // Si c'est un employé connecté, le rediriger vers son dashboard
            if (Auth::guard('employe')->check()) {
                $employe = Auth::guard('employe')->user();
                return redirect()->to($employe->getDashboardUrl())
                    ->with('error', 'Cette section est réservée aux clients.');
            }
            return redirect('/login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

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

        return $next($request);
    }
}
