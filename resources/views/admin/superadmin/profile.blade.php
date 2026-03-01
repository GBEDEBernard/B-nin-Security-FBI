@extends('layouts.app')

@section('title', 'Mon Profil - Benin Security')

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

    [data-bs-theme="dark"] .info-card {
        background-color: #1a1a1a;
    }

    [data-bs-theme="dark"] .profile-card {
        background-color: #1a1a1a;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-person-circle me-2" style="color:#6f42c1;"></i>
                    Mon Profil
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profil</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-4">
                <div class="card profile-card mb-4">
                    <div class="profile-header text-center text-white">
                        @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" class="profile-avatar mb-3">
                        @else
                        <div class="profile-avatar mb-3 d-flex align-items-center justify-content-center mx-auto" style="background: rgba(255,255,255,0.2); font-size: 2.5rem;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        @endif
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="mb-0 opacity-75">{{ $user->email }}</p>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <div>
                                <div class="text-muted small">Rôle</div>
                                <div class="fw-semibold">Super Administrateur</div>
                            </div>
                            <div class="info-icon bg-primary-subtle">
                                <i class="bi bi-shield-lock text-primary"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <div>
                                <div class="text-muted small">Membre depuis</div>
                                <div class="fw-semibold">{{ $user->created_at->format('d M. Y') }}</div>
                            </div>
                            <div class="info-icon bg-success-subtle">
                                <i class="bi bi-calendar-check text-success"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3">
                            <div>
                                <div class="text-muted small">Dernière connexion</div>
                                <div class="fw-semibold">{{ $user->updated_at->format('d M. Y à H:i') }}</div>
                            </div>
                            <div class="info-icon bg-info-subtle">
                                <i class="bi bi-clock-history text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card info-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i>
                            Modifier mes informations
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('admin.superadmin.profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nom complet</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Adresse email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Nouveau mot de passe (optionnel)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Laissez vide pour garder l'actuel">
                                    </div>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmer le mot de passe">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.superadmin.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i> Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card info-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Informations supplémentaires
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon bg-success-subtle me-3">
                                        <i class="bi bi-check-circle text-success"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Statut du compte</div>
                                        <div class="fw-semibold text-success">Actif</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon bg-purple-subtle me-3">
                                        <i class="bi bi-shield-check text-purple"></i>
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
</div>
@endsection