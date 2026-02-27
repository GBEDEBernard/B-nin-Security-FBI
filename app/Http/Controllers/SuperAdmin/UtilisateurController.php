<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UtilisateurController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Liste des Super Admins
     */
    public function index(Request $request)
    {
        $query = User::where('is_superadmin', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('statut')) {
            $query->where('is_active', $request->statut === 'active');
        }

        $sortBy    = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');
        $allowed   = ['name', 'email', 'created_at', 'last_login_at', 'is_active'];

        $query->orderBy(in_array($sortBy, $allowed) ? $sortBy : 'name', $sortOrder === 'desc' ? 'desc' : 'asc');

        $utilisateurs = $query->paginate(15)->appends($request->query());

        return view('admin.superadmin.utilisateurs.index', compact('utilisateurs'));
    }

    /**
     * Formulaire de création
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
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:20',
            'password'  => 'required|string|min:8|confirmed',
            'is_active' => 'boolean',
            'photo'     => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,bmp|max:2048',
        ]);

        $data = [
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'telephone'     => $validated['telephone'] ?? null,
            'password'      => Hash::make($validated['password']),
            'is_superadmin' => true,
            'is_active'     => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $user = User::create($data);
        $user->assignRole('super_admin');

        return redirect()->route('admin.superadmin.utilisateurs.index')
            ->with('success', 'Super Administrateur créé avec succès.');
    }

    /**
     * Voir les détails
     */
    public function show($id)
    {
        $utilisateur = User::with('roles')->findOrFail($id);

        if (!$utilisateur->is_superadmin) {
            return redirect()->route('admin.superadmin.utilisateurs.index')
                ->with('error', "Cet utilisateur n'est pas un Super Administrateur.");
        }

        return view('admin.superadmin.utilisateurs.show', compact('utilisateur'));
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        $utilisateur = User::findOrFail($id);

        if (!$utilisateur->is_superadmin) {
            return redirect()->route('admin.superadmin.utilisateurs.index')
                ->with('error', "Cet utilisateur n'est pas un Super Administrateur.");
        }

        return view('admin.superadmin.utilisateurs.edit', compact('utilisateur'));
    }

    /**
     * Mettre à jour un Super Admin
     * Seuls les champs réellement remplis sont validés et mis à jour.
     * Un champ vide = on conserve la valeur existante.
     */
    public function update(Request $request, $id)
    {
        $utilisateur = User::findOrFail($id);

        if (!$utilisateur->is_superadmin) {
            return redirect()->route('admin.superadmin.utilisateurs.index')
                ->with('error', "Cet utilisateur n'est pas un Super Administrateur.");
        }

        // --- Construction des règles dynamiques ---
        $rules = [];

        // Nom : toujours présent dans le formulaire (champ pré-rempli)
        if ($request->filled('name')) {
            $rules['name'] = 'required|string|max:255';
        }

        // Email : toujours présent dans le formulaire (champ pré-rempli)
        if ($request->filled('email')) {
            $rules['email'] = ['required', 'email', Rule::unique('users')->ignore($id)];
        }

        // Téléphone : optionnel
        if ($request->filled('telephone')) {
            $rules['telephone'] = 'nullable|string|max:20';
        }

        // Mot de passe : uniquement si l'utilisateur a saisi quelque chose
        // On exclut svg et tiff qui ne sont pas supportés par le driver GD/Imagick de PHP
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        // Photo : uniquement si un fichier est envoyé
        if ($request->hasFile('photo')) {
            $rules['photo'] = 'nullable|image|mimes:jpeg,jpg,png,gif,webp,bmp|max:2048';
        }

        $validated = $request->validate($rules);

        // --- Construction des données à mettre à jour ---
        $data = [];

        if (isset($validated['name']))      $data['name']      = $validated['name'];
        if (isset($validated['email']))     $data['email']     = $validated['email'];
        if (isset($validated['telephone'])) $data['telephone'] = $validated['telephone'];
        if (isset($validated['password']))  $data['password']  = Hash::make($validated['password']);

        // Statut actif : toujours traité (checkbox = absent si décoché)
        $data['is_active'] = $request->boolean('is_active');

        // Photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($utilisateur->photo && Storage::disk('public')->exists($utilisateur->photo)) {
                Storage::disk('public')->delete($utilisateur->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        if (!empty($data)) {
            $utilisateur->update($data);
        }

        return redirect()->route('admin.superadmin.utilisateurs.index')
            ->with('success', 'Super Administrateur mis à jour avec succès.');
    }

    /**
     * Supprimer un Super Admin
     */
    public function destroy($id)
    {
        $utilisateur = User::findOrFail($id);

        if (!$utilisateur->is_superadmin) {
            return back()->with('error', "Cet utilisateur n'est pas un Super Administrateur.");
        }

        if (User::where('is_superadmin', true)->count() <= 1) {
            return back()->with('error', 'Impossible de supprimer le dernier Super Administrateur.');
        }

        if (auth()->id() == $id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Supprimer la photo associée
        if ($utilisateur->photo && Storage::disk('public')->exists($utilisateur->photo)) {
            Storage::disk('public')->delete($utilisateur->photo);
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
            return back()->with('error', "Cet utilisateur n'est pas un Super Administrateur.");
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
            return back()->with('error', "Cet utilisateur n'est pas un Super Administrateur.");
        }

        if (auth()->id() == $id) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $utilisateur->update(['is_active' => false]);

        return back()->with('success', 'Super Administrateur désactivé.');
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(Request $request, $id)
    {
        $utilisateur = User::findOrFail($id);

        if (!$utilisateur->is_superadmin) {
            return back()->with('error', "Cet utilisateur n'est pas un Super Administrateur.");
        }

        $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%'), 0, 12);

        $utilisateur->update(['password' => Hash::make($newPassword)]);

        if (app()->isLocal()) {
            return back()->with('info', "Mot de passe réinitialisé. Mot de passe temporaire : {$newPassword}");
        }

        return back()->with('success', 'Mot de passe réinitialisé. Un email a été envoyé.');
    }

    /**
     * Exporter les Super Admins
     */
    public function export(Request $request)
    {
        return back()->with('info', 'Export en cours de développement.');
    }
}