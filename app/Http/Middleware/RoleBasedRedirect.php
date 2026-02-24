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

        // Tableau des routes admin par rôle
        $adminRoutes = [
            'superadmin' => 'admin.superadmin.index',
            'entreprise' => 'admin.entreprise.index',
            'agent' => 'admin.agent.index',
            'client' => 'admin.client.index',
        ];

        // Obtenir la route attendue selon le rôle
        $expectedRoute = $user->getAdminRoute();

        // Si l'utilisateur accède à une route admin différente de son rôle
        foreach ($adminRoutes as $type => $routeName) {
            $routePattern = 'admin/' . $type;

            // Si l'utilisateur est sur un admin qui n'est pas le sien
            if (str_starts_with($currentPath, 'admin/' . $type) && $expectedRoute !== $routeName) {
                // Rediriger vers son propre admin
                return redirect()->route($expectedRoute)->with('info', 'Vous avez été redirigé vers votre tableau de bord.');
            }
        }

        return $next($request);
    }
}
