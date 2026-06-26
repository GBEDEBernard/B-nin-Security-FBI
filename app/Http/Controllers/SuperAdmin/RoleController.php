<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);

        $this->middleware('permission:view_users|manage_user_roles')->only([
            'index', 'show',
        ]);

        $this->middleware('permission:manage_user_roles')->only([
            'create', 'store', 'edit', 'update', 'destroy',
            'assignToUser', 'removeFromUser',
        ]);
    }

    public function index(Request $request)
    {
        $query = Role::with('permissions')->where('guard_name', 'web');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $roles = $query->orderBy('name')->paginate(15);

        return view('admin.superadmin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::where('guard_name', 'web')->orderBy('name')->get()->groupBy(function ($p) {
            return explode('_', $p->name)[0] ?? 'autres';
        });

        return view('admin.superadmin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('admin.superadmin.roles.index')
            ->with('success', 'Rôle "' . $role->name . '" créé avec succès.');
    }

    public function show($id)
    {
        $role = Role::with('permissions', 'users')->where('guard_name', 'web')->findOrFail($id);
        $users = User::where('is_superadmin', true)->orderBy('name')->get();

        return view('admin.superadmin.roles.show', compact('role', 'users'));
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->where('guard_name', 'web')->findOrFail($id);
        $permissions = Permission::where('guard_name', 'web')->orderBy('name')->get()->groupBy(function ($p) {
            return explode('_', $p->name)[0] ?? 'autres';
        });
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('admin.superadmin.roles.edit', compact('role', 'permissions', 'rolePermissionIds'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::where('guard_name', 'web')->findOrFail($id);

        if ($role->name === 'super_admin') {
            return back()->with('error', 'Le rôle super_admin ne peut pas être modifié.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.superadmin.roles.index')
            ->with('success', 'Rôle "' . $role->name . '" mis à jour.');
    }

    public function destroy($id)
    {
        $role = Role::where('guard_name', 'web')->findOrFail($id);

        if ($role->name === 'super_admin') {
            return back()->with('error', 'Le rôle super_admin ne peut pas être supprimé.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un rôle attribué à des utilisateurs.');
        }

        $roleName = $role->name;
        $role->delete();

        return redirect()->route('admin.superadmin.roles.index')
            ->with('success', 'Rôle "' . $roleName . '" supprimé.');
    }

    public function assignToUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $role = Role::findOrFail($validated['role_id']);

        if (!$user->is_superadmin) {
            return back()->with('error', 'Seuls les Super Admins peuvent recevoir des rôles.');
        }

        $user->assignRole($role->name);

        return back()->with('success', 'Rôle "' . $role->name . '" assigné à ' . $user->name . '.');
    }

    public function removeFromUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $role = Role::findOrFail($validated['role_id']);

        if ($role->name === 'super_admin' && $user->hasRole('super_admin')) {
            $superAdminCount = User::where('is_superadmin', true)->role('super_admin')->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Impossible de retirer le dernier rôle super_admin.');
            }
        }

        $user->removeRole($role->name);

        return back()->with('success', 'Rôle "' . $role->name . '" retiré de ' . $user->name . '.');
    }
}
