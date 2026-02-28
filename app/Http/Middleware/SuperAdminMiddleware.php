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
     * Redirige les autres utilisateurs vers leur propre tableau de bord.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // -----------------------------------------------------------
        // CAS 1 : Vérifier si un SuperAdmin est connecté
        // -----------------------------------------------------------
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            // Vérifier si c'est un SuperAdmin
            if ($user->estSuperAdmin()) {
                return $next($request);
            }

            // User normal (non-superadmin) → vers son dashboard
            return redirect()->route('admin.superadmin.index');
        }

        // -----------------------------------------------------------
        // CAS 2 : Employé connecté → vers son dashboard
        // -----------------------------------------------------------
        if (Auth::guard('employe')->check()) {
            $employe = Auth::guard('employe')->user();
            return redirect()->to($employe->getDashboardUrl())
                ->with('error', 'Cette section est réservés aux administrateurs.');
        }

        // -----------------------------------------------------------
        // CAS 3 : Client connecté → vers son dashboard
        // -----------------------------------------------------------
        if (Auth::guard('client')->check()) {
            $client = Auth::guard('client')->user();
            return redirect()->to($client->getDashboardUrl())
                ->with('error', 'Cette section est réservés aux administrateurs.');
        }

        // -----------------------------------------------------------
        // CAS 4 : Non connecté → vers login
        // -----------------------------------------------------------
        return redirect('/login')->with('error', 'Vous devez être connecté.');
    }
}
