<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employe;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    /**
     * Liste de tous les utilisateurs et employés avec leurs rôles
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $search = $request->get('search', '');
        $entrepriseFilter = $request->get('entreprise', '');

        // Liste des entreprises pour le filtre
        $entreprises = Entreprise::orderBy('nom_entreprise')->get();

        // Requête pour les Users (SuperAdmins et utilisateurs d'entreprises)
        $usersQuery = User::with('roles')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        // Requête pour les Employés
        $employesQuery = Employe::with(['roles', 'entreprise'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenoms', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($entrepriseFilter, function ($query) use ($entrepriseFilter) {
                $query->where('entreprise_id', $entrepriseFilter);
            });

        // Récupérer selon le type demandé
        if ($type === 'users') {
            $users = $usersQuery->orderBy('name')->paginate(15)->appends($request->query());
            $employes = collect([]);
        } elseif ($type === 'employes') {
            $users = collect([]);
            $employes = $employesQuery->orderBy('nom')->orderBy('prenoms')->paginate(15)->appends($request->query());
        } else {
            // ALL - paginer les deux séparément
            $users = $usersQuery->orderBy('name')->paginate(10)->appends($request->query());
            $employes = $employesQuery->orderBy('nom')->orderBy('prenoms')->paginate(10)->appends($request->query());
        }

        // Tous les rôles disponibles
        $allRoles = Role::orderBy('name')->get();

        // Statistiques
        $stats = [
            'total_users' => User::count(),
            'total_employes' => Employe::count(),
            'total_superadmins' => User::where('is_superadmin', true)->count(),
            'total_agents' => Employe::where('categorie', 'agent')->count(),
        ];

        return view('admin.superadmin.roles.index', compact(
            'users',
            'employes',
            'allRoles',
            'entreprises',
            'stats',
            'type',
            'search',
            'entrepriseFilter'
        ));
    }

    /**
     * Voir les détails d'un utilisateur ou employé
     */
    public function show(Request $request, $id)
    {
        $type = $request->get('type', 'user');

        if ($type === 'employe') {
            $model = Employe::with(['roles', 'entreprise'])->findOrFail($id);
        } else {
            $model = User::with('roles')->findOrFail($id);
        }

        // Historique des rôles (via model_has_roles)
        $roleHistory = DB::table('model_has_roles')
            ->where('model_type', $type === 'employe' ? Employe::class : User::class)
            ->where('model_id', $id)
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('roles.name', 'roles.id', 'model_has_roles.*')
            ->get();

        return view('admin.superadmin.roles.show', compact('model', 'type', 'roleHistory'));
    }

    /**
     * Formulaire de modification des rôles
     */
    public function edit(Request $request, $id)
    {
        $type = $request->get('type', 'user');

        if ($type === 'employe') {
            $model = Employe::with(['roles', 'entreprise'])->findOrFail($id);
        } else {
            $model = User::with('roles')->findOrFail($id);
        }

        // Tous les rôles disponibles
        $allRoles = Role::orderBy('name')->get();

        // Rôles actuels du model
        $currentRoles = $model->roles->pluck('name')->toArray();

        // Permissions du modèle
        $currentPermissions = $model->getAllPermissions()->pluck('name')->toArray();
        $allPermissions = Permission::orderBy('name')->get();

        return view('admin.superadmin.roles.edit', compact(
            'model',
            'type',
            'allRoles',
            'currentRoles',
            'allPermissions',
            'currentPermissions'
        ));
    }

    /**
     * Mettre à jour les rôles et permissions
     */
    public function update(Request $request, $id)
    {
        $type = $request->get('type', 'user');

        if ($type === 'employe') {
            $model = Employe::findOrFail($id);
        } else {
            $model = User::findOrFail($id);
        }

        // Validation
        $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        // Synchroniser les rôles
        if ($request->has('roles')) {
            $model->syncRoles($request->roles);
        } else {
            $model->syncRoles([]);
        }

        // Synchroniser les permissions individuelles
        if ($request->has('permissions')) {
            $model->syncPermissions($request->permissions);
        }

        $modelName = $type === 'employe'
            ? $model->prenoms . ' ' . $model->nom
            : $model->name;

        return redirect()
            ->route('admin.superadmin.roles.index')
            ->with('success', "Les rôles et permissions de {$modelName} ont été mis à jour avec succès.");
    }

    /**
     * Assigner un rôle spécifique (API/AJAX)
     */
    public function assignRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
            'type' => 'required|in:user,employe',
        ]);

        if ($request->type === 'employe') {
            $model = Employe::findOrFail($id);
        } else {
            $model = User::findOrFail($id);
        }

        $model->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => "Rôle '{$request->role}' assigné avec succès.",
        ]);
    }

    /**
     * Retirer un rôle spécifique (API/AJAX)
     */
    public function removeRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
            'type' => 'required|in:user,employe',
        ]);

        if ($request->type === 'employe') {
            $model = Employe::findOrFail($id);
        } else {
            $model = User::findOrFail($id);
        }

        // Empêcher la suppression du dernier super_admin
        if ($request->type === 'user' && $request->role === 'super_admin') {
            $superAdminCount = User::whereHas('roles', function ($q) {
                $q->where('name', 'super_admin');
            })->count();

            if ($superAdminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => "Impossible de retirer le dernier Super Admin.",
                ], 422);
            }
        }

        $model->removeRole($request->role);

        return response()->json([
            'success' => true,
            'message' => "Rôle '{$request->role}' retiré avec succès.",
        ]);
    }

    /**
     * Liste des rôles disponibles
     */
    public function roles()
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            return explode('_', $permission->name)[0];
        });

        return view('admin.superadmin.roles.roles-list', compact('roles', 'permissions'));
    }

    /**
     * Créer un nouveau rôle
     */
    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return back()->with('success', "Rôle '{$role->name}' créé avec succès.");
    }

    /**
     * Supprimer un rôle
     */
    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);

        // Vérifier si le rôle est utilisé
        $usersCount = DB::table('model_has_roles')->where('role_id', $id)->count();

        if ($usersCount > 0) {
            return back()->with('error', "Impossible de supprimer le rôle '{$role->name}' car il est assigné à {$usersCount} utilisateur(s).");
        }

        $role->delete();

        return back()->with('success', "Rôle '{$role->name}' supprimé avec succès.");
    }

    /**
     * Rechercher un utilisateur ou employé
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');

        if (strlen($search) < 2) {
            return response()->json(['results' => []]);
        }

        // Rechercher dans les users
        $users = User::where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->limit(5)
            ->get(['id', 'name', 'email'])
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name . ' (' . $user->email . ')',
                    'type' => 'user',
                ];
            });

        // Rechercher dans les employés
        $employes = Employe::where('nom', 'like', "%{$search}%")
            ->orWhere('prenoms', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->limit(5)
            ->get(['id', 'nom', 'prenoms', 'email'])
            ->map(function ($employe) {
                return [
                    'id' => $employe->id,
                    'text' => $employe->prenoms . ' ' . $employe->nom . ' (' . ($employe->email ?? 'N/A') . ')',
                    'type' => 'employe',
                ];
            });

        return response()->json([
            'results' => $users->concat($employes),
        ]);
    }
}
