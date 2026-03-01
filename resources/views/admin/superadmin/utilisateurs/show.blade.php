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
        background: linear-gradient(135deg, #6f42c1 0%, #9d7df3 100%);
        border-radius: 16px 16px 0 0;
        padding: 2rem;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .info-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
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

    [data-bs-theme="dark"] .info-card {
        background-color: #1a1a1a;
    }

    [data-bs-theme="dark"] .profile-card {
        background-color: #1a1a1a;
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-person-badge me-2" style="color:#6f42c1;"></i>
                    Détails du Super Administrateur
                </h3>
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
            <!-- Colonne de gauche - Carte de profil -->
            <div class="col-md-4">
                <div class="card profile-card mb-4">
                    <div class="profile-header text-center text-white">
                        @if($utilisateur->photo)
                        <img src="{{ asset('storage/' . $utilisateur->photo) }}" alt="{{ $utilisateur->name }}" class="profile-avatar mb-3">
                        @else
                        <div class="profile-avatar mb-3 d-flex align-items-center justify-content-center mx-auto" style="background: rgba(255,255,255,0.2); font-size: 2.5rem;">
                            {{ strtoupper(substr($utilisateur->name, 0, 2)) }}
                        </div>
                        @endif
                        <h4 class="mb-1">{{ $utilisateur->name }}</h4>
                        <p class="mb-0 opacity-75">{{ $utilisateur->email }}</p>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <div>
                                <div class="text-muted small">Rôle</div>
                                <div class="fw-semibold">
                                    @if($utilisateur->roles->count() > 0)
                                    {{ $utilisateur->roles->first()->name }}
                                    @else
                                    Super Administrateur
                                    @endif
                                </div>
                            </div>
                            <div class="info-icon bg-primary-subtle">
                                <i class="bi bi-shield-lock text-primary"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <div>
                                <div class="text-muted small">Membre depuis</div>
                                <div class="fw-semibold">{{ $utilisateur->created_at->format('d M. Y') }}</div>
                            </div>
                            <div class="info-icon bg-success-subtle">
                                <i class="bi bi-calendar-check text-success"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <div>
                                <div class="text-muted small">Dernière connexion</div>
                                <div class="fw-semibold">
                                    @if($utilisateur->last_login_at)
                                    {{ $utilisateur->last_login_at->format('d M. Y à H:i') }}
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                            <div class="info-icon bg-info-subtle">
                                <i class="bi bi-clock-history text-info"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3">
                            <div>
                                <div class="text-muted small">Statut</div>
                                <div class="fw-semibold text-success">{{ $utilisateur->is_active ? 'Actif' : 'Inactif' }}</div>
                            </div>
                            <div class="info-icon bg-success-subtle">
                                <i class="bi bi-check-circle text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card info-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-gear me-2"></i>
                            Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.superadmin.utilisateurs.edit', $utilisateur->id) }}"
                                class="btn btn-warning action-btn">
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

                <!-- Retour -->
                <div class="card info-card">
                    <div class="card-body">
                        <a href="{{ route('admin.superadmin.utilisateurs.index') }}"
                            class="btn btn-outline-primary action-btn w-100">
                            <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>

            <!-- Colonne de droite - Informations détaillées -->
            <div class="col-md-8">
                <div class="card info-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-person me-2"></i>
                            Informations du compte
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon bg-primary-subtle me-3">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Nom complet</div>
                                        <div class="fw-semibold">{{ $utilisateur->name }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon bg-info-subtle me-3">
                                        <i class="bi bi-envelope text-info"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Adresse email</div>
                                        <div class="fw-semibold">
                                            <a href="mailto:{{ $utilisateur->email }}">{{ $utilisateur->email }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon bg-success-subtle me-3">
                                        <i class="bi bi-telephone text-success"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Téléphone</div>
                                        <div class="fw-semibold">{{ $utilisateur->telephone ?? 'Non défini' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon bg-purple-subtle me-3">
                                        <i class="bi bi-shield-check text-purple"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Type de compte</div>
                                        <div class="fw-semibold">Super Administrateur</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon bg-warning-subtle me-3">
                                        <i class="bi bi-calendar text-warning"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Créé le</div>
                                        <div class="fw-semibold">{{ $utilisateur->created_at->format('d/m/Y à H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon bg-secondary-subtle me-3">
                                        <i class="bi bi-clock text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Dernière modification</div>
                                        <div class="fw-semibold">{{ $utilisateur->updated_at->format('d/m/Y à H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($utilisateur->last_login_ip)
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-geo-alt me-2"></i>
                            <strong>Dernière connexion IP :</strong> {{ $utilisateur->last_login_ip }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card info-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-shield me-2"></i>
                            Rôles et Permissions
                        </h5>
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

                        <div class="mt-3">
                            <div class="d-flex align-items-center">
                                <div class="info-icon bg-success-subtle me-3">
                                    <i class="bi bi-check-circle text-success"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Permissions</div>
                                    <div class="fw-semibold">Super Admin</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::App Content-->
@endsection