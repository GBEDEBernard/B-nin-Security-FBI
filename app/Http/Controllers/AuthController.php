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
     * Si déjà connecté, redirige vers le dashboard
     */
    public function showLoginForm()
    {
        // Si déjà connecté, rediriger vers le dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('info', 'Vous êtes déjà connecté.');
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

        // Vérifier si l'utilisateur peut accéder à l'application
        if (!$user->peutAcceder()) {
            throw ValidationException::withMessages([
                'email' => ['Votre compte est inactif. Veuillez contacter l\'administrateur.'],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended('/dashboard')->with('success', 'Bienvenue ' . $user->name . ' !');
    }

    /**
     * Affiche le formulaire d'inscription
     * Si déjà connecté, redirige vers le dashboard
     */
    public function showRegistrationForm()
    {
        // Si déjà connecté, rediriger vers le dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('info', 'Vous êtes déjà connecté.');
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

        return redirect('/dashboard')->with('success', 'Compte créé avec succès !');
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
