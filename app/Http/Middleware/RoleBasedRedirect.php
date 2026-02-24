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
     * Redirige l'utilisateur vers le dashboard approprié selon son rôle
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si utilisateur non connecté, continuer
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Si utilisateur inactif, ne pas rediriger (la déconnexion sera faite ailleurs)
        if (!$user->is_active) {
            return $next($request);
        }

        // Chemin actuel
        $currentPath = $request->path();

        // Tableau des routes de dashboard par rôle
        $dashboardRoutes = [
            'superadmin' => 'dashboard.superadmin.index',
            'entreprise' => 'dashboard.entreprise.index',
            'agent' => 'dashboard.agent.index',
            'client' => 'dashboard.client.index',
        ];

        // Obtenir la route attendue selon le rôle
        $expectedRoute = $user->getDashboardRoute();

        // Si l'utilisateur accède à une route de dashboard différente de son rôle
        foreach ($dashboardRoutes as $type => $routeName) {
            $routePattern = 'dashboard.' . $type;

            // Si l'utilisateur est sur un dashboard qui n'est pas le sien
            if (str_starts_with($currentPath, 'dashboard/' . $type) && $expectedRoute !== $routeName) {
                // Rediriger vers son propre dashboard
                return redirect()->route($expectedRoute)->with('info', 'Vous avez été redirigé vers votre tableau de bord.');
            }
        }

        return $next($request);
    }
}
