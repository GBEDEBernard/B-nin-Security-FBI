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
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Les super admins ont accès à tout
        if ($user && $user->estSuperAdmin()) {
            return $next($request);
        }

        // Vérifier que l'utilisateur a une entreprise
        if (!$user || !$user->entreprise_id) {
            Auth::logout();
            return redirect('/login')->with('error', 'Votre compte n\'est pas lié à une entreprise.');
        }

        // Vérifier que l'entreprise est active
        $entreprise = $user->entreprise;
        if (!$entreprise || !$entreprise->est_active) {
            Auth::logout();
            return redirect('/login')->with('error', 'Votre entreprise est inactive. Veuillez contacter l\'administrateur.');
        }

        // Pour les utilisateurs de type client, vérifier que le client est actif
        if ($user->estClient() && $user->client_id) {
            $client = $user->client;
            if (!$client || !$client->est_actif) {
                Auth::logout();
                return redirect('/login')->with('error', 'Votre compte client est inactif.');
            }
        }

        return $next($request);
    }
}
