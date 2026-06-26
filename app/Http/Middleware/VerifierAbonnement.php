<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifierAbonnement
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !$user->entreprise) {
            return $next($request);
        }

        $entreprise = $user->entreprise;
        $abonnement = $entreprise->abonnement;

        if (!$abonnement) {
            return redirect()->route('abonnement.requis')
                ->with('error', 'Votre entreprise n\'a pas d\'abonnement actif. Veuillez contacter l\'administrateur.');
        }

        if (!$abonnement->est_valide) {
            $abonnement->update([
                'est_active' => false,
                'statut' => 'expire',
            ]);

            Auth::logout();

            return redirect()->route('login')
                ->with('error', 'Votre abonnement a expiré. Veuillez contacter l\'administrateur pour le renouveler.');
        }

        return $next($request);
    }
}
