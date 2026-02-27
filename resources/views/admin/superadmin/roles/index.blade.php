@extends('layouts.app')

@section('title', 'Gestion des Rôles - Super Admin')

@push('styles')
<style>
    /* Stats Cards */
    .stats-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    /* Avatar Styles */
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        flex-shrink: 0;
    }

    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }

    .avatar-lg {
        width: 56px;
        height: 56px;
        font-size: 18px;
    }

    /* Role Badges */
    .role-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: 500;
        margin: 1px;
        display: inline-block;
    }

    .role-super_admin {
        background: #dc3545;
        color: white;
    }

    .role-general_director {
        background: #6f42c1;
        color: white;
    }

    .role-deputy_director {
        background: #e83e8c;
        color: white;
    }

    .role-supervisor {
        background: #0dcaf0;
        color: white;
    }

    .role-controller {
        background: #20c997;
        color: white;
    }

    .role-agent {
        background: #198754;
        color: white;
    }

    .role-client_individual {
        background: #ffc107;
        color: #000;
    }

    .role-client_company {
        background: #fd7e14;
        color: white;
    }

    /* Status Badge */
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }

    .status-active {
        background-color: #198754;
    }

    .status-inactive {
        background-color: #dc3545;
    }

    /* Table Styles */
    .roles-table th {
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
    }

    .roles-table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .roles-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Search & Filter */
    .search-box {
        position: relative;
    }

    .search-box .bi-search {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .search-box input {
        padding-left: 40px;
        border-radius: 8px;
    }

    /* Tabs */
    .custom-tabs {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 1.5rem;
    }

    .custom-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        position: relative;
    }

    .custom-tabs .nav-link:hover {
        color: #198754;
        border: none;
    }

    .custom-tabs .nav-link.active {
        color: #198754;
        background: transparent;
        border: none;
    }

    .custom-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: #198754;
    }

    .custom-tabs .nav-link .badge {
        font-size: 0.65rem;
        margin-left: 6px;
    }

    /* Action Buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .btn-action:hover {
        transform: scale(1.1);
    }

    /* Empty State */
    .empty-state {
        padding: 3rem;
        text-align: center;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .empty-icon i {
        font-size: 2rem;
        color: #adb5bd;
    }

    /* Pagination */
    .pagination-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
    }

    .page-info {
        color: #6c757d;
        font-size: 0.875rem;
    }

    /* Filter Card */
    .filter-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Entreprise Badge */
    .entreprise-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        background: #e9ecef;
        border-radius: 4px;
        color: #495057;
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeIn 0.3s ease forwards;
    }

    /* Dropdown Menu */
    .role-dropdown {
        min-width: 200px;
        padding: 0.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .role-dropdown .dropdown-item {
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }

    .role-dropdown .dropdown-item:hover {
        background: #f8f9fa;
    }

    /* Checkbox custom */
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-shield-lock-fill me-2"></i>Gestion des Rôles
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Rôles & Permissions</li>
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

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Total Utilisateurs</h6>
                                <h3 class="mb-0">{{ $stats['total_users'] + $stats['total_employes'] }}</h3>
                            </div>
                            <div class="stats-icon bg-white bg-opacity-10">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card" style="background: #6f42c1; color: white;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Super Admins</h6>
                                <h3 class="mb-0">{{ $stats['total_superadmins'] }}</h3>
                            </div>
                            <div class="stats-icon bg-white bg-opacity-10">
                                <i class="bi bi-shield-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card" style="background: #198754; color: white;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Agents</h6>
                                <h3 class="mb-0">{{ $stats['total_agents'] }}</h3>
                            </div>
                            <div class="stats-icon bg-white bg-opacity-10">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card" style="background: #0dcaf0; color: white;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Rôles Disponibles</h6>
                                <h3 class="mb-0">{{ $allRoles->count() }}</h3>
                            </div>
                            <div class="stats-icon bg-white bg-opacity-10">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card filter-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.superadmin.roles.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" class="form-control"
                                placeholder="Rechercher un utilisateur..."
                                value="{{ $search }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="entreprise" class="form-select">
                            <option value="">Toutes les entreprises</option>
                            @foreach($entreprises as $entreprise)
                            <option value="{{ $entreprise->id }}" {{ $entrepriseFilter == $entreprise->id ? 'selected' : '' }}>
                                {{ $entreprise->nom_entreprise ?? $entreprise->nom }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="sort" class="form-select">
                            <option value="name">Trier par nom</option>
                            <option value="created_at">Date création</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="{{ route('admin.superadmin.roles.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Onglets -->
        <ul class="nav custom-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $type === 'all' ? 'active' : '' }}"
                    href="{{ route('admin.superadmin.roles.index', array_merge(request()->except('type'), ['type' => 'all'])) }}">
                    <i class="bi bi-grid me-1"></i> Tous
                    <span class="badge bg-secondary">{{ $stats['total_users'] + $stats['total_employes'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type === 'users' ? 'active' : '' }}"
                    href="{{ route('admin.superadmin.roles.index', array_merge(request()->except('type'), ['type' => 'users'])) }}">
                    <i class="bi bi-people me-1"></i> Utilisateurs
                    <span class="badge bg-primary">{{ $stats['total_users'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type === 'employes' ? 'active' : '' }}"
                    href="{{ route('admin.superadmin.roles.index', array_merge(request()->except('type'), ['type' => 'employes'])) }}">
                    <i class="bi bi-person-badge me-1"></i> Employés
                    <span class="badge bg-success">{{ $stats['total_employes'] }}</span>
                </a>
            </li>
        </ul>

        <!-- Liste des utilisateurs -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    @switch($type)
                    @case('users') Utilisateurs @break
                    @case('employes') Employés @break
                    @default Tous les utilisateurs
                    @endswitch
                </h5>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear me-1"></i> Actions
                    </button>
                    <ul class="dropdown-menu role-dropdown">
                        <li><a class="dropdown-item" href="{{ route('admin.superadmin.roles.roles-list') }}">
                                <i class="bi bi-diagram-3 me-2"></i> Liste des rôles
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('admin.superadmin.utilisateurs.create') }}">
                                <i class="bi bi-person-plus me-2"></i> Nouvel utilisateur
                            </a></li>
                    </ul>
                </div>
            </div>

            <div class="card-body p-0">
                @if($users->isEmpty() && $employes->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5>Aucun résultat trouvé</h5>
                    <p class="text-muted">
                        @if($search)
                        Aucun utilisateur ne correspond à "{{ $search }}"
                        @else
                        Aucun utilisateur n'a été trouvé
                        @endif
                    </p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table roles-table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Utilisateur</th>
                                <th>Type</th>
                                <th>Entreprise</th>
                                <th>Rôles assignés</th>
                                <th>Statut</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Users -->
                            @foreach($users as $user)
                            <tr class="fade-in">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if($user->photo)
                                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo"
                                            class="user-avatar me-3" style="object-fit: cover;">
                                        @else
                                        <div class="user-avatar bg-primary text-white me-3">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->is_superadmin)
                                    <span class="badge bg-danger">Super Admin</span>
                                    @else
                                    <span class="badge bg-secondary">Utilisateur</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->entreprise)
                                    <span class="entreprise-badge">{{ $user->entreprise->nom_entreprise ?? $user->entreprise->nom }}</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @forelse($user->roles as $role)
                                    <span class="role-badge role-{{ $role->name }}">
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                    @empty
                                    <span class="text-muted">Aucun rôle</span>
                                    @endforelse
                                </td>
                                <td>
                                    <span class="d-flex align-items-center">
                                        <span class="status-dot {{ $user->is_active ? 'status-active' : 'status-inactive' }}"></span>
                                        {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group" role="group">
                                        <!-- Voir -->
                                        <a href="{{ route('admin.superadmin.roles.show', ['id' => $user->id, 'type' => 'user']) }}"
                                            class="btn btn-outline-primary btn-action" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <!-- Modifier les rôles -->
                                        <a href="{{ route('admin.superadmin.roles.edit', ['id' => $user->id, 'type' => 'user']) }}"
                                            class="btn btn-outline-warning btn-action" title="Modifier les rôles">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <!-- Menu déroulant pour les rôles rapides -->
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-action dropdown-toggle"
                                                type="button" data-bs-toggle="dropdown" title="Actions rapides">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu role-dropdown">
                                                @foreach($allRoles as $role)
                                                @if(!$user->hasRole($role->name))
                                                <li>
                                                    <form action="{{ route('admin.superadmin.roles.assign-role', ['id' => $user->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="role" value="{{ $role->name }}">
                                                        <input type="hidden" name="type" value="user">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bi bi-plus-circle me-2"></i> Ajouter {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @else
                                                <li>
                                                    <form action="{{ route('admin.superadmin.roles.remove-role', ['id' => $user->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="role" value="{{ $role->name }}">
                                                        <input type="hidden" name="type" value="user">
                                                        <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Retirer le rôle {{ $role->name }}?')">
                                                            <i class="bi bi-dash-circle me-2"></i> Retirer {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                            <!-- Employés -->
                            @foreach($employes as $employe)
                            <tr class="fade-in">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if($employe->photo)
                                        <img src="{{ asset('storage/' . $employe->photo) }}" alt="Photo"
                                            class="user-avatar me-3" style="object-fit: cover;">
                                        @else
                                        <div class="user-avatar bg-success text-white me-3">
                                            {{ strtoupper(substr($employe->prenoms, 0, 1) . substr($employe->nom, 0, 1)) }}
                                        </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $employe->prenoms }} {{ $employe->nom }}</div>
                                            <small class="text-muted">{{ $employe->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ ucfirst($employe->categorie ?? 'employé') }}</span>
                                </td>
                                <td>
                                    @if($employe->entreprise)
                                    <span class="entreprise-badge">{{ $employe->entreprise->nom_entreprise ?? $employe->entreprise->nom }}</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @forelse($employe->roles as $role)
                                    <span class="role-badge role-{{ $role->name }}">
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                    @empty
                                    <span class="text-muted">Aucun rôle</span>
                                    @endforelse
                                </td>
                                <td>
                                    <span class="d-flex align-items-center">
                                        <span class="status-dot {{ $employe->est_actif ? 'status-active' : 'status-inactive' }}"></span>
                                        {{ $employe->est_actif ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group" role="group">
                                        <!-- Voir -->
                                        <a href="{{ route('admin.superadmin.roles.show', ['id' => $employe->id, 'type' => 'employe']) }}"
                                            class="btn btn-outline-primary btn-action" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <!-- Modifier les rôles -->
                                        <a href="{{ route('admin.superadmin.roles.edit', ['id' => $employe->id, 'type' => 'employe']) }}"
                                            class="btn btn-outline-warning btn-action" title="Modifier les rôles">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <!-- Menu déroulant pour les rôles rapides -->
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-action dropdown-toggle"
                                                type="button" data-bs-toggle="dropdown" title="Actions rapides">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu role-dropdown">
                                                @foreach($allRoles as $role)
                                                @if(!$employe->hasRole($role->name))
                                                <li>
                                                    <form action="{{ route('admin.superadmin.roles.assign-role', ['id' => $employe->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="role" value="{{ $role->name }}">
                                                        <input type="hidden" name="type" value="employe">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bi bi-plus-circle me-2"></i> Ajouter {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @else
                                                <li>
                                                    <form action="{{ route('admin.superadmin.roles.remove-role', ['id' => $employe->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="role" value="{{ $role->name }}">
                                                        <input type="hidden" name="type" value="employe">
                                                        <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Retirer le rôle {{ $role->name }}?')">
                                                            <i class="bi bi-dash-circle me-2"></i> Retirer {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($users->hasPages() || $employes->hasPages())
            <div class="card-footer">
                <div class="pagination-wrap">
                    <div class="page-info">
                        @if($type === 'all')
                        Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }} (utilisateurs)
                        et {{ $employes->firstItem() ?? 0 }} à {{ $employes->lastItem() ?? 0 }} (employés)
                        @elseif($type === 'users')
                        Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }}
                        sur {{ $users->total() }} résultat(s)
                        @else
                        Affichage de {{ $employes->firstItem() ?? 0 }} à {{ $employes->lastItem() ?? 0 }}
                        sur {{ $employes->total() }} résultat(s)
                        @endif
                    </div>
                    @if($type === 'all')
                    <div>
                        {{ $users->links('pagination::bootstrap-5') }}
                        {{ $employes->links('pagination::bootstrap-5') }}
                    </div>
                    @elseif($type === 'users')
                    {{ $users->links('pagination::bootstrap-5') }}
                    @else
                    {{ $employes->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<!--end::App Content-->
@endsection