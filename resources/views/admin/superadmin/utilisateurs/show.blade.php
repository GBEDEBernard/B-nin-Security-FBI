@extends('layouts.app')

@section('title', 'Détails du Super Administrateur - Super Admin')

@push('styles')
<style>
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .profile-header {
        background: linear-gradient(135deg, #198754, #20c997);
        border-radius: 16px 16px 0 0;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: #198754;
        border: 5px solid rgba(255, 255, 255, 0.3);
        position: relative;
        z-index: 1;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        margin-top: 1rem;
    }

    .profile-email {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
    }

    .info-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .info-item {
        padding: 1rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #198754;
    }

    .info-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        color: #212529;
        font-weight: 500;
    }

    .badge-role {
        background: linear-gradient(135deg, #6f42c1, #a855f7);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .stat-card {
        border: none;
        border-radius: 12px;
        padding: 1.25rem;
        background: #f8f9fa;
        text-align: center;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-size: 1.25rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #212529;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .action-btn {
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    .timeline-item {
        position: relative;
        padding-left: 2rem;
        padding-bottom: 1.5rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 4px;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-dot {
        position: absolute;
        left: 0;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #198754;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #198754;
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-person-badge me-2"></i>Détails du Super Administrateur</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.utilisateurs.index') }}">Super Administrateurs</a></li>
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
        <!-- Messages -->
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

        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Profile Card -->
                <div class="card profile-card mb-4">
                    <div class="profile-header">
                        <div class="d-flex align-items-center">
                            <div class="profile-avatar">
                                {{ strtoupper(substr($utilisateur->name, 0, 2)) }}
                            </div>
                            <div class="ms-4">
                                <h3 class="profile-name">{{ $utilisateur->name }}</h3>
                                <p class="profile-email mb-2">
                                    <i class="bi bi-envelope me-2"></i>{{ $utilisateur->email }}
                                </p>
                                @if($utilisateur->roles->count() > 0)
                                <span class="badge-role">
                                    <i class="bi bi-shield-fill me-1"></i>
                                    {{ $utilisateur->roles->first()->name }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Statistiques rapides -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div class="stat-value">{{ $utilisateur->is_active ? 'Actif' : 'Inactif' }}</div>
                                    <div class="stat-label">Statut du compte</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-calendar-plus"></i>
                                    </div>
                                    <div class="stat-value">{{ $utilisateur->created_at->format('d/m/Y') }}</div>
                                    <div class="stat-label">Date de création</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div class="stat-value">
                                        @if($utilisateur->last_login_at)
                                        {{ $utilisateur->last_login_at->diffForHumans() }}
                                        @else
                                        -
                                        @endif
                                    </div>
                                    <div class="stat-label">Dernière connexion</div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations détaillées -->
                        <h5 class="mb-4"><i class="bi bi-info-circle me-2"></i>Informations du compte</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="d-flex align-items-center">
                                        <div class="info-icon me-3">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Nom complet</div>
                                            <div class="info-value">{{ $utilisateur->name }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="d-flex align-items-center">
                                        <div class="info-icon me-3">
                                            <i class="bi bi-envelope"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Adresse email</div>
                                            <div class="info-value">
                                                <a href="mailto:{{ $utilisateur->email }}">{{ $utilisateur->email }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="d-flex align-items-center">
                                        <div class="info-icon me-3">
                                            <i class="bi bi-telephone"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Téléphone</div>
                                            <div class="info-value">{{ $utilisateur->telephone ?? 'Non défini' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="d-flex align-items-center">
                                        <div class="info-icon me-3">
                                            <i class="bi bi-shield-check"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Type de compte</div>
                                            <div class="info-value">Super Administrateur</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="d-flex align-items-center">
                                        <div class="info-icon me-3">
                                            <i class="bi bi-calendar"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Créé le</div>
                                            <div class="info-value">{{ $utilisateur->created_at->format('d/m/Y à H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="d-flex align-items-center">
                                        <div class="info-icon me-3">
                                            <i class="bi bi-clock"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Dernière modification</div>
                                            <div class="info-value">{{ $utilisateur->updated_at->format('d/m/Y à H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- IP de dernière connexion -->
                        @if($utilisateur->last_login_ip)
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-geo-alt me-2"></i>
                            <strong>Dernière connexion IP:</strong> {{ $utilisateur->last_login_ip }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div class="col-lg-4">
                <!-- Actions -->
                <div class="card info-card mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.superadmin.utilisateurs.edit', $utilisateur->id) }}" class="btn btn-warning action-btn">
                                <i class="bi bi-pencil me-2"></i>Modifier
                            </a>

                            <form action="{{ route('admin.superadmin.utilisateurs.reset-password', $utilisateur->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-info action-btn w-100"
                                    onclick="return confirm('Réinitialiser le mot de passe de {{ $utilisateur->name }} ?')">
                                    <i class="bi bi-key me-2"></i>Réinitialiser le mot de passe
                                </button>
                            </form>

                            @if($utilisateur->id !== auth()->id())
                            @if($utilisateur->is_active)
                            <form action="{{ route('admin.superadmin.utilisateurs.deactivate', $utilisateur->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary action-btn w-100"
                                    onclick="return confirm('Désactiver {{ $utilisateur->name }} ?')">
                                    <i class="bi bi-person-dash me-2"></i>Désactiver le compte
                                </button>
                            </form>
                            @else
                            <form action="{{ route('admin.superadmin.utilisateurs.activate', $utilisateur->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success action-btn w-100"
                                    onclick="return confirm('Activer {{ $utilisateur->name }} ?')">
                                    <i class="bi bi-person-check me-2"></i>Activer le compte
                                </button>
                            </form>
                            @endif

                            @php
                            $countSuperAdmins = \App\Models\User::where('is_superadmin', true)->count();
                            @endphp

                            @if($countSuperAdmins > 1)
                            <form action="{{ route('admin.superadmin.utilisateurs.destroy', $utilisateur->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger action-btn w-100"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer {{ $utilisateur->name }} ? Cette action est irréversible.')">
                                    <i class="bi bi-trash me-2"></i>Supprimer
                                </button>
                            </form>
                            @endif
                            @else
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                C'est votre propre compte.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Rôles -->
                <div class="card info-card mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0"><i class="bi bi-shield me-2"></i>Rôles</h5>
                    </div>
                    <div class="card-body">
                        @if($utilisateur->roles->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($utilisateur->roles as $role)
                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-shield-fill me-1"></i>{{ $role->name }}
                            </span>
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted mb-0">Aucun rôle assigné</p>
                        @endif
                    </div>
                </div>

                <!-- Retour -->
                <div class="card info-card">
                    <div class="card-body">
                        <a href="{{ route('admin.superadmin.utilisateurs.index') }}" class="btn btn-outline-primary action-btn w-100">
                            <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::App Content-->
@endsection