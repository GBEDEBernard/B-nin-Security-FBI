@extends('layouts.app')

@section('title', 'Modifier l\'Abonnement - ' . ($abonnement->formule_label ?? 'Abonnement'))

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
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.abonnements.show', $abonnement->id) }}">{{ $abonnement->formule_label }}</a></li>
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
                        <h5 class="mb-0">Modifier l'Abonnement</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.superadmin.abonnements.update', $abonnement->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Formule et Description -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="formule" class="form-label">Formule <span class="text-danger">*</span></label>
                                    <select class="form-select" id="formule" name="formule" required>
                                        @foreach($formules as $key => $label)
                                        <option value="{{ $key }}" {{ $abonnement->formule == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="cycle_facturation" class="form-label">Cycle de facturation</label>
                                    <select class="form-select" id="cycle_facturation" name="cycle_facturation">
                                        @foreach($cycles as $key => $label)
                                        <option value="{{ $key }}" {{ $abonnement->cycle_facturation == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="2" placeholder="Description de l'abonnement...">{{ $abonnement->description }}</textarea>
                            </div>

                            <!-- Limites -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="nombre_agents_max" class="form-label">Nombre d'agents max</label>
                                    <input type="number" class="form-control" id="nombre_agents_max" name="nombre_agents_max" value="{{ $abonnement->nombre_agents_max }}" min="0" placeholder="0 = illimité">
                                </div>
                                <div class="col-md-4">
                                    <label for="nombre_sites_max" class="form-label">Nombre de sites max</label>
                                    <input type="number" class="form-control" id="nombre_sites_max" name="nombre_sites_max" value="{{ $abonnement->nombre_sites_max }}" min="0" placeholder="0 = illimité">
                                </div>
                                <div class="col-md-4">
                                    <label for="limite_utilisateurs" class="form-label">Nombre d'utilisateurs</label>
                                    <input type="number" class="form-control" id="limite_utilisateurs" name="limite_utilisateurs" value="{{ $abonnement->limite_utilisateurs }}" min="1">
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="date_debut" class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ $abonnement->date_debut?->format('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="date_fin" class="form-label">Date de fin</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{ $abonnement->date_fin?->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="date_fin_essai" class="form-label">Fin de la période d'essai</label>
                                    <input type="date" class="form-control" id="date_fin_essai" name="date_fin_essai" value="{{ $abonnement->date_fin_essai?->format('Y-m-d') }}">
                                </div>
                            </div>

                            <!-- Facturation -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="montant_mensuel" class="form-label">Montant Mensuel (CFA) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="montant_mensuel" name="montant_mensuel" value="{{ $abonnement->montant_mensuel }}" min="0" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="tarif_agents_supplementaires" class="form-label">Tarif agents sup. (CFA)</label>
                                    <input type="number" class="form-control" id="tarif_agents_supplementaires" name="tarif_agents_supplementaires" value="{{ $abonnement->tarif_agents_supplementaires }}" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="mode_paiement" class="form-label">Mode de paiement</label>
                                    <select class="form-select" id="mode_paiement" name="mode_paiement">
                                        <option value="">Sélectionner</option>
                                        <option value="virement" {{ $abonnement->mode_paiement == 'virement' ? 'selected' : '' }}>Virement</option>
                                        <option value="mobile_money" {{ $abonnement->mode_paiement == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                        <option value="cheque" {{ $abonnement->mode_paiement == 'cheque' ? 'selected' : '' }}>Chèque</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="est_active" name="est_active" {{ $abonnement->est_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="est_active">
                                            Abonnement actif
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="est_en_essai" name="est_en_essai" {{ $abonnement->est_en_essai ? 'checked' : '' }}>
                                        <label class="form-check-label" for="est_en_essai">
                                            Période d'essai
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="est_renouvele_auto" name="est_renouvele_auto" {{ $abonnement->est_renouvele_auto ? 'checked' : '' }}>
                                        <label class="form-check-label" for="est_renouvele_auto">
                                            Renouvellement auto
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Statut -->
                            <div class="mb-3">
                                <label for="statut" class="form-label">Statut</label>
                                <select class="form-select" id="statut" name="statut">
                                    @foreach(\App\Models\Abonnement::STATUTS as $key => $label)
                                    <option value="{{ $key }}" {{ $abonnement->statut == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Notes -->
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Notes internes...">{{ $abonnement->notes }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.superadmin.abonnements.show', $abonnement->id) }}" class="btn btn-secondary">
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
                            <i class="bi bi-credit-card me-2"></i>
                            {{ $abonnement->formule_label }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="avatar-lg mx-auto mb-2" style="background: rgba(25, 135, 84, 0.1); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                <span class="fs-4 text-success">{{ strtoupper(substr($abonnement->formule, 0, 2)) }}</span>
                            </div>
                            <span class="badge bg-{{ $abonnement->formule == 'premium' ? 'purple' : ($abonnement->formule == 'standard' ? 'info' : ($abonnement->formule == 'basic' ? 'warning' : 'secondary')) }}">
                                {{ $abonnement->formule_label }}
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Entreprises:</span>
                            <span class="fw-bold">{{ $abonnement->entreprises_count ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Agents max:</span>
                            <span class="fw-bold">{{ $abonnement->nombre_agents_max ?? 'Illimité' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Sites max:</span>
                            <span class="fw-bold">{{ $abonnement->nombre_sites_max ?? 'Illimité' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Montant:</span>
                            <span class="fw-bold">{{ number_format($abonnement->montant_mensuel, 0, ',', ' ') }} CFA</span>
                        </div>
                    </div>
                </div>

                <!-- Entreprises liées -->
                @if($abonnement->entreprises->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Entreprises liées</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($abonnement->entreprises as $entreprise)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>{{ $entreprise->nom_entreprise }}</span>
                                    <form action="{{ route('admin.superadmin.abonnements.retirer', ['id' => $abonnement->id, 'entrepriseId' => $entreprise->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Retirer">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

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
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span class="badge bg-dark">Enterprise</span>
                                    <span>Sur mesure</span>
                                </div>
                                <small class="text-muted">Personnalisé</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection