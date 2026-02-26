<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     * Vérifie que l'utilisateur est un SuperAdmin (User avec is_superadmin = true)
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si connecté
        if (!Auth::guard('web')->check()) {
            return redirect('/login')->with('error', 'Vous devez être connecté.');
        }

        $user = Auth::guard('web')->user();

        // Vérifier si c'est un SuperAdmin
        if (!$user->estSuperAdmin()) {
            abort(403, 'Accès réservé aux Super Administrateurs.');
        }

        return $next($request);
    }
}
