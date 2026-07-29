<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'superadmin']);
    }

    public function index()
    {
        $roles = Role::with('permissions')
            ->orderBy('name')
            ->get()
            ->map(function ($role) {
                $role->users_count = $role->users()->count();
                return $role;
            });

        $stats = [
            'total' => $roles->count(),
            'permissions_total' => Permission::count(),
        ];

        return view('admin.superadmin.roles.index', compact('roles', 'stats'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($perm) {
            $parts = explode('_', $perm->name);
            return count($parts) > 1 ? $parts[0] : 'general';
        });

        return view('admin.superadmin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'description' => 'nullable|string|max:500',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('admin.superadmin.roles.index')
            ->with('success', 'Rôle créé avec succès.');
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $users = $role->users()->orderBy('name')->paginate(15);

        return view('admin.superadmin.roles.show', compact('role', 'users'));
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($perm) {
            $parts = explode('_', $perm->name);
            return count($parts) > 1 ? $parts[0] : 'general';
        });
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('admin.superadmin.roles.edit', compact('role', 'permissions', 'rolePermissionIds'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'super_admin') {
            return back()->with('error', 'Le rôle Super Admin ne peut pas être modifié.');
        }

        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('roles')->ignore($id),
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'description' => 'nullable|string|max:500',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.superadmin.roles.index')
            ->with('success', 'Rôle mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'super_admin') {
            return back()->with('error', 'Le rôle Super Admin ne peut pas être supprimé.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Ce rôle est attribué à des utilisateurs. Veuillez d\'abord réaffecter ces utilisateurs.');
        }

        $role->delete();

        return redirect()->route('admin.superadmin.roles.index')
            ->with('success', 'Rôle supprimé avec succès.');
    }

    public function duplicate($id)
    {
        $original = Role::with('permissions')->findOrFail($id);
        $newName = $original->name . ' (copie)';
        $counter = 1;

        while (Role::where('name', $newName)->exists()) {
            $counter++;
            $newName = $original->name . ' (copie ' . $counter . ')';
        }

        $role = Role::create([
            'name' => $newName,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($original->permissions->pluck('id')->toArray());

        return redirect()->route('admin.superadmin.roles.edit', $role->id)
            ->with('success', 'Rôle dupliqué avec succès.');
    }

    public function users($id)
    {
        $role = Role::findOrFail($id);
        $users = $role->users()->orderBy('name')->paginate(20);

        return view('admin.superadmin.roles.users', compact('role', 'users'));
    }
}
