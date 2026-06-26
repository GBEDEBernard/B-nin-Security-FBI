@extends('layouts.app')

@section('title', 'Gestion des Rôles - Entreprise')

@push('styles')
<style>
    .role-badge {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .employee-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Gestion des Rôles</h4>
            <p class="text-muted mb-0">Assignez et gérez les rôles de vos employés</p>
        </div>
    </div>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a href="{{ route('admin.entreprise.roles.index') }}" class="nav-link active">
                <i class="bi bi-people me-1"></i> Employés
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.entreprise.roles.clients') }}" class="nav-link">
                <i class="bi bi-person-badge me-1"></i> Clients
            </a>
        </li>
    </ul>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card card-custom">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Employé</th>
                            <th>Email</th>
                            <th>Catégorie</th>
                            <th>Poste</th>
                            <th>Rôles actuels</th>
                            @can('manage_user_roles')
                            <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employes as $employe)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="employee-avatar bg-primary bg-opacity-10 text-primary">
                                        {{ $employe->initiales }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $employe->nomComplet }}</div>
                                        <small class="text-muted">{{ $employe->matricule }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $employe->email }}</td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">{{ $employe->categorie }}</span>
                            </td>
                            <td>{{ $employe->poste }}</td>
                            <td>
                                @forelse($employe->roles as $role)
                                <span class="role-badge bg-primary bg-opacity-10 text-primary">
                                    {{ $role->name }}
                                </span>
                                @empty
                                <span class="text-muted">Aucun rôle</span>
                                @endforelse
                            </td>
                            @can('manage_user_roles')
                            <td>
                                <a href="{{ route('admin.entreprise.roles.edit', $employe->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-shield me-1"></i> Gérer les rôles
                                </a>
                            </td>
                            @endcan
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-people fs-3 d-block mb-2"></i>
                                Aucun employé trouvé
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $employes->links() }}
    </div>
</div>
@endsection
