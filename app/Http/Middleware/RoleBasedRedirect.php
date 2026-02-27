<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedRedirect
{
    /**
     * Handle an incoming request.
     * Ce middleware vérifie seulement si l'utilisateur est sur la bonne route.
     * Les redirections principales sont gérées par les routes elles-mêmes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si utilisateur non connecté, continuer (la route / gère la redirection)
        if (!Auth::guard('web')->check() && !Auth::guard('employe')->check() && !Auth::guard('client')->check()) {
            return $next($request);
        }

        // Empêcher les boucles de redirection en vérifiant si on vient déjà d'une redirection
        if (session()->has('just_redirected')) {
            session()->forget('just_redirected');
            return $next($request);
        }

        // Marquer qu'on a fait une redirection
        $shouldRedirect = false;
        $redirectRoute = null;
        $redirectMessage = '';

        // Vérifier si c'est un SuperAdmin (User)
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            if ($user->estSuperAdmin() && !$request->is('admin/superadmin*')) {
                $shouldRedirect = true;
                $redirectRoute = 'admin.superadmin.index';
                $redirectMessage = 'Vous avez été redirigé vers votre tableau de bord Super Admin.';
            }
        }

        // Vérifier si c'est un Employé
        if (!$shouldRedirect && Auth::guard('employe')->check()) {
            $employe = Auth::guard('employe')->user();

            if ($employe->estDirection() || $employe->estSuperviseur() || $employe->estControleur()) {
                if (!$request->is('admin/entreprise*') && !$request->is('admin/superadmin*')) {
                    $shouldRedirect = true;
                    $redirectRoute = 'admin.entreprise.index';
                    $redirectMessage = 'Vous avez été redirigé vers votre tableau de bord.';
                }
            } elseif ($employe->estAgent()) {
                if (!$request->is('admin/agent*') && !$request->is('admin/superadmin*')) {
                    $shouldRedirect = true;
                    $redirectRoute = 'admin.agent.index';
                    $redirectMessage = 'Vous avez été redirigé vers votre tableau de bord agent.';
                }
            }
        }

        // Vérifier si c'est un Client
        if (!$shouldRedirect && Auth::guard('client')->check()) {
            if (!$request->is('admin/client*') && !$request->is('admin/superadmin*')) {
                $shouldRedirect = true;
                $redirectRoute = 'admin.client.index';
                $redirectMessage = 'Vous avez été redirigé vers votre espace client.';
            }
        }

        if ($shouldRedirect && $redirectRoute) {
            session()->put('just_redirected', true);
            return redirect()->route($redirectRoute)->with('info', $redirectMessage);
        }

        return $next($request);
    }
}
