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
     * Vérifie que l'utilisateur est un client
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est connecté
        if (!$user) {
            return redirect('/login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Les super admins ne devraient pas utiliser ce middleware (ils ont leur propre section)
        if ($user->estSuperAdmin()) {
            return redirect()->route('admin.superadmin.index')
                ->with('error', 'Veuillez utiliser le panneau d\'administration Super Admin.');
        }

        // Vérifier que l'utilisateur est un client
        if (!$user->estClient()) {
            // Rediriger vers le dashboard approprié selon le rôle
            return redirect()->route($user->getAdminRoute())
                ->with('error', 'Vous n\'avez pas accès à cette section. Cette zone est réservée aux clients.');
        }

        // Vérifier que l'utilisateur a une entreprise
        if (!$user->entreprise_id) {
            Auth::logout();
            return redirect('/login')->with('error', 'Votre compte n\'est pas lié à une entreprise.');
        }

        // Vérifier que l'entreprise est active
        $entreprise = $user->entreprise;
        if (!$entreprise || !$entreprise->est_active) {
            Auth::logout();
            return redirect('/login')->with('error', 'Votre entreprise est inactive. Veuillez contacter l\'administrateur.');
        }

        // Vérifier que le client est actif
        if ($user->client_id) {
            $client = $user->client;
            if (!$client || !$client->est_actif) {
                Auth::logout();
                return redirect('/login')->with('error', 'Votre compte client est inactif.');
            }
        }

        return $next($request);
    }
}
