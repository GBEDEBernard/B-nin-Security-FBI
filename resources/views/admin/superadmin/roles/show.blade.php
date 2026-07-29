@extends('layouts.app')

@section('title', 'Détail Rôle - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-shield-check text-primary me-2"></i>
                {{ $role->name }}
            </h2>
            <p class="text-muted mb-0">Détails du rôle et ses permissions</p>
        </div>
        <div>
            <a href="{{ route('admin.superadmin.roles.edit', $role->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i> Modifier
            </a>
            <a href="{{ route('admin.superadmin.roles.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th class="text-muted">Nom</th>
                            <td><strong>{{ $role->name }}</strong></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Guard</th>
                            <td><code>{{ $role->guard_name }}</code></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Permissions</th>
                            <td><span class="badge bg-info">{{ $role->permissions->count() }}</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Utilisateurs</th>
                            <td><span class="badge bg-secondary">{{ $users->total() }}</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Créé le</th>
                            <td>{{ $role->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Protégé</th>
                            <td>
                                @if($role->name === 'super_admin')
                                <span class="badge bg-danger">Oui</span>
                                @else
                                <span class="badge bg-success">Non</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Actions rapides</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.superadmin.roles.duplicate', $role->id) }}" method="POST" class="d-inline w-100">
                        @csrf
                        <button type="submit" class="btn btn-outline-info w-100 mb-2">
                            <i class="bi bi-files me-1"></i> Dupliquer
                        </button>
                    </form>
                    <a href="{{ route('admin.superadmin.roles.users', $role->id) }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-people me-1"></i> {{ $users->total() }} utilisateur(s)
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Permissions Attribuées</h5>
                </div>
                <div class="card-body">
                    @if($role->permissions->count() > 0)
                    <div class="row">
                        @php
                        $grouped = $role->permissions->groupBy(function($perm) {
                            $parts = explode('_', $perm->name);
                            return count($parts) > 1 ? $parts[0] : 'general';
                        });
                        @endphp
                        @foreach($grouped as $group => $groupPerms)
                        <div class="col-md-6 mb-3">
                            <h6 class="text-uppercase text-muted fw-bold border-bottom pb-1">
                                <i class="bi bi-folder me-1"></i> {{ ucfirst($group) }}
                                <span class="badge bg-info ms-1">{{ $groupPerms->count() }}</span>
                            </h6>
                            <ul class="list-unstyled">
                                @foreach($groupPerms as $permission)
                                <li class="mb-1">
                                    <span class="badge bg-success bg-opacity-10 text-success p-2 w-100 text-start">
                                        <i class="bi bi-check-lg me-1"></i>
                                        <code>{{ $permission->name }}</code>
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center py-4">Aucune permission attribuée à ce rôle</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Utilisateurs avec ce rôle</h5>
                    <span class="text-muted">{{ $users->total() }} utilisateur(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->is_active)
                                        <span class="badge bg-success">Actif</span>
                                        @else
                                        <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Aucun utilisateur avec ce rôle</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($users->hasPages())
                <div class="card-footer">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
