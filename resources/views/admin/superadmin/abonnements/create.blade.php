@extends('layouts.app')

@section('title', 'Nouvel Abonnement')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-plus-circle me-2 text-success"></i>
                    Nouvel Abonnement
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.abonnements.index') }}">Abonnements</a></li>
                    <li class="breadcrumb-item active">Nouveau</li>
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
                        <h5 class="mb-0">Créer un Nouvel Abonnement</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.superadmin.abonnements.store') }}" method="POST">
                            @csrf

                            <!-- Formule et Description -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="formule" class="form-label">Formule <span class="text-danger">*</span></label>
                                    <select class="form-select" id="formule" name="formule" required>
                                        <option value="">Sélectionner une formule</option>
                                        @foreach($formules as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="cycle_facturation" class="form-label">Cycle de facturation</label>
                                    <select class="form-select" id="cycle_facturation" name="cycle_facturation">
                                        @foreach($cycles as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="2" placeholder="Description de l'abonnement..."></textarea>
                            </div>

                            <!-- Limites -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="nombre_agents_max" class="form-label">Nombre d'agents max</label>
                                    <input type="number" class="form-control" id="nombre_agents_max" name="nombre_agents_max" value="25" min="0" placeholder="0 = illimité">
                                </div>
                                <div class="col-md-4">
                                    <label for="nombre_sites_max" class="form-label">Nombre de sites max</label>
                                    <input type="number" class="form-control" id="nombre_sites_max" name="nombre_sites_max" value="10" min="0" placeholder="0 = illimité">
                                </div>
                                <div class="col-md-4">
                                    <label for="limite_utilisateurs" class="form-label">Nombre d'utilisateurs</label>
                                    <input type="number" class="form-control" id="limite_utilisateurs" name="limite_utilisateurs" value="5" min="1">
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="date_debut" class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="date_fin" class="form-label">Date de fin</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin">
                                </div>
                                <div class="col-md-4">
                                    <label for="date_fin_essai" class="form-label">Fin de la période d'essai</label>
                                    <input type="date" class="form-control" id="date_fin_essai" name="date_fin_essai">
                                </div>
                            </div>

                            <!-- Facturation -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="montant_mensuel" class="form-label">Montant Mensuel (CFA) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="montant_mensuel" name="montant_mensuel" value="100000" min="0" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="tarif_agents_supplementaires" class="form-label">Tarif agents sup. (CFA)</label>
                                    <input type="number" class="form-control" id="tarif_agents_supplementaires" name="tarif_agents_supplementaires" value="5000" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="mode_paiement" class="form-label">Mode de paiement</label>
                                    <select class="form-select" id="mode_paiement" name="mode_paiement">
                                        <option value="">Sélectionner</option>
                                        <option value="virement">Virement</option>
                                        <option value="mobile_money">Mobile Money</option>
                                        <option value="cheque">Chèque</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="est_active" name="est_active" checked>
                                        <label class="form-check-label" for="est_active">
                                            Abonnement actif
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="est_en_essai" name="est_en_essai">
                                        <label class="form-check-label" for="est_en_essai">
                                            Période d'essai
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="est_renouvele_auto" name="est_renouvele_auto">
                                        <label class="form-check-label" for="est_renouvele_auto">
                                            Renouvellement auto
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Assignation à une entreprise -->
                            <div class="mb-3">
                                <label for="entreprise_id" class="form-label">Assigner à une entreprise</label>
                                <select class="form-select" id="entreprise_id" name="entreprise_id">
                                    <option value="">Sélectionner une entreprise (optionnel)</option>
                                    @foreach($entreprises as $entreprise)
                                    <option value="{{ $entreprise->id }}">{{ $entreprise->nom_entreprise }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Vous pouvez assigner cet abonnement à une entreprise maintenant ou plus tard</small>
                            </div>

                            <!-- Notes -->
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Notes internes..."></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.superadmin.abonnements.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-lg me-1"></i> Créer l'abonnement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Informations
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="small">Créez un plan d'abonnement qui pourra être assigné à une ou plusieurs entreprises.</p>
                        <p class="small text-muted">L'abonnement définit les limites (agents, sites) et le tarif. Chaque entreprise peut avoir son propre abonnement.</p>
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