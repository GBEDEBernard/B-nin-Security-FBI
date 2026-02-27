@extends('layouts.app')

@section('title', 'Détails des Rôles - Super Admin')

@push('styles')
<style>
    /* Avatar Styles */
    .user-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 32px;
        flex-shrink: 0;
    }

    /* Role Badges */
    .role-badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        font-weight: 500;
        margin: 3px;
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

    /* Info Cards */
    .info-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .info-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    /* Permission List */
    .permission-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .permission-list li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
    }

    .permission-list li:last-child {
        border-bottom: none;
    }

    .permission-list li i {
        width: 24px;
        color: #198754;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -26px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #198754;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #198754;
    }

    .timeline-date {
        font-size: 0.75rem;
        color: #6c757d;
    }

    /* Back Button */
    .back-btn {
        transition: all 0.2s;
    }

    .back-btn:hover {
        transform: translateX(-3px);
    }

    /* Status Badge */
    .status-badge-custom {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .status-active {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .status-inactive {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
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
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-shield-lock-fill me-2"></i>Détails des Rôles
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.roles.index') }}">Rôles</a></li>
                    <li class="breadcrumb-item active">Détails</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        <!-- Back Button -->
        <a href="{{ route('admin.superadmin.roles.index') }}" class="btn btn-outline-secondary mb-3 back-btn">
            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
        </a>

        <div class="row">
            <!-- Profil de l'utilisateur -->
            <div class="col-md-4">
                <div class="card info-card mb-4">
                    <div class="card-body text-center">
                        @if($type === 'employe')
                        @if($model->photo)
                        <img src="{{ asset('storage/' . $model->photo) }}" alt="Photo"
                            class="user-avatar mb-3" style="object-fit: cover;">
                        @else
                        <div class="user-avatar bg-success text-white mb-3 mx-auto">
                            {{ strtoupper(substr($model->prenoms, 0, 1) . substr($model->nom, 0, 1)) }}
                        </div>
                        @endif
                        <h4>{{ $model->prenoms }} {{ $model->nom }}</h4>
                        <p class="text-muted mb-1">{{ $model->email ?? 'N/A' }}</p>
                        <p class="text-muted mb-2">{{ $model->telephone ?? 'N/A' }}</p>

                        <div class="mb-3">
                            <span class="badge bg-success">{{ ucfirst($model->categorie ?? 'Employé') }}</span>
                            <span class="badge bg-secondary">{{ ucfirst($model->poste ?? 'N/A') }}</span>
                        </div>

                        @if($model->entreprise)
                        <div class="mt-2">
                            <i class="bi bi-building me-1"></i>
                            {{ $model->entreprise->nom_entreprise ?? $model->entreprise->nom }}
                        </div>
                        @endif
                        @else
                        @if($model->photo)
                        <img src="{{ asset('storage/' . $model->photo) }}" alt="Photo"
                            class="user-avatar mb-3" style="object-fit: cover;">
                        @else
                        <div class="user-avatar bg-primary text-white mb-3 mx-auto">
                            {{ strtoupper(substr($model->name, 0, 2)) }}
                        </div>
                        @endif
                        <h4>{{ $model->name }}</h4>
                        <p class="text-muted mb-1">{{ $model->email }}</p>

                        <div class="mb-3">
                            @if($model->is_superadmin)
                            <span class="badge bg-danger">Super Admin</span>
                            @else
                            <span class="badge bg-secondary">Utilisateur</span>
                            @endif
                        </div>

                        @if($model->entreprise)
                        <div class="mt-2">
                            <i class="bi bi-building me-1"></i>
                            {{ $model->entreprise->nom_entreprise ?? $model->entreprise->nom }}
                        </div>
                        @endif
                        @endif

                        <hr>

                        <!-- Statut -->
                        <div class="mb-3">
                            <span class="status-badge-custom {{ $model->is_active || (isset($model->est_actif) && $model->est_actif) ? 'status-active' : 'status-inactive' }}">
                                <i class="bi {{ $model->is_active || (isset($model->est_actif) && $model->est_actif) ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                                {{ $model->is_active || (isset($model->est_actif) && $model->est_actif) ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>

                        <!-- Dates -->
                        <div class="text-start">
                            <p class="mb-1">
                                <i class="bi bi-calendar-plus me-2"></i>
                                <strong>Créé le:</strong> {{ $model->created_at->format('d/m/Y à H:i') }}
                            </p>
                            @if($model->last_login_at)
                            <p class="mb-0">
                                <i class="bi bi-clock-history me-2"></i>
                                <strong>Dernière connexion:</strong> {{ $model->last_login_at->format('d/m/Y à H:i') }}
                            </p>
                            @else
                            <p class="mb-0 text-muted">
                                <i class="bi bi-clock-history me-2"></i>
                                <strong>Dernière connexion:</strong> Jamais
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="card info-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightning-fill me-2"></i>Actions rapides
                        </h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('admin.superadmin.roles.edit', ['id' => $model->id, 'type' => $type]) }}"
                            class="btn btn-warning w-100 mb-2">
                            <i class="bi bi-pencil-square me-1"></i> Modifier les rôles
                        </a>

                        @if($type === 'employe' && $model->entreprise)
                        <a href="{{ route('admin.superadmin.entreprises.show', $model->entreprise->id) }}"
                            class="btn btn-outline-primary w-100 mb-2">
                            <i class="bi bi-building me-1"></i> Voir l'entreprise
                        </a>
                        @endif

                        @if($type === 'user' && !$model->is_superadmin)
                        <a href="{{ route('admin.superadmin.utilisateurs.show', $model->id) }}"
                            class="btn btn-outline-secondary w-100">
                            <i class="bi bi-person me-1"></i> Profil complet
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Rôles et Permissions -->
            <div class="col-md-8">
                <!-- Rôles actuels -->
                <div class="card info-card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-diagram-3-fill me-2"></i>Rôles assignés
                            <span class="badge bg-secondary ms-2">{{ $model->roles->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($model->roles as $role)
                        <div class="d-flex align-items-center justify-content-between p-3 mb-2"
                            style="background: #f8f9fa; border-radius: 8px; border-left: 4px solid #198754;">
                            <div>
                                <span class="role-badge role-{{ $role->name }}">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </span>
                                @if($role->permissions->count() > 0)
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-key me-1"></i>{{ $role->permissions->count() }} permission(s)
                                </small>
                                @endif
                            </div>
                            <form action="{{ route('admin.superadmin.roles.remove-role', ['id' => $model->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="role" value="{{ $role->name }}">
                                <input type="hidden" name="type" value="{{ $type }}">
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Retirer le rôle {{ $role->name }}?')">
                                    <i class="bi bi-dash-circle"></i>
                                </button>
                            </form>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="bi bi-person-x text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">Aucun rôle assigné</p>
                            <a href="{{ route('admin.superadmin.roles.edit', ['id' => $model->id, 'type' => $type]) }}"
                                class="btn btn-success btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> Assigner un rôle
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Permissions -->
                <div class="card info-card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-shield-check me-2"></i>Permissions actives
                            <span class="badge bg-success ms-2">{{ $model->getAllPermissions()->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                        $permissions = $model->getAllPermissions();
                        $groupedPermissions = $permissions->groupBy(function($permission) {
                        return explode('_', $permission->name)[0];
                        });
                        @endphp

                        @forelse($groupedPermissions as $group => $groupPermissions)
                        <div class="mb-3">
                            <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                                <i class="bi bi-{{ $group === 'view' ? 'eye' : ($group === 'create' ? 'plus-circle' : ($group === 'edit' ? 'pencil' : ($group === 'delete' ? 'trash' : 'gear'))) }} me-1"></i>
                                {{ $group }}
                            </h6>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($groupPermissions as $permission)
                                <span class="badge bg-light text-dark" style="font-size: 0.75rem;">
                                    {{ str_replace('_', ' ', $permission->name) }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">Aucune permission spécifique</p>
                        @endforelse
                    </div>
                </div>

                <!-- Historique des rôles -->
                <div class="card info-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history me-2"></i>Historique des rôles
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($model->roles as $role)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div>
                                <strong>Rôle assigné:</strong>
                                <span class="role-badge role-{{ $role->name }}">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </span>
                                <div class="timeline-date mt-1">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $role->created_at ? $role->created_at->format('d/m/Y à H:i') : 'Date inconnue' }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">Aucun historique disponible</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::App Content-->
@endsection