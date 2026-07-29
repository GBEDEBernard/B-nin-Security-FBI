@extends('layouts.app')

@section('title', 'Gestion des Rôles - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-shield-lock text-primary me-2"></i>
                Rôles & Permissions
            </h2>
            <p class="text-muted mb-0">Gérez les rôles et leurs permissions associées</p>
        </div>
        <a href="{{ route('admin.superadmin.roles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Nouveau Rôle
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Total Rôles</h6>
                            <h3 class="mb-0 text-primary">{{ $stats['total'] }}</h3>
                        </div>
                        <i class="bi bi-shield-check fs-1 text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Permissions Définies</h6>
                            <h3 class="mb-0 text-info">{{ $stats['permissions_total'] }}</h3>
                        </div>
                        <i class="bi bi-key fs-1 text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des Rôles</h5>
            <span class="text-muted">{{ $roles->count() }} rôle(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Rôle</th>
                            <th>Permissions</th>
                            <th>Utilisateurs</th>
                            <th>Protégé</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                        <i class="bi bi-shield-check text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $role->name }}</strong>
                                        @if($role->name === 'super_admin')
                                        <span class="badge bg-danger ms-2">Système</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $role->permissions->count() }} permission(s)</span>
                                <button type="button" class="btn btn-sm btn-link text-decoration-none" data-bs-toggle="tooltip"
                                    title="{{ $role->permissions->pluck('name')->implode(', ') }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $role->users_count ?? $role->users()->count() }}</span>
                            </td>
                            <td>
                                @if($role->name === 'super_admin')
                                <span class="badge bg-danger">Protégé</span>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.superadmin.roles.show', $role->id) }}" class="btn btn-outline-primary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($role->name !== 'super_admin')
                                    <a href="{{ route('admin.superadmin.roles.edit', $role->id) }}" class="btn btn-outline-warning" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.superadmin.roles.destroy', $role->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Supprimer ce rôle ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <button class="btn btn-outline-secondary" disabled title="Rôle système protégé">
                                        <i class="bi bi-lock"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Aucun rôle trouvé</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
