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
     * Redirige l'utilisateur vers l'admin approprié selon son rôle
     * Gère à la fois User (SuperAdmin) et Employe
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si utilisateur non connecté, continuer
        if (!Auth::guard('web')->check() && !Auth::guard('employe')->check()) {
            return $next($request);
        }

        // ─────────────────────────────────────────────────────────────
        // 1. Vérifier si c'est un SuperAdmin (User)
        // ─────────────────────────────────────────────────────────────
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            if ($user->estSuperAdmin()) {
                // Si déjà sur une autre route admin, rediriger vers superadmin
                if (!$request->is('admin/superadmin*')) {
                    return redirect()->route('admin.superadmin.index')
                        ->with('info', 'Vous avez été redirigé vers votre tableau de bord.');
                }
            }
        }

        // ─────────────────────────────────────────────────────────────
        // 2. Vérifier si c'est un Employé
        // ─────────────────────────────────────────────────────────────
        if (Auth::guard('employe')->check()) {
            $employe = Auth::guard('employe')->user();

            // Obtenir la route attendue selon le poste
            $expectedRoute = $employe->getDashboardRoute();
            $currentPath = $request->path();

            // Si l'utilisateur n'est pas sur la bonne route
            if ($employe->estDirection() || $employe->estSuperviseur() || $employe->estControleur()) {
                // Direction, Supervision, Contrôle -> admin/entreprise
                if (!str_starts_with($currentPath, 'admin/entreprise') && !str_starts_with($currentPath, 'admin/superadmin')) {
                    return redirect()->route('admin.entreprise.index')
                        ->with('info', 'Vous avez été redirigé vers votre tableau de bord.');
                }
            } elseif ($employe->estAgent()) {
                // Agent -> admin/agent
                if (!str_starts_with($currentPath, 'admin/agent') && !str_starts_with($currentPath, 'admin/superadmin')) {
                    return redirect()->route('admin.agent.index')
                        ->with('info', 'Vous avez été redirigé vers votre tableau de bord.');
                }
            }
        }

        return $next($request);
    }
}
