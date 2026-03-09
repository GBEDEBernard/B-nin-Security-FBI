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

    .site-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }

    .site-card:hover {
        border-color: #198754;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
    }

    .agent-row {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-bottom: 1px solid #e9ecef;
    }

    .agent-row:last-child {
        border-bottom: none;
    }

    .agent-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #495057;
    }

    .site-badge {
        background: #e9ecef;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
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
                                <p class="text-muted mb-1 small">Sites</p>
                                <h4 class="mb-0 fw-bold">{{ $contrat->sites()->count() }}</h4>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                        </div>
                        <small class="text-muted">sites</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small">Agents</p>
                                <h4 class="mb-0 fw-bold">{{ $contrat->affectations()->where('statut', 'en_cours')->count() }}</h4>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <small class="text-muted">affectés</small>
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
                        <button class="nav-link" id="sites-tab" data-bs-toggle="tab" data-bs-target="#sites" type="button" role="tab">
                            <i class="bi bi-geo-alt me-2"></i>Sites ({{ $contrat->sites()->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="agents-tab" data-bs-toggle="tab" data-bs-target="#agents" type="button" role="tab">
                            <i class="bi bi-people me-2"></i>Agents ({{ $contrat->affectations()->where('statut', 'en_cours')->count() }})
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
                                        <div class="info-item">
                                            <div class="info-label">Nombre de sites autorisés</div>
                                            <div class="info-value">{{ $contrat->nombre_sites ? $contrat->nombre_sites : 'Illimité' }}</div>
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

                    <!-- Onglet Sites -->
                    <div class="tab-pane fade" id="sites" role="tabpanel" aria-labelledby="sites-tab">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Sites associés au contrat</h5>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ajouterSiteModal">
                                <i class="bi bi-plus-circle me-1"></i> Ajouter un site
                            </button>
                        </div>

                        @if($contrat->sites()->count() > 0)
                        <div class="row">
                            @foreach($contrat->sites as $site)
                            <div class="col-md-6">
                                <div class="site-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $site->nom_site }}</h6>
                                            <p class="text-muted small mb-2">{{ $site->adresse ?? 'Adresse non spécifiée' }}</p>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <span class="site-badge">
                                                    <i class="bi bi-people me-1"></i>
                                                    {{ $site->pivot->nombre_agents_site ?? 0 }} agents
                                                </span>
                                                @if($site->ville)
                                                <span class="site-badge">
                                                    <i class="bi bi-geo me-1"></i>
                                                    {{ $site->ville }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editSiteModal{{ $site->id }}">
                                                        <i class="bi bi-pencil me-1"></i> Modifier
                                                    </button>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.superadmin.contrats.retirerSite', [$contrat->id, $site->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir retirer ce site ?')">
                                                            <i class="bi bi-trash me-1"></i> Retirer
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    @if($site->pivot->consignes_site)
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>{{ $site->pivot->consignes_site }}</small>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Modal de modification du site -->
                            <div class="modal fade" id="editSiteModal{{ $site->id }}" tabindex="-1" aria-labelledby="editSiteModalLabel{{ $site->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editSiteModalLabel{{ $site->id }}">Modifier le site</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.superadmin.contrats.updateSite', [$contrat->id, $site->id]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="nombre_agents_site{{ $site->id }}" class="form-label">Nombre d'agents</label>
                                                    <input type="number" class="form-control" id="nombre_agents_site{{ $site->id }}" name="nombre_agents_site" value="{{ $site->pivot->nombre_agents_site ?? 0 }}" min="0" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="consignes_site{{ $site->id }}" class="form-label">Consignes</label>
                                                    <textarea class="form-control" id="consignes_site{{ $site->id }}" name="consignes_site" rows="3">{{ $site->pivot->consignes_site ?? '' }}</textarea>
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
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-geo-alt fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Aucun site associé à ce contrat</p>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ajouterSiteModal">
                                <i class="bi bi-plus-circle me-1"></i> Ajouter un site
                            </button>
                        </div>
                        @endif
                    </div>

                    <!-- Onglet Agents -->
                    <div class="tab-pane fade" id="agents" role="tabpanel" aria-labelledby="agents-tab">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Agents affectés au contrat</h5>
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#reaffecterAgentModal">
                                <i class="bi bi-arrow-left-right me-1"></i> Réaffecter un agent
                            </button>
                        </div>

                        @php
                        $affectations = $contrat->affectations()->with(['employe', 'siteClient'])->where('statut', 'en_cours')->get();
                        @endphp

                        @if($affectations->count() > 0)
                        <div class="card">
                            <div class="card-body p-0">
                                @foreach($affectations as $affectation)
                                <div class="agent-row">
                                    <div class="agent-avatar me-3">
                                        {{ strtoupper(substr($affectation->employe?->prenom ?? 'A', 0, 1)) }}{{ strtoupper(substr($affectation->employe?->nom ?? '', 0, 1)) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $affectation->employe?->nom ?? 'Inconnu' }} {{ $affectation->employe?->prenom ?? '' }}</div>
                                        <div class="text-muted small">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            {{ $affectation->siteClient?->nom_site ?? 'Site non spécifié' }}
                                            @if($affectation->role_site)
                                            <span class="ms-2">- {{ $affectation->role_site }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success">En cours</span>
                                        <div class="text-muted small mt-1">
                                            Depuis {{ $affectation->date_debut?->format('d/m/Y') ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-people fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Aucun agent affecté à ce contrat</p>
                            <p class="text-muted small">Les agents doivent être affectés depuis la gestion des affectations</p>
                        </div>
                        @endif
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

<!-- Modal d'ajout de site -->
<div class="modal fade" id="ajouterSiteModal" tabindex="-1" aria-labelledby="ajouterSiteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajouterSiteModalLabel">Ajouter un site au contrat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.superadmin.contrats.ajouterSite', $contrat->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="site_client_id" class="form-label">Site</label>
                        <select class="form-select" id="site_client_id" name="site_client_id" required>
                            <option value="">Sélectionner un site</option>
                            @foreach($contrat->getSitesDisponibles() as $site)
                            <option value="{{ $site->id }}">{{ $site->nom_site }} - {{ $site->adresse ?? $site->ville }}</option>
                            @endforeach
                        </select>
                        @if($contrat->getSitesDisponibles()->isEmpty())
                        <small class="text-muted">Aucun site disponible. Veuillez d'abord créer des sites pour ce client.</small>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="nombre_agents_site" class="form-label">Nombre d'agents</label>
                        <input type="number" class="form-control" id="nombre_agents_site" name="nombre_agents_site" value="0" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="consignes_site" class="form-label">Consignes spécifiques</label>
                        <textarea class="form-control" id="consignes_site" name="consignes_site" rows="3" placeholder="Consignes pour ce site..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success" @if($contrat->getSitesDisponibles()->isEmpty()) disabled @endif>Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de réaffectation d'agent -->
<div class="modal fade" id="reaffecterAgentModal" tabindex="-1" aria-labelledby="reaffecterAgentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reaffecterAgentModalLabel">Réaffecter un agent</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.superadmin.contrats.reaffecterAgent', $contrat->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="affectation_id" class="form-label">Agent à réaffecter</label>
                        <select class="form-select" id="affectation_id" name="affectation_id" required>
                            <option value="">Sélectionner un agent</option>
                            @foreach($contrat->affectations()->with('employe')->where('statut', 'en_cours')->get() as $affectation)
                            <option value="{{ $affectation->id }}">{{ $affectation->employe?->nom ?? 'Inconnu' }} {{ $affectation->employe?->prenom ?? '' }} - {{ $affectation->siteClient?->nom_site ?? 'Site non spécifié' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="site_client_id_reaffect" class="form-label">Nouveau site</label>
                        <select class="form-select" id="site_client_id_reaffect" name="site_client_id" required>
                            <option value="">Sélectionner un site</option>
                            @foreach($contrat->sites as $site)
                            <option value="{{ $site->id }}">{{ $site->nom_site }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date_reaffectation" class="form-label">Date de réaffectation</label>
                        <input type="date" class="form-control" id="date_reaffectation" name="date_reaffectation" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="motif_reaffectation" class="form-label">Motif (optionnel)</label>
                        <textarea class="form-control" id="motif_reaffectation" name="motif_reaffectation" rows="2" placeholder="Motif de la réaffectation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-info">Réaffecter</button>
                </div>
            </form>
        </div>
    </div>
</div>

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