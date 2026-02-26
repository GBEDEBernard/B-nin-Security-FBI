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

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="nom_entreprise" class="form-label">Nom de l'Entreprise <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nom_entreprise" name="nom_entreprise" required placeholder="Nom de l'entreprise de sécurité">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="formule" class="form-label">Formule <span class="text-danger">*</span></label>
                                    <select class="form-select" id="formule" name="formule" required>
                                        <option value="">Sélectionner une formule</option>
                                        <option value="essai">Essai</option>
                                        <option value="basic">Basic</option>
                                        <option value="standard" selected>Standard</option>
                                        <option value="premium">Premium</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="montant_mensuel" class="form-label">Montant Mensuel (CFA) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="montant_mensuel" name="montant_mensuel" value="100000" min="0" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre_agents_max" class="form-label">Nombre d'agents maximum</label>
                                    <input type="number" class="form-control" id="nombre_agents_max" name="nombre_agents_max" value="25" min="0" placeholder="Laissez vide pour illimité">
                                    <small class="text-muted">Laissez vide pour illimité</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="nombre_sites_max" class="form-label">Nombre de sites maximum</label>
                                    <input type="number" class="form-control" id="nombre_sites_max" name="nombre_sites_max" value="10" min="0" placeholder="Laissez vide pour illimité">
                                    <small class="text-muted">Laissez vide pour illimité</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date_debut_contrat" class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_debut_contrat" name="date_debut_contrat" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="date_fin_contrat" class="form-label">Date de fin</label>
                                    <input type="date" class="form-control" id="date_fin_contrat" name="date_fin_contrat">
                                    <small class="text-muted">Laissez vide pour sans date de fin</small>
                                </div>
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
                        <p class="small">La création d'un abonnement va également créer une nouvelle entreprise de sécurité dans le système.</p>
                        <p class="small text-muted">L'entreprise pourra ensuite être gérée via le menu "Entreprises" ou directement via ce formulaire d'abonnement.</p>
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