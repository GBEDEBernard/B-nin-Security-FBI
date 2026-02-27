@extends('layouts.app')

@section('title', 'Super Administrateurs - Super Admin')

@push('styles')
<style>
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }

    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }

    .status-badge {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-active {
        background-color: #198754;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
    }

    .status-inactive {
        background-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
    }

    .table-action-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .table-action-btn:hover {
        transform: translateY(-1px);
    }

    .filter-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .search-input {
        border-radius: 8px;
        padding-left: 40px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }

    .search-input:focus {
        background: #fff;
        border-color: #198754;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .pagination-wrap {
        display: flex;
        justify-content: -between;
        align-items: center;
        padding: 1rem 0;
    }

    .page-info {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .empty-state {
        padding: 3rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .empty-state-icon i {
        font-size: 2rem;
        color: #adb5bd;
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-people-fill me-2"></i>Super Administrateurs</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Utilisateurs</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        <!-- Messages de session -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Filtres -->
        <div class="card filter-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.superadmin.utilisateurs.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <div class="position-relative">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" name="search" class="form-control search-input"
                                placeholder="Rechercher par nom ou email..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ request('statut') == 'active' ? 'selected' : '' }}>Actifs</option>
                            <option value="inactive" {{ request('statut') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="sort" class="form-select">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom</option>
                            <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date création</option>
                            <option value="last_login_at" {{ request('sort') == 'last_login_at' ? 'selected' : '' }}>Dernière connexion</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="order" class="form-select">
                            <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                            <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des utilisateurs -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul me-2"></i>Liste des Super Administrateurs
                    <span class="badge bg-secondary ms-2">{{ $utilisateurs->total() }}</span>
                </h5>
                <a href="{{ route('admin.superadmin.utilisateurs.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Nouveau Super Admin
                </a>
            </div>

            <div class="card-body p-0">
                @if($utilisateurs->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-person-x"></i>
                    </div>
                    <h5>Aucun Super Administrateur trouvé</h5>
                    <p class="text-muted mb-3">
                        @if(request()->has('search') || request()->has('statut'))
                        Aucun résultat ne correspond à vos critères de recherche.
                        @else
                        Cliquez sur le bouton ci-dessous pour créer le premier Super Administrateur.
                        @endif
                    </p>
                    <a href="{{ route('admin.superadmin.utilisateurs.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Créer un Super Admin
                    </a>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Utilisateur</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Statut</th>
                                <th>Dernière connexion</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($utilisateurs as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if($user->photo)
                                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de {{ $user->name }}"
                                            class="user-avatar me-3" style="object-fit: cover;">
                                        @else
                                        <div class="user-avatar bg-primary text-white me-3">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                            <small class="text-muted">Créé le {{ $user->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                        {{ $user->email }}
                                    </a>
                                </td>
                                <td>{{ $user->telephone ?? '-' }}</td>
                                <td>
                                    <span class="d-flex align-items-center">
                                        <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }} me-2"></span>
                                        {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->last_login_at)
                                    <span title="{{ $user->last_login_at }}">
                                        {{ $user->last_login_at->diffForHumans() }}
                                    </span>
                                    @else
                                    <span class="text-muted">Jamais</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group" role="group">
                                        <!-- Voir -->
                                        <a href="{{ route('admin.superadmin.utilisateurs.show', $user->id) }}"
                                            class="btn btn-outline-primary btn-sm table-action-btn"
                                            title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <!-- Modifier -->
                                        <a href="{{ route('admin.superadmin.utilisateurs.edit', $user->id) }}"
                                            class="btn btn-outline-warning btn-sm table-action-btn"
                                            title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <!-- Reset Password -->
                                        <form action="{{ route('admin.superadmin.utilisateurs.reset-password', $user->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-info btn-sm table-action-btn"
                                                title="Réinitialiser le mot de passe"
                                                onclick="return confirm('Réinitialiser le mot de passe de {{ $user->name }} ?')">
                                                <i class="bi bi-key"></i>
                                            </button>
                                        </form>

                                        <!-- Activer/Désactiver -->
                                        @if($user->id !== auth()->id())
                                        @if($user->is_active)
                                        <form action="{{ route('admin.superadmin.utilisateurs.deactivate', $user->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary btn-sm table-action-btn"
                                                title="Désactiver"
                                                onclick="return confirm('Désactiver {{ $user->name }} ?')">
                                                <i class="bi bi-person-dash"></i>
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('admin.superadmin.utilisateurs.activate', $user->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm table-action-btn"
                                                title="Activer"
                                                onclick="return confirm('Activer {{ $user->name }} ?')">
                                                <i class="bi bi-person-check"></i>
                                            </button>
                                        </form>
                                        @endif

                                        <!-- Supprimer -->
                                        @if($utilisateurs->count() > 1)
                                        <form action="{{ route('admin.superadmin.utilisateurs.destroy', $user->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm table-action-btn"
                                                title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer {{ $user->name }} ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @else
                                        <span class="btn btn-outline-secondary btn-sm table-action-btn disabled"
                                            title="C'est vous">
                                            <i class="bi bi-person-badge"></i>
                                        </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            @if($utilisateurs->hasPages())
            <div class="card-footer">
                <div class="pagination-wrap">
                    <div class="page-info">
                        Affichage de {{ $utilisateurs->firstItem() }} à {{ $utilisateurs->lastItem() }}
                        sur {{ $utilisateurs->total() }} résultat(s)
                    </div>
                    {{ $utilisateurs->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<!--end::App Content-->
@endsection