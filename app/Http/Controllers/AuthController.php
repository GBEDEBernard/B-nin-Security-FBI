<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     * Si déjà connecté, redirige vers l'admin
     */
    public function showLoginForm()
    {
        // Si déjà connecté, rediriger vers l'admin
        if (Auth::check()) {
            return redirect()->route('admin')->with('info', 'Vous êtes déjà connecté.');
        }
        return view('auth.login');
    }

    /**
     * Traite la connexion
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        // Vérifier si le compte est actif
        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Votre compte est inactif. Veuillez contacter l\'administrateur.'],
            ]);
        }

        // Vérifier si l'utilisateur peut accéder à l'application
        if (!$user->peutAcceder()) {
            throw ValidationException::withMessages([
                'email' => ['Votre compte est inactif. Veuillez contacter l\'administrateur.'],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        // Enregistrer les informations de connexion
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Rediriger vers l'admin approprié selon le rôle
        return redirect()->to($user->getAdminUrl())
            ->with('success', 'Bienvenue ' . $user->name . ' !');
    }

    /**
     * Affiche le formulaire d'inscription
     * Si déjà connecté, redirige vers l'admin
     */
    public function showRegistrationForm()
    {
        // Si déjà connecté, rediriger vers l'admin
        if (Auth::check()) {
            return redirect()->route('admin')->with('info', 'Vous êtes déjà connecté.');
        }
        return view('auth.register');
    }

    /**
     * Traite l'inscription
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assigner le rôle par défaut (agent pour les nouveaux utilisateurs)
        $user->assignRole('agent');

        Auth::login($user);

        return redirect('/admin')->with('success', 'Compte créé avec succès !');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Vous avez été déconnecté.');
    }
}
