@extends('layouts.app')

@section('title', 'Détails Contrat - Super Admin')

@push('styles')
<style>
    .contract-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .contract-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .stat-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .info-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
    }

    .info-item {
        padding: 1rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-weight: 500;
        color: #212529;
    }

    .badge-statut {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .badge-brouillon {
        background: rgba(108, 117, 125, 0.15);
        color: #6c757d;
    }

    .badge-en_cours {
        background: rgba(25, 135, 84, 0.15);
        color: #198754;
    }

    .badge-suspendu {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }

    .badge-termine {
        background: rgba(13, 110, 253, 0.15);
        color: #0d6efd;
    }

    .badge-resilie {
        background: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 1rem 1.5rem;
        position: relative;
    }

    .nav-tabs-custom .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 3px;
        background: #198754;
        transition: width 0.3s ease;
    }

    .nav-tabs-custom .nav-link:hover {
        color: #198754;
    }

    .nav-tabs-custom .nav-link.active {
        color: #198754;
        background: transparent;
    }

    .nav-tabs-custom .nav-link.active::after {
        width: 100%;
    }

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

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 4px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #198754;
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Détails du Contrat</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.contrats.index') }}">Contrats</a></li>
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

        <!-- Messages de session -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- En-tête du contrat -->
        <div class="contract-header mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-file-earmark-text fs-2 text-white"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="mb-1 fw-bold">{{ $contrat->numero_contrat }}</h2>
                            <p class="mb-0 opacity-75">{{ $contrat->intitule }}</p>
                            <div class="mt-2 d-flex gap-2 flex-wrap">
                                <span class="badge bg-white bg-opacity-25">
                                    <i class="bi bi-building me-1"></i> {{ $contrat->entreprise?->nom_entreprise ?? 'N/A' }}
                                </span>
                                <span class="badge-statut badge-{{ $contrat->statut }}">
                                    {{ match($contrat->statut) {
                                        'brouillon' => 'Brouillon',
                                        'en_cours' => 'En cours',
                                        'suspendu' => 'Suspendu',
                                        'termine' => 'Terminé',
                                        'resilie' => 'Résilié',
                                        default => 'Inconnu'
                                    } }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.superadmin.contrats.edit', $contrat->id) }}" class="action-btn btn btn-light text-success">
                                <i class="bi bi-pencil me-1"></i> Modifier
                            </a>
                            <button class="action-btn btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#statutModal">
                                <i class="bi bi-arrow-repeat me-1"></i> Statut
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small">Montant mensuel</p>
                                <h4 class="mb-0 fw-bold">{{ number_format($contrat->montant_mensuel_ht ?? 0, 0, ',', ' ') }}</h4>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-currency-exchange"></i>
                            </div>
                        </div>
                        <small class="text-muted">FCFA HT</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small">Montant TTC</p>
                                <h4 class="mb-0 fw-bold">{{ number_format($contrat->montant_mensuel_ttc ?? 0, 0, ',', ' ') }}</h4>
                            </div>
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-receipt"></i>
                            </div>
                        </div>
                        <small class="text-muted">FCFA TTC</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small">Agents requis</p>
                                <h4 class="mb-0 fw-bold">{{ $contrat->nombre_agents_requis ?? 0 }}</h4>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <small class="text-muted">agents</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small">Durée</p>
                                @php
                                $datedebut = \Carbon\Carbon::parse($contrat->date_debut);
                                $datefin = \Carbon\Carbon::parse($contrat->date_fin);
                                $diff = $datedebut->diffInMonths($datefin);
                                @endphp
                                <h4 class="mb-0 fw-bold">{{ $diff }}</h4>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-calendar-range"></i>
                            </div>
                        </div>
                        <small class="text-muted">mois</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglets d'informations -->
        <div class="card">
            <div class="card-header p-0">
                <ul class="nav nav-tabs-custom" id="contratTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="infos-tab" data-bs-toggle="tab" data-bs-target="#infos" type="button" role="tab">
                            <i class="bi bi-info-circle me-2"></i>Informations
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="periode-tab" data-bs-toggle="tab" data-bs-target="#periode" type="button" role="tab">
                            <i class="bi bi-calendar me-2"></i>Période
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="signataires-tab" data-bs-toggle="tab" data-bs-target="#signataires" type="button" role="tab">
                            <i class="bi bi-pen me-2"></i>Signataires
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="historique-tab" data-bs-toggle="tab" data-bs-target="#historique" type="button" role="tab">
                            <i class="bi bi-clock-history me-2"></i>Historique
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="contratTabsContent">

                    <!-- Onglet Informations -->
                    <div class="tab-pane fade show active" id="infos" role="tabpanel" aria-labelledby="infos-tab">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-card card h-100">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0"><i class="bi bi-building me-2 text-success"></i>Entreprise & Client</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item">
                                            <div class="info-label">Entreprise de sécurité</div>
                                            <div class="info-value">{{ $contrat->entreprise?->nom_entreprise ?? 'N/A' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Client</div>
                                            <div class="info-value">{{ $contrat->client?->nom ?? 'N/A' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Email client</div>
                                            <div class="info-value">{{ $contrat->client?->email ?? 'N/A' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Téléphone client</div>
                                            <div class="info-value">{{ $contrat->client?->telephone ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-card card h-100">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0"><i class="bi bi-cash-stack me-2 text-success"></i>Financial Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item">
                                            <div class="info-label">Montant mensuel HT</div>
                                            <div class="info-value fw-bold">{{ number_format($contrat->montant_mensuel_ht ?? 0, 0, ',', ' ') }} FCAF</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Montant annuel HT</div>
                                            <div class="info-value">{{ number_format($contrat->montant_annuel_ht ?? 0, 0, ',', ' ') }} FCAF</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">TVA</div>
                                            <div class="info-value">{{ $contrat->tva ?? 18 }}%</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Montant mensuel TTC</div>
                                            <div class="info-value fw-bold text-success">{{ number_format($contrat->montant_mensuel_ttc ?? 0, 0, ',', ' ') }} FCAF</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Périodicité</div>
                                            <div class="info-value">{{ ucfirst($contrat->periodicite_facturation ?? 'mensuel') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($contrat->description_prestation)
                            <div class="col-12">
                                <div class="info-card card">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0"><i class="bi bi-text-paragraph me-2 text-success"></i>Description de la prestation</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">{{ $contrat->description_prestation }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Onglet Période -->
                    <div class="tab-pane fade" id="periode" role="tabpanel" aria-labelledby="periode-tab">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-card card h-100">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0"><i class="bi bi-calendar-event me-2 text-success"></i>Période du contrat</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item">
                                            <div class="info-label">Date de début</div>
                                            <div class="info-value">{{ $contrat->date_debut?->format('d/m/Y') ?? 'N/A' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Date de fin</div>
                                            <div class="info-value">{{ $contrat->date_fin?->format('d/m/Y') ?? 'N/A' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Statut</div>
                                            <div class="info-value">
                                                <span class="badge-statut badge-{{ $contrat->statut }}">
                                                    {{ match($contrat->statut) {
                                                        'brouillon' => 'Brouillon',
                                                        'en_cours' => 'En cours',
                                                        'suspendu' => 'Suspendu',
                                                        'termine' => 'Terminé',
                                                        'resilie' => 'Résilié',
                                                        default => 'Inconnu'
                                                    } }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Renouvelable</div>
                                            <div class="info-value">{{ $contrat->est_renouvelable ? 'Oui' : 'Non' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Durée du préavis</div>
                                            <div class="info-value">{{ $contrat->duree_preavis ?? 30 }} jours</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-card card h-100">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0"><i class="bi bi-people me-2 text-success"></i>Ressources</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item">
                                            <div class="info-label">Nombre d'agents requis</div>
                                            <div class="info-value">{{ $contrat->nombre_agents_requis ?? 0 }}</div>
                                        </div>
                                        @if($contrat->conditions_particulieres)
                                        <div class="info-item">
                                            <div class="info-label">Conditions particulières</div>
                                            <div class="info-value">{{ $contrat->conditions_particulieres }}</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Onglet Signataires -->
                    <div class="tab-pane fade" id="signataires" role="tabpanel" aria-labelledby="signataires-tab">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-card card h-100">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0"><i class="bi bi-person me-2 text-success"></i>Signataire Client</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item">
                                            <div class="info-label">Nom</div>
                                            <div class="info-value">{{ $contrat->signataire_client_nom ?? 'Non spécifié' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Fonction</div>
                                            <div class="info-value">{{ $contrat->signataire_client_fonction ?? 'Non spécifiée' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-card card h-100">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0"><i class="bi bi-building me-2 text-success"></i>Signataire Sécurité</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item">
                                            <div class="info-label">Nom</div>
                                            <div class="info-value">{{ $contrat->signataireSecurite?->nom ?? 'Non spécifié' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Date de signature</div>
                                            <div class="info-value">{{ $contrat->date_signature?->format('d/m/Y') ?? 'Non signé' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Onglet Historique -->
                    <div class="tab-pane fade" id="historique" role="tabpanel" aria-labelledby="historique-tab">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="fw-semibold">Création du contrat</div>
                                <small class="text-muted">{{ $contrat->created_at?->format('d/m/Y à H:i') }}</small>
                            </div>
                            @if($contrat->date_signature)
                            <div class="timeline-item">
                                <div class="fw-semibold">Signature du contrat</div>
                                <small class="text-muted">{{ $contrat->date_signature->format('d/m/Y') }}</small>
                            </div>
                            @endif
                            @if($contrat->date_resiliation)
                            <div class="timeline-item">
                                <div class="fw-semibold text-danger">Résiliation</div>
                                <small class="text-muted">{{ $contrat->date_resiliation->format('d/m/Y') }}</small>
                                <p class="mb-0 small">{{ $contrat->motif_resiliation }}</p>
                            </div>
                            @endif
                            <div class="timeline-item">
                                <div class="fw-semibold">Dernière modification</div>
                                <small class="text-muted">{{ $contrat->updated_at?->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="bi bi-gear me-2"></i>Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.superadmin.contrats.edit', $contrat->id) }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i> Modifier
                            </a>
                            <a href="{{ route('admin.superadmin.contrats.dupliquer', $contrat->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-copy me-1"></i> Dupliquer
                            </a>
                            <button class="btn btn-outline-info" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Imprimer
                            </button>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="bi bi-trash me-1"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::App Content-->

<!-- Modal de changement de statut -->
<div class="modal fade" id="statutModal" tabindex="-1" aria-labelledby="statutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statutModalLabel">Changer le statut du contrat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.superadmin.contrats.changerStatut', $contrat->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="statut" class="form-label">Nouveau statut</label>
                        <select class="form-select" id="statut" name="statut" required>
                            <option value="brouillon" {{ $contrat->statut == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="en_cours" {{ $contrat->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="suspendu" {{ $contrat->statut == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="termine" {{ $contrat->statut == 'termine' ? 'selected' : '' }}>Terminé</option>
                            <option value="resilie" {{ $contrat->statut == 'resilie' ? 'selected' : '' }}>Résilié</option>
                        </select>
                    </div>
                    <div id="resiliationFields" style="display: {{ $contrat->statut == 'resilie' ? 'block' : 'none' }}">
                        <div class="mb-3">
                            <label for="motif_resiliation" class="form-label">Motif de résiliation</label>
                            <textarea class="form-control" id="motif_resiliation" name="motif_resiliation" rows="3">{{ $contrat->motif_resiliation }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="date_resiliation" class="form-label">Date de résiliation</label>
                            <input type="date" class="form-control" id="date_resiliation" name="date_resiliation" value="{{ $contrat->date_resiliation?->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le contrat <strong>{{ $contrat->numero_contrat }}</strong> ?</p>
                @if($contrat->factures()->count() > 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Ce contrat possède des factures. La suppression est bloquée.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                @if($contrat->factures()->count() == 0)
                <form action="{{ route('admin.superadmin.contrats.destroy', $contrat->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Afficher/masquer les champs de résiliation
    document.getElementById('statut').addEventListener('change', function() {
        const resiliationFields = document.getElementById('resiliationFields');
        if (this.value === 'resilie') {
            resiliationFields.style.display = 'block';
        } else {
            resiliationFields.style.display = 'none';
        }
    });
</script>
@endpush
@endsection