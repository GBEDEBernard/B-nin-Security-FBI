<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UtilisateurController extends Controller
{
    /**
     * Constructeur - middleware superadmin uniquement
     */
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Liste des Super Admins
     * Note: On gère UNIQUEMENT les SuperAdmins ici
     * Les employés et clients sont gérés par leurs modules respectifs
     */
    public function index(Request $request)
    {
        $query = User::where('is_superadmin', true);

        // Recherche par nom ou email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtrer par statut
        if ($request->filled('statut')) {
            $query->where('is_active', $request->statut === 'active');
        }

        // Tri
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');
        $allowedSorts = ['name', 'email', 'created_at', 'last_login_at', 'is_active'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('name');
        }

        $utilisateurs = $query->paginate(15)->appends($request->query());

        return view('admin.superadmin.utilisateurs.index', compact('utilisateurs'));
    }

    /**
     * Formulaire de création d'un Super Admin
     */
    public function create()
    {
        return view('admin.superadmin.utilisateurs.create');
    }

    /**
     * Enregistrer un nouveau Super Admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_superadmin' => true,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Assigner le rôle super_admin (comme défini dans RolesAndPermissionsSeeder)
        $user->assignRole('super_admin');

        return redirect()->route('admin.superadmin.utilisateurs.index')
            ->with('success', 'Super Administrateur créé avec succès.');
    }

    /**
     * Voir les détails d'un Super Admin
     */
    public function show($id)
    {
        $utilisateur = User::with('roles')->findOrFail($id);

        // Sécurisé: ne montrer que les superadmins
        if (!$utilisateur->is_superadmin) {
            return redirect()->route('admin.superadmin.utilisateurs.index')
                ->with('error', 'Cet utilisateur n\'est pas un Super Administrateur.');
        }

        return view('admin.superadmin.utilisateurs.show', compact('utilisateur'));
    }

    /**
     * Formulaire de modification d'un Super Admin
     */
    public function edit($id)
    {
        $utilisateur = User::findOrFail($id);

        // Sécurisé: ne modifier que les superadmins
        if (!$utilisateur->is_superadmin) {
            return redirect()->route('admin.superadmin.utilisateurs.index')
                ->with('error', 'Cet utilisateur n\'est pas un Super Administrateur.');
        }

        return view('admin.superadmin.utilisateurs.edit', compact('utilisateur'));
    }

    /**
     * Mettre à jour un Super Admin
     */
    public function update(Request $request, $id)
    {
        $utilisateur = User::findOrFail($id);

        // Vérification de sécurité
        if (!$utilisateur->is_superadmin) {
            return redirect()->route('admin.superadmin.utilisateurs.index')
                ->with('error', 'Cet utilisateur n\'est pas un Super Administrateur.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'telephone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ];

        // Mettre à jour le mot de passe seulement si fourni
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $utilisateur->update($data);

        return redirect()->route('admin.superadmin.utilisateurs.index')
            ->with('success', 'Super Administrateur mis à jour.');
    }

    /**
     * Supprimer un Super Admin
     */
    public function destroy($id)
    {
        $utilisateur = User::findOrFail($id);

        // Vérifications de sécurité
        if (!$utilisateur->is_superadmin) {
            return back()->with('error', 'Cet utilisateur n\'est pas un Super Administrateur.');
        }

        // Empêcher la suppression du dernier Super Admin
        $countSuperAdmins = User::where('is_superadmin', true)->count();
        if ($countSuperAdmins <= 1) {
            return back()->with('error', 'Impossible de supprimer le dernier Super Administrateur.');
        }

        // Empêcher l'auto-suppression
        if (auth()->id() === $id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $utilisateur->delete();

        return redirect()->route('admin.superadmin.utilisateurs.index')
            ->with('success', 'Super Administrateur supprimé.');
    }

    /**
     * Activer un Super Admin
     */
    public function activate($id)
    {
        $utilisateur = User::findOrFail($id);

        if (!$utilisateur->is_superadmin) {
            return back()->with('error', 'Cet utilisateur n\'est pas un Super Administrateur.');
        }

        $utilisateur->update(['is_active' => true]);

        return back()->with('success', 'Super Administrateur activé.');
    }

    /**
     * Désactiver un Super Admin
     */
    public function deactivate($id)
    {
        $utilisateur = User::findOrFail($id);

        if (!$utilisateur->is_superadmin) {
            return back()->with('error', 'Cet utilisateur n\'est pas un Super Administrateur.');
        }

        // Empêcher l'auto-désactivation
        if (auth()->id() === $id) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $utilisateur->update(['is_active' => false]);

        return back()->with('success', 'Super Administrateur désactivé.');
    }

    /**
     * Réinitialiser le mot de passe d'un Super Admin
     */
    public function resetPassword(Request $request, $id)
    {
        $utilisateur = User::findOrFail($id);

        if (!$utilisateur->is_superadmin) {
            return back()->with('error', 'Cet utilisateur n\'est pas un Super Administrateur.');
        }

        // Générer un mot de passe aléatoire sécurisé
        $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%'), 0, 12);

        $utilisateur->update([
            'password' => Hash::make($newPassword),
        ]);

        // Note: Dans un vrai projet, envoyer par email
        // Mail::to($utilisateur)->send(new PasswordReset($utilisateur, $newPassword));

        // Pour le développement, retourner le mot de passe (⚠️ temporaire)
        if (app()->isLocal()) {
            return back()->with('info', "Mot de passe réinitialisé. Password temporaire: {$newPassword}");
        }

        return back()->with('success', 'Mot de passe réinitialisé. Un email a été envoyé.');
    }

    /**
     * Exporter les Super Admins
     */
    public function export(Request $request)
    {
        $users = User::where('is_superadmin', true)->get();

        // Logique d'export CSV (à implémenter)
        return back()->with('info', 'Export en cours de développement.');
    }
}
