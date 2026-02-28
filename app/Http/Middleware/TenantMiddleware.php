<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $entrepriseId = $this->getEntrepriseIdFromUser();

        if ($entrepriseId) {
            session(['entreprise_id' => $entrepriseId]);
        }

        return $next($request);
    }

    private function getEntrepriseIdFromUser(): ?int
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->estSuperAdmin() && session()->has('entreprise_id')) {
                return session('entreprise_id');
            }
            if ($user->entreprise_id) {
                return $user->entreprise_id;
            }
        }

        if (Auth::guard('employe')->check()) {
            $employe = Auth::guard('employe')->user();
            if ($employe->entreprise_id) {
                return $employe->entreprise_id;
            }
        }

        if (Auth::guard('client')->check()) {
            $client = Auth::guard('client')->user();
            if ($client->entreprise_id) {
                return $client->entreprise_id;
            }
        }

        return null;
    }
}
