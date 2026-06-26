@extends('layouts.app')

@section('title', 'Rôles Clients - Entreprise')

@push('styles')
<style>
    .role-badge { padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500; }
    .card-custom { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .client-avatar { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; }
    .nav-tabs .nav-link { border: none; border-bottom: 2px solid transparent; color: #6c757d; font-weight: 500; padding: 0.75rem 1rem; }
    .nav-tabs .nav-link.active { border-bottom-color: #0d6efd; color: #0d6efd; background: transparent; }
    .nav-tabs .nav-link:hover { border-bottom-color: #0d6efd40; color: #0d6efd; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Gestion des Rôles</h4>
            <p class="text-muted mb-0">Gérez les rôles de vos clients</p>
        </div>
    </div>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a href="{{ route('admin.entreprise.roles.index') }}" class="nav-link">
                <i class="bi bi-people me-1"></i> Employés
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.entreprise.roles.clients') }}" class="nav-link active">
                <i class="bi bi-person-badge me-1"></i> Clients
            </a>
        </li>
    </ul>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card card-custom">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Client</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Rôles actuels</th>
                            @can('manage_user_roles')
                            <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="client-avatar bg-success bg-opacity-10 text-success">
                                        {{ strtoupper(substr($client->nomAffichage, 0, 1)) }}
                                    </div>
                                    <div class="fw-semibold">{{ $client->nomAffichage }}</div>
                                </div>
                            </td>
                            <td>{{ $client->email }}</td>
                            <td><span class="badge bg-info bg-opacity-10 text-info">{{ $client->typeLabel }}</span></td>
                            <td>
                                @forelse($client->roles as $role)
                                <span class="role-badge bg-primary bg-opacity-10 text-primary">{{ $role->name }}</span>
                                @empty
                                <span class="text-muted">Aucun rôle</span>
                                @endforelse
                            </td>
                            @can('manage_user_roles')
                            <td>
                                <a href="{{ route('admin.entreprise.roles.edit-client', $client->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-shield me-1"></i> Gérer les rôles
                                </a>
                            </td>
                            @endcan
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-people fs-3 d-block mb-2"></i>
                                Aucun client trouvé
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $clients->links() }}</div>
</div>
@endsection
