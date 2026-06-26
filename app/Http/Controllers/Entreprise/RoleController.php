<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Client;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'entreprise']);

        $this->middleware('permission:manage_user_roles|view_users|edit_agents|edit_managers|edit_supervisors|edit_controllers')->only([
            'index', 'indexClients',
        ]);

        $this->middleware('permission:manage_user_roles')->only([
            'edit', 'update', 'editClient', 'updateClient',
        ]);
    }

    public function index(Request $request)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $employes = Employe::where('entreprise_id', $entrepriseId)
            ->with('roles')
            ->orderBy('nom')
            ->paginate(20);

        $roles = Role::where('guard_name', 'web')
            ->whereIn('name', ['general_director', 'deputy_director', 'operations_director', 'supervisor', 'controller', 'agent'])
            ->orderBy('name')
            ->get();

        return view('admin.entreprise.roles.index', compact('employes', 'roles'));
    }

    public function indexClients(Request $request)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $clients = Client::where('entreprise_id', $entrepriseId)
            ->with('roles')
            ->orderBy('nom')
            ->paginate(20);

        $roles = Role::where('guard_name', 'web')
            ->whereIn('name', ['client_individual', 'client_company'])
            ->orderBy('name')
            ->get();

        return view('admin.entreprise.roles.clients', compact('clients', 'roles'));
    }

    public function edit($id)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $employe = Employe::with('roles')
            ->where('entreprise_id', $entrepriseId)
            ->findOrFail($id);

        $roles = Role::where('guard_name', 'web')
            ->whereIn('name', ['general_director', 'deputy_director', 'operations_director', 'supervisor', 'controller', 'agent'])
            ->orderBy('name')
            ->get();

        return view('admin.entreprise.roles.edit', compact('employe', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $employe = Employe::where('entreprise_id', $entrepriseId)->findOrFail($id);

        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $roleIds = $validated['roles'] ?? [];
        $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

        $employe->syncRoles($roles);

        return redirect()->route('admin.entreprise.roles.index')
            ->with('success', 'Rôles de ' . $employe->nomComplet . ' mis à jour.');
    }

    public function editClient($id)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $client = Client::with('roles')
            ->where('entreprise_id', $entrepriseId)
            ->findOrFail($id);

        $roles = Role::where('guard_name', 'web')
            ->whereIn('name', ['client_individual', 'client_company'])
            ->orderBy('name')
            ->get();

        return view('admin.entreprise.roles.edit-client', compact('client', 'roles'));
    }

    public function updateClient(Request $request, $id)
    {
        $entrepriseId = Auth::user()->entreprise_id;

        $client = Client::where('entreprise_id', $entrepriseId)->findOrFail($id);

        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $roleIds = $validated['roles'] ?? [];
        $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

        $client->syncRoles($roles);

        return redirect()->route('admin.entreprise.roles.clients')
            ->with('success', 'Rôles de ' . $client->nomAffichage . ' mis à jour.');
    }
}
