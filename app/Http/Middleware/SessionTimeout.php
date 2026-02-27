<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     * Vérifie si la session a expiré par inactivité et déconnecte l'utilisateur
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si utilisateur non connecté, continuer
        if (!$this->isAuthenticated()) {
            return $next($request);
        }

        // Timeout en secondes (3 minutes)
        $timeout = config('session.lifetime') * 60;

        // Vérifier le dernier accès à la session
        $lastActivity = Session::get('last_activity');

        if ($lastActivity === null) {
            // Première requête après connexion - initialiser le timestamp
            Session::put('last_activity', time());
        } else {
            $elapsed = time() - $lastActivity;

            if ($elapsed >= $timeout) {
                // Session expirée - déconnecter l'utilisateur
                $this->logout($request);

                // Rediriger vers la page de connexion avec message
                return redirect('/login')->with('error', 'Votre session a expiré par inactivité. Veuillez vous reconnecter.');
            }

            // Mettre à jour le timestamp de dernière activité
            Session::put('last_activity', time());
        }

        return $next($request);
    }

    /**
     * Vérifie si un utilisateur est connecté (quel que soit le guard)
     */
    private function isAuthenticated(): bool
    {
        return Auth::guard('web')->check()
            || Auth::guard('employe')->check()
            || Auth::guard('client')->check();
    }

    /**
     * Déconnecte l'utilisateur de tous les guards
     */
    private function logout(Request $request): void
    {
        // Déconnecter SuperAdmin
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        // Déconnecter Employé
        if (Auth::guard('employe')->check()) {
            $employe = Auth::guard('employe')->user();
            if ($employe) {
                $employe->update(['est_connecte' => false]);
            }
            Auth::guard('employe')->logout();
        }

        // Déconnecter Client
        if (Auth::guard('client')->check()) {
            $client = Auth::guard('client')->user();
            if ($client) {
                $client->enregistrerDeconnexion();
            }
            Auth::guard('client')->logout();
        }

        // Invalider la session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
