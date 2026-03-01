<?php

namespace App\Http\Middleware;

use App\Models\Abonnement;
use App\Models\Employe;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAbonnementLimite
{
    /**
     * Handle an incoming request.
     * Vérifie si l'entreprise peut ajouter un nouvel employé selon sa limite d'abonnement.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtenir l'entreprise de l'utilisateur connecté
        $entreprise = null;

        if (Auth::check()) {
            $user = Auth::user();

            // Vérifier si c'est un utilisateur entreprise
            if ($user->entreprise_id) {
                $entreprise = \App\Models\Entreprise::find($user->entreprise_id);
            }
        }

        // Si pas d'entreprise, permettre l'action
        if (!$entreprise) {
            return $next($request);
        }

        // Obtenir l'abonnement de l'entreprise
        $abonnement = $entreprise->abonnement;

        // Si pas d'abonnement, permettre l'action (ou rediriger selon la logique métier)
        if (!$abonnement) {
            return $next($request);
        }

        // Obtenir le nombre actuel d'employés actifs
        $nombreEmployesActifs = $entreprise->employes()
            ->where('est_actif', true)
            ->where('statut', 'en_poste')
            ->count();

        // Obtenir la limite d'employés de l'abonnement
        $limiteEmployes = $abonnement->employes_max ?? $entreprise->nombre_agents_max;

        // Vérifier si on a atteint la limite
        if ($nombreEmployesActifs >= $limiteEmployes) {
            // Si c'est une requête AJAX ou API
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => "Vous avez atteint la limite de {$limiteEmployes} employés de votre abonnement {$abonnement->formuleLabel}.",
                    'limite' => $limiteEmployes,
                    'actuels' => $nombreEmployesActifs,
                    'formule' => $abonnement->formule,
                ], 422);
            }

            // Rediriger avec un message d'erreur
            return redirect()->back()
                ->with('error', "Vous avez atteint la limite de {$limiteEmployes} employés de votre abonnement {$abonnement->formuleLabel}. Veuillez upgrade votre abonnement pour ajouter plus d'employés.");
        }

        // Vérifier si l'abonnement est valide
        if (!$abonnement->estValide()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Votre abonnement a expiré. Veuillez le renouveler pour continuer.',
                    'expire' => true,
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Votre abonnement a expiré. Veuillez le renouveler pour continuer.');
        }

        return $next($request);
    }
}
