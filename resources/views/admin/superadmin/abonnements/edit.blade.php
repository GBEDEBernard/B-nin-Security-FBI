@extends('layouts.app')

@section('title', 'Modifier l\'Abonnement - ' . ($entreprise->nom_entreprise ?? $entreprise->nom))

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>
                    Modifier l'Abonnement
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.abonnements.index') }}">Abonnements</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.abonnements.show', $entreprise->id) }}">{{ $entreprise->nom_entreprise ?? $entreprise->nom }}</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Informations de l'Abonnement</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.superadmin.abonnements.update', $entreprise->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="formule" class="form-label">Formule <span class="text-danger">*</span></label>
                                    <select class="form-select" id="formule" name="formule" required>
                                        <option value="">Sélectionner une formule</option>
                                        <option value="essai" {{ $entreprise->formule == 'essai' ? 'selected' : '' }}>Essai</option>
                                        <option value="basic" {{ $entreprise->formule == 'basic' ? 'selected' : '' }}>Basic</option>
                                        <option value="standard" {{ $entreprise->formule == 'standard' ? 'selected' : '' }}>Standard</option>
                                        <option value="premium" {{ $entreprise->formule == 'premium' ? 'selected' : '' }}>Premium</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="montant_mensuel" class="form-label">Montant Mensuel (CFA) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="montant_mensuel" name="montant_mensuel" value="{{ $entreprise->montant_mensuel }}" min="0" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre_agents_max" class="form-label">Nombre d'agents maximum</label>
                                    <input type="number" class="form-control" id="nombre_agents_max" name="nombre_agents_max" value="{{ $entreprise->nombre_agents_max }}" min="0" placeholder="Laissez vide pour illimité">
                                    <small class="text-muted">Laissez vide pour illimité</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="nombre_sites_max" class="form-label">Nombre de sites maximum</label>
                                    <input type="number" class="form-control" id="nombre_sites_max" name="nombre_sites_max" value="{{ $entreprise->nombre_sites_max }}" min="0" placeholder="Laissez vide pour illimité">
                                    <small class="text-muted">Laissez vide pour illimité</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date_debut_contrat" class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_debut_contrat" name="date_debut_contrat" value="{{ $entreprise->date_debut_contrat?->format('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="date_fin_contrat" class="form-label">Date de fin</label>
                                    <input type="date" class="form-control" id="date_fin_contrat" name="date_fin_contrat" value="{{ $entreprise->date_fin_contrat?->format('Y-m-d') }}">
                                    <small class="text-muted">Laissez vide pour sans date de fin</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="est_active" name="est_active" value="1" {{ $entreprise->est_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="est_active">
                                            Abonnement actif
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="est_en_essai" name="est_en_essai" value="1" {{ $entreprise->est_en_essai ? 'checked' : '' }}>
                                        <label class="form-check-label" for="est_en_essai">
                                            Période d'essai
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.superadmin.abonnements.show', $entreprise->id) }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-building me-2"></i>
                            {{ $entreprise->nom_entreprise ?? $entreprise->nom }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="avatar-lg mx-auto mb-2" style="background: rgba(25, 135, 84, 0.1); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                <span class="fs-4 text-success">{{ substr($entreprise->nom_entreprise ?? $entreprise->nom ?? 'EN', 0, 2) }}</span>
                            </div>
                            <span class="badge bg-{{ $entreprise->formule == 'premium' ? 'purple' : ($entreprise->formule == 'standard' ? 'info' : ($entreprise->formule == 'basic' ? 'warning' : 'secondary')) }}">
                                {{ ucfirst($entreprise->formule ?? 'Standard') }}
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Agents actuels:</span>
                            <span class="fw-bold">{{ $entreprise->employes->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Limite agents:</span>
                            <span class="fw-bold">{{ $entreprise->nombre_agents_max ?? 'Illimité' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Clients:</span>
                            <span class="fw-bold">{{ $entreprise->clients->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Contrats:</span>
                            <span class="fw-bold">{{ $entreprise->contratsPrestation->count() }}</span>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Formules disponibles</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span class="badge bg-secondary">Essai</span>
                                    <span>Gratuit</span>
                                </div>
                                <small class="text-muted">Limité</small>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span class="badge bg-warning">Basic</span>
                                    <span>50 000 CFA</span>
                                </div>
                                <small class="text-muted">Jusqu'à 10 agents</small>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span class="badge bg-info">Standard</span>
                                    <span>100 000 CFA</span>
                                </div>
                                <small class="text-muted">Jusqu'à 25 agents</small>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span class="badge bg-purple" style="background: #6f42c1;">Premium</span>
                                    <span>200 000 CFA</span>
                                </div>
                                <small class="text-muted">Agents illimités</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection