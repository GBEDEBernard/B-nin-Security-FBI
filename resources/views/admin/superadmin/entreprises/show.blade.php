@extends('layouts.app')

@section('title', 'Détails Entreprise - Super Admin')

@push('styles')
<style>
    .profile-header {
        border-radius: 16px;
        padding: 2rem;
        color: white;
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

    .profile-logo {
        width: 100px;
        height: 100px;
        border-radius: 16px;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.3);
        background: white;
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

    .badge-formule {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .badge-essai {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }

    .badge-basic {
        background: rgba(13, 110, 253, 0.15);
        color: #0d6efd;
    }

    .badge-standard {
        background: rgba(25, 135, 84, 0.15);
        color: #198754;
    }

    .badge-premium {
        background: rgba(111, 66, 193, 0.15);
        color: #6f42c1;
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

    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
    }

    .data-table {
        margin-bottom: 0;
    }

    .data-table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        color: #495057;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .data-table tbody tr:hover {
        background: #f8f9fa;
    }

    .avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
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
                <h3 class="mb-0"><i class="bi bi-building me-2"></i>Détails de l'Entreprise</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.entreprises.index') }}">Entreprises</a></li>
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



        <!-- En-tête de profil avec couleurs dynamiques de l'entreprise -->
        @php
        $couleurPrimaire = $entreprise->couleur_primaire ?? '#198754';
        $couleurSecondaire = $entreprise->couleur_secondaire ?? '#20c997';
        @endphp
        <div class="profile-header mb-4" style="background: linear-gradient(135deg, {{ $couleurPrimaire }} 0%, {{ $couleurSecondaire }} 100%);">
            <div class="row align-items-center">
                <div class="col-auto">
                    @if($entreprise->logo)
                    <img src="{{ $entreprise->logoUrl }}" alt="Logo" class="profile-logo">
                    @else
                    <div class="profile-logo d-flex align-items-center justify-content-center bg-white bg-opacity-25">
                        <span class="fs-1 fw-bold text-white">{{ strtoupper(substr($entreprise->nom_entreprise, 0, 2)) }}</span>
                    </div>
                    @endif
                </div>
                <div class="col">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="mb-1 fw-bold">{{ $entreprise->nom_entreprise }}</h2>
                            @if($entreprise->nom_commercial)
                            <p class="mb-0 opacity-75">{{ $entreprise->nom_commercial }}</p>
                            @endif
                            <div class="mt-2 d-flex gap-2">
                                @if($entreprise->est_active)
                                <span class="badge bg-white bg-opacity-25">
                                    <i class="bi bi-check-circle me-1"></i> Active
                                </span>
                                @else
                                <span class="badge bg-secondary bg-opacity-50">
                                    <i class="bi bi-x-circle me-1"></i> Inactive
                                </span>
                                @endif
                                <span class="badge-formule badge-{{ $entreprise->formule ?? 'basic' }}">
                                    {{ ucfirst($entreprise->formule ?? 'basic') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.superadmin.entreprises.edit', $entreprise->id) }}" class="action-btn btn btn-light text-success">
                                <i class="bi bi-pencil me-1"></i> Modifier
                            </a>
                            @if($entreprise->est_active)
                            <button class="action-btn btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#connectModal"
                                data-entreprise-id="{{ $entreprise->id }}"
                                data-entreprise-nom="{{ $entreprise->nom_entreprise }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Se connecter
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cartes de statistiques -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small">Employés</p>
                                <h3 class="mb-0 fw-bold">{{ $entreprise->employes_count ?? 0 }}</h3>
                            </div>
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Max: {{ $entreprise->nombre_agents_max ?? 'N/A' }}</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small">Clients</p>
                                <h3 class="mb-0 fw-bold">{{ $entreprise->clients_count ?? 0 }}</h3>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-person-badge"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Max: {{ $entreprise->nombre_sites_max ?? 'N/A' }} sites</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small">Contrats</p>
                                <h3 class="mb-0 fw-bold">{{ $entreprise->contratsPrestation_count ?? 0 }}</h3>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Contrats actifs</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small">Abonnement</p>
                                <h3 class="mb-0 fw-bold">{{ number_format($entreprise->montant_mensuel ?? 0, 0, ',', ' ') }}</h3>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-currency-exchange"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">FCFA / mois</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglets d'informations -->
        <div class="card">
            <div class="card-header p-0">
                <ul class="nav nav-tabs-custom" id="entrepriseTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="infos-tab" data-bs-toggle="tab" data-bs-target="#infos" type="button" role="tab">
                            <i class="bi bi-info-circle me-2"></i>Informations
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="employes-tab" data-bs-toggle="tab" data-bs-target="#employes" type="button" role="tab">
                            <i class="bi bi-people me-2"></i>Employés ({{ $entreprise->employes_count ?? 0 }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="clients-tab" data-bs-toggle="tab" data-bs-target="#clients" type="button" role="tab">
                            <i class="bi bi-person-badge me-2"></i>Clients ({{ $entreprise->clients_count ?? 0 }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contrats-tab" data-bs-toggle="tab" data-bs-target="#contrats" type="button" role="tab">
                            <i class="bi bi-file-earmark-text me-2"></i>Contrats ({{ $entreprise->contratsPrestation_count ?? 0 }})
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="entrepriseTabsContent">

                    <!-- Onglet Informations -->
                    <div class="tab-pane fade show active" id="infos" role="tabpanel" aria-labelledby="infos-tab">
                        <div class="row g-4">
                            <!-- Colonne de gauche -->
                            <div class="col-md-6">
                                <div class="info-card card h-100">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0"><i class="bi bi-building me-2 text-success"></i>Informations générales</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item">
                                            <div class="info-label">Forme juridique</div>
                                            <div class="info-value">{{ $entreprise->forme_juridique ?? 'Non spécifié' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">N° Registre de commerce</div>
                                            <div class="info-value">{{ $entreprise->numero_registre ?? 'Non spécifié' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">N° Identification Fiscale</div>
                                            <div class="info-value">{{ $entreprise->numeroIdentificationFiscale ?? 'Non spécifié' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">N° Contribuable</div>
                                            <div class="info-value">{{ $entreprise->numeroContribuable ?? 'Non spécifié' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Slug</div>
                                            <div class="info-value">{{ $entreprise->slug ?? 'Non spécifié' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Colonne de droite -->
                            <div class="col-md-6">
                                <div class="info-card card h-100">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0"><i class="bi bi-telephone me-2 text-success"></i>Coordonnées</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item">
                                            <div class="info-label">Email</div>
                                            <div class="info-value">
                                                <a href="mailto:{{ $entreprise->email }}" class="text-decoration-none">{{ $entreprise->email }}</a>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Téléphone</div>
                                            <div class="info-value">{{ $entreprise->telephone ?? 'Non spécifié' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Téléphone alternatif</div>
                                            <div class="info-value">{{ $entreprise->telephone_alternatif ?? 'Non spécifié' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Adresse</div>
                                            <div class="info-value">
                                                {{ $entreprise->adresse ?? 'Non spécifiée' }}<br>
                                                {{ $entreprise->ville ?? '' }}@if($entreprise->ville && $entreprise->pays), @endif{{ $entreprise->pays ?? '' }} @if($entreprise->code_postal)({{ $entreprise->code_postal }})@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Représentant légal -->
                            <div class="col-md-6">
                                <div class="info-card card h-100">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0"><i class="bi bi-person-vcard me-2 text-success"></i>Représentant légal</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item">
                                            <div class="info-label">Nom complet</div>
                                            <div class="info-value">{{ $entreprise->nom_representant_legal ?? 'Non spécifié' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Email</div>
                                            <div class="info-value">
                                                @if($entreprise->email_representant_legal)
                                                <a href="mailto:{{ $entreprise->email_representant_legal }}" class="text-decoration-none">{{ $entreprise->email_representant_legal }}</a>
                                                @else
                                                Non spécifié
                                                @endif
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Téléphone</div>
                                            <div class="info-value">{{ $entreprise->telephone_representant_legal ?? 'Non spécifié' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Abonnement -->
                            <div class="col-md-6">
                                <div class="info-card card h-100">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0"><i class="bi bi-credit-card me-2 text-success"></i>Abonnement</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item">
                                            <div class="info-label">Formule</div>
                                            <div class="info-value">
                                                <span class="badge-formule badge-{{ $entreprise->formule ?? 'basic' }}">
                                                    {{ ucfirst($entreprise->formule ?? 'basic') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Période</div>
                                            <div class="info-value">
                                                @if($entreprise->est_en_essai)
                                                <span class="text-warning">Essai jusqu'au {{ $entreprise->date_fin_essai?->format('d/m/Y') ?? 'N/A' }}</span>
                                                @else
                                                Du {{ $entreprise->date_debut_contrat?->format('d/m/Y') ?? 'N/A' }} au {{ $entreprise->date_fin_contrat?->format('d/m/Y') ?? 'N/A' }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Cycle de facturation</div>
                                            <div class="info-value">{{ ucfirst($entreprise->cycle_facturation ?? 'mensuel') }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Montant mensuel</div>
                                            <div class="info-value fw-bold text-success">{{ number_format($entreprise->montant_mensuel ?? 0, 0, ',', ' ') }} FCAF</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            @if($entreprise->notes)
                            <div class="col-12">
                                <div class="info-card card">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0"><i class="bi bi-sticky me-2 text-success"></i>Notes</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">{{ $entreprise->notes }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Onglet Employés -->
                    <div class="tab-pane fade" id="employes" role="tabpanel" aria-labelledby="employes-tab">
                        @if($entreprise->employes && $entreprise->employes->count() > 0)
                        <div class="table-responsive">
                            <table class="table data-table table-hover">
                                <thead>
                                    <tr>
                                        <th>Matricule</th>
                                        <th>Nom</th>
                                        <th>Téléphone</th>
                                        <th> Fonction</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entreprise->employes as $employe)
                                    <tr>
                                        <td><span class="fw-semibold">{{ $employe->matricule }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle me-2">
                                                    {{ strtoupper(substr($employe->nom, 0, 2)) }}
                                                </div>
                                                {{ $employe->nom }}
                                            </div>
                                        </td>
                                        <td>{{ $employe->telephone }}</td>
                                        <td>{{ $employe->fonction ?? 'Non défini' }}</td>
                                        <td>
                                            @if($employe->statut == 'en_poste')
                                            <span class="badge bg-success">En poste</span>
                                            @elseif($employe->statut == 'conge')
                                            <span class="badge bg-warning">En congé</span>
                                            @else
                                            <span class="badge bg-secondary">{{ $employe->statut }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-people fs-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Aucun employé</h5>
                            <p class="text-muted">Aucun employé n'a encore été ajouté à cette entreprise.</p>
                        </div>
                        @endif
                    </div>

                    <!-- Onglet Clients -->
                    <div class="tab-pane fade" id="clients" role="tabpanel" aria-labelledby="clients-tab">
                        @if($entreprise->clients && $entreprise->clients->count() > 0)
                        <div class="table-responsive">
                            <table class="table data-table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entreprise->clients as $client)
                                    <tr>
                                        <td><span class="fw-semibold">{{ $client->nom }}</span></td>
                                        <td>{{ $client->email }}</td>
                                        <td>{{ $client->telephone }}</td>
                                        <td>{{ ucfirst($client->type_client ?? 'particulier') }}</td>
                                        <td>
                                            @if($client->est_actif)
                                            <span class="badge bg-success">Actif</span>
                                            @else
                                            <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-person-badge fs-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Aucun client</h5>
                            <p class="text-muted">Aucun client n'a encore été ajouté à cette entreprise.</p>
                        </div>
                        @endif
                    </div>

                    <!-- Onglet Contrats -->
                    <div class="tab-pane fade" id="contrats" role="tabpanel" aria-labelledby="contrats-tab">
                        @if($entreprise->contratsPrestation && $entreprise->contratsPrestation->count() > 0)
                        <div class="table-responsive">
                            <table class="table data-table table-hover">
                                <thead>
                                    <tr>
                                        <th>N° Contrat</th>
                                        <th>Client</th>
                                        <th>Date début</th>
                                        <th>Date fin</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entreprise->contratsPrestation as $contrat)
                                    <tr>
                                        <td><span class="fw-semibold">{{ $contrat->numero_contrat }}</span></td>
                                        <td>{{ $contrat->client?->nom ?? 'N/A' }}</td>
                                        <td>{{ $contrat->date_debut?->format('d/m/Y') }}</td>
                                        <td>{{ $contrat->date_fin?->format('d/m/Y') }}</td>
                                        <td>{{ number_format($contrat->montant_total ?? 0, 0, ',', ' ') }} FCAF</td>
                                        <td>
                                            @if($contrat->statut == 'actif')
                                            <span class="badge bg-success">Actif</span>
                                            @elseif($contrat->statut == 'expire')
                                            <span class="badge bg-warning">Expiré</span>
                                            @elseif($contrat->statut == 'resilie')
                                            <span class="badge bg-danger">Résilié</span>
                                            @else
                                            <span class="badge bg-secondary">{{ $contrat->statut }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-text fs-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Aucun contrat</h5>
                            <p class="text-muted">Aucun contrat de prestation n'a encore été créé.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions supplémentaires -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="bi bi-gear me-2"></i>Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap">
                            @if($entreprise->est_active)
                            <form action="{{ route('admin.superadmin.entreprises.deactivate', $entreprise->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning">
                                    <i class="bi bi-pause-circle me-1"></i> Désactiver
                                </button>
                            </form>
                            @else
                            <form action="{{ route('admin.superadmin.entreprises.activate', $entreprise->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="bi bi-play-circle me-1"></i> Activer
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('admin.superadmin.entreprises.edit', $entreprise->id) }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i> Modifier
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

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'entreprise <strong>{{ $entreprise->nom_entreprise }}</strong> ?</p>
                @if($entreprise->employes_count > 0 || $entreprise->clients_count > 0 || $entreprise->contratsPrestation_count > 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Cette entreprise possède des données关联. La suppression est bloquée.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                @if($entreprise->employes_count == 0 && $entreprise->clients_count == 0 && $entreprise->contratsPrestation_count == 0)
                <form action="{{ route('admin.superadmin.entreprises.destroy', $entreprise->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
                @else
                <button type="button" class="btn btn-danger" disabled>Suppression impossible</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection