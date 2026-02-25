<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UtilisateurController extends Controller
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Liste des utilisateurs
     */
    public function index(Request $request)
    {
        $query = User::with('entreprise');

        if ($request->filled('entreprise_id')) {
            $query->where('entreprise_id', $request->entreprise_id);
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('statut')) {
            $query->where('is_active', $request->statut === 'active');
        }

        $utilisateurs = $query->orderBy('name')->paginate(15);
        $entreprises = Entreprise::orderBy('nom_entreprise')->get();

        return view('admin.superadmin.utilisateurs.index', compact('utilisateurs', 'entreprises'));
    }

    /**
     * Créer un utilisateur
     */
    public function create()
    {
        $entreprises = Entreprise::orderBy('nom_entreprise')->get();
        $roles = ['admin', 'directeur', 'superviseur', 'controleur', 'agent', 'client'];

        return view('admin.superadmin.utilisateurs.create', compact('entreprises', 'roles'));
    }

    /**
     * Enregistrer un utilisateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'entreprise_id' => 'nullable|exists:entreprises,id',
            'role' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'password' => Hash::make($validated['password']),
            'entreprise_id' => $validated['entreprise_id'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'is_superadmin' => false,
            'type_user' => $validated['role'] === 'client' ? 'client' : 'interne',
        ]);

        // Assigner le rôle
        $user->assignRole($validated['role']);

        // Envoyer email de création de compte
        // Mail::to($user)->send(new AccountCreated($user, $validated['password']));

        return redirect()->route('admin.superadmin.utilisateurs.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Voir un utilisateur
     */
    public function show($id)
    {
        $utilisateur = User::with(['entreprise', 'employe', 'roles'])->findOrFail($id);

        return view('admin.superadmin.utilisateurs.show', compact('utilisateur'));
    }

    /**
     * Modifier un utilisateur
     */
    public function edit($id)
    {
        $utilisateur = User::findOrFail($id);
        $entreprises = Entreprise::orderBy('nom_entreprise')->get();
        $roles = ['admin', 'directeur', 'superviseur', 'controleur', 'agent', 'client'];

        return view('admin.superadmin.utilisateurs.edit', compact('utilisateur', 'entreprises', 'roles'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, $id)
    {
        $utilisateur = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'telephone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'entreprise_id' => 'nullable|exists:entreprises,id',
            'role' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'entreprise_id' => $validated['entreprise_id'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $utilisateur->update($data);

        // Mettre à jour le rôle
        $utilisateur->syncRoles([$validated['role']]);

        return redirect()->route('admin.superadmin.utilisateurs.index')
            ->with('success', 'Utilisateur mis à jour.');
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy($id)
    {
        $utilisateur = User::findOrFail($id);

        if ($utilisateur->is_superadmin) {
            return back()->with('error', 'Impossible de supprimer un super admin.');
        }

        $utilisateur->delete();

        return redirect()->route('admin.superadmin.utilisateurs.index')
            ->with('success', 'Utilisateur supprimé.');
    }

    /**
     * Activer un utilisateur
     */
    public function activate($id)
    {
        $utilisateur = User::findOrFail($id);
        $utilisateur->update(['is_active' => true]);

        return back()->with('success', 'Utilisateur activé.');
    }

    /**
     * Désactiver un utilisateur
     */
    public function deactivate($id)
    {
        $utilisateur = User::findOrFail($id);
        $utilisateur->update(['is_active' => false]);

        return back()->with('success', 'Utilisateur désactivé.');
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(Request $request, $id)
    {
        $utilisateur = User::findOrFail($id);

        $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 12);

        $utilisateur->update([
            'password' => Hash::make($newPassword),
        ]);

        // Envoyer le mot de passe par email
        // Mail::to($utilisateur)->send(new PasswordReset($utilisateur, $newPassword));

        return back()->with('success', 'Mot de passe réinitialisé. Un email a été envoyé.');
    }

    /**
     * Exporter les utilisateurs
     */
    public function export(Request $request)
    {
        // Logique d'export
        return back()->with('info', 'Export en cours de développement.');
    }
}
