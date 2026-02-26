<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employe;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectByGuard();
        }
        if (Auth::guard('employe')->check()) {
            return $this->redirectByGuard();
        }
        if (Auth::guard('client')->check()) {
            return $this->redirectByGuard();
        }
        return view('auth.login');
    }

    /**
     * Traite la connexion
     * Gère trois types d'utilisateurs:
     * 1. SuperAdmin (table users avec is_superadmin = true)
     * 2. Employés (table employes avec email/password)
     * 3. Clients (table clients avec email/password)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $request->email;

        // ─────────────────────────────────────────────────────────────
        // 1. Essayer d'abord comme SuperAdmin (User)
        // ─────────────────────────────────────────────────────────────
        $user = User::where('email', $email)->first();

        if ($user && $user->is_superadmin && Hash::check($request->password, $user->password)) {
            // Connexion SuperAdmin
            if (!$user->is_active) {
                throw ValidationException::withMessages([
                    'email' => ['Votre compte SuperAdmin est inactif.'],
                ]);
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            // Enregistrer les informations de connexion
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            return redirect()->route('admin.superadmin.index')
                ->with('success', 'Bienvenue ' . $user->name . ' !');
        }

        // ─────────────────────────────────────────────────────────────
        // 2. Essayer comme Employé
        // ─────────────────────────────────────────────────────────────
        $employe = Employe::where('email', $email)
            ->whereNotNull('password')
            ->whereNotNull('email')
            ->first();

        if ($employe && Hash::check($request->password, $employe->password)) {
            // Vérifier si l'employé peut se connecter
            if (!$employe->peutSeConnecter()) {
                throw ValidationException::withMessages([
                    'email' => ['Votre compte employé est inactif ou vous n\'êtes plus en poste.'],
                ]);
            }

            // Vérifier si l'entreprise est active
            $entreprise = $employe->entreprise;
            if (!$entreprise || !$entreprise->est_active) {
                throw ValidationException::withMessages([
                    'email' => ['Votre entreprise est inactive. Veuillez contacter l\'administrateur.'],
                ]);
            }

            // Connexion Employé via Guard 'employe'
            Auth::guard('employe')->login($employe, $request->boolean('remember'));
            $request->session()->regenerate();

            // Enregistrer les informations de connexion
            $employe->update([
                'est_connecte' => true,
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Rediriger selon le poste
            return redirect()->to($employe->getDashboardUrl())
                ->with('success', 'Bienvenue ' . $employe->nomComplet . ' !');
        }

        // ─────────────────────────────────────────────────────────────
        // 3. Essayer comme Client
        // ─────────────────────────────────────────────────────────────
        $client = Client::where('email', $email)
            ->whereNotNull('password')
            ->whereNotNull('email')
            ->first();

        if ($client && Hash::check($request->password, $client->password)) {
            // Vérifier si le client peut se connecter
            if (!$client->peutSeConnecter()) {
                throw ValidationException::withMessages([
                    'email' => ['Votre compte client est inactif. Veuillez contacter l\'administrateur.'],
                ]);
            }

            // Vérifier si l'entreprise associée est active
            $entreprise = $client->entreprise;
            if (!$entreprise || !$entreprise->est_active) {
                throw ValidationException::withMessages([
                    'email' => ['Le service de sécurité de votre entreprise est inactif.'],
                ]);
            }

            // Connexion Client via Guard 'client'
            Auth::guard('client')->login($client, $request->boolean('remember'));
            $request->session()->regenerate();

            // Enregistrer les informations de connexion
            $client->enregistrerConnexion($request->ip());

            // Rediriger vers le dashboard client
            return redirect()->to($client->getDashboardUrl())
                ->with('success', 'Bienvenue ' . $client->nomAffichage . ' !');
        }

        // ─────────────────────────────────────────────────────────────
        // 4. Échec - Identifiants incorrects
        // ─────────────────────────────────────────────────────────────
        throw ValidationException::withMessages([
            'email' => ['Les identifiants sont incorrects.'],
        ]);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        // Vérifier si c'est un employe connecté
        if (Auth::guard('employe')->check()) {
            $employe = Auth::guard('employe')->user();
            $employe->update(['est_connecte' => false]);
            Auth::guard('employe')->logout();
        }
        // Vérifier si c'est un client connecté
        elseif (Auth::guard('client')->check()) {
            $client = Auth::guard('client')->user();
            $client->enregistrerDeconnexion();
            Auth::guard('client')->logout();
        }
        // Sinon c'est un SuperAdmin
        else {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Vous avez été déconnecté.');
    }

    /**
     * Redirige selon le guard utilisé
     */
    private function redirectByGuard()
    {
        // Vérifier si c'est un SuperAdmin
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->estSuperAdmin()) {
                return redirect()->route('admin.superadmin.index');
            }
        }

        // Vérifier si c'est un employé
        if (Auth::guard('employe')->check()) {
            $employe = Auth::guard('employe')->user();
            return redirect()->to($employe->getDashboardUrl());
        }

        // Vérifier si c'est un client
        if (Auth::guard('client')->check()) {
            $client = Auth::guard('client')->user();
            return redirect()->to($client->getDashboardUrl());
        }

        return redirect('/login');
    }
}
