@extends('layouts.app')

@section('title', 'Détails du Contrat - Client')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #0d6efd 0%, #6ea8fe 100%);
        padding: 1.75rem 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 4px 20px rgba(13, 110, 253, 0.3);
    }

    .page-header h3 {
        margin: 0;
        font-weight: 700;
        font-size: 1.6rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header .breadcrumb {
        margin: 0;
        padding: 0;
        background: transparent;
    }

    .page-header .breadcrumb-item,
    .page-header .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.875rem;
    }

    .page-header .breadcrumb-item.active {
        color: white;
        font-weight: 600;
    }

    .page-header .breadcrumb-item+.breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.6);
    }

    .detail-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        background: var(--bs-body-bg);
    }

    .stat-card {
        border: none;
        border-radius: 12px;
    }

    .badge-statut {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
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

    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 0.95rem;
        color: #212529;
        font-weight: 500;
    }

    .section-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #0d6efd;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-in {
        animation: fadeInUp 0.4s ease-out both;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">

    {{-- HEADER --}}
    <div class="page-header animate-in">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h3>
                    <i class="bi bi-file-earmark-text-fill"></i>
                    Détails du Contrat
                </h3>
                <div class="mt-1 opacity-75" style="font-size:0.875rem;">
                    {{ $contrat->numero_contrat }}
                </div>
            </div>
            <div class="col-md-5">
                <ol class="breadcrumb float-md-end mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.client.index') }}">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.client.contrats.index') }}">Contrats</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $contrat->numero_contrat }}</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- EN-TÊTE CONTRAT --}}
    <div class="card detail-card mb-4 animate-in">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-3">
                        <h4 class="mb-0 me-3">{{ $contrat->numero_contrat }}</h4>
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
                    <h5 class="text-muted">{{ $contrat->intitule }}</h5>
                    <div class="mt-2">
                        <span class="me-3">
                            <i class="bi bi-building me-1"></i>
                            {{ $contrat->entreprise?->nom ?? 'N/A' }}
                        </span>
                        <span class="me-3">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $contrat->date_debut?->format('d/m/Y') ?? '—' }} - {{ $contrat->date_fin?->format('d/m/Y') ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATISTIQUES --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card card bg-primary bg-opacity-10 h-100">
                <div class="card-body">
                    <div class="info-label">Montant Mensuel</div>
                    <div class="info-value">{{ number_format($contrat->montant_mensuel_ht ?? 0, 0, ',', ' ') }} FCA</div>
                    <small class="text-muted">HT</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card bg-success bg-opacity-10 h-100">
                <div class="card-body">
                    <div class="info-label">Montant Annuel</div>
                    <div class="info-value">{{ number_format($contrat->montant_annuel_ht ?? 0, 0, ',', ' ') }} FCA</div>
                    <small class="text-muted">HT</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card bg-info bg-opacity-10 h-100">
                <div class="card-body">
                    <div class="info-label">Agents Requis</div>
                    <div class="info-value">{{ $contrat->nombre_agents_requis ?? 0 }}</div>
                    <small class="text-muted">personnes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card bg-warning bg-opacity-10 h-100">
                <div class="card-body">
                    <div class="info-label">Sites</div>
                    <div class="info-value">{{ $contrat->nombre_sites ?? 0 }}</div>
                    <small class="text-muted">sites</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- INFORMATIONS PRINCIPALES --}}
        <div class="col-lg-8">
            <div class="card detail-card mb-4 animate-in">
                <div class="card-body">
                    <div class="section-title">Informations du contrat</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-label">Entreprise</div>
                            <div class="info-value">{{ $contrat->entreprise?->nom ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Période</div>
                            <div class="info-value">{{ $contrat->date_debut?->format('d/m/Y') }} au {{ $contrat->date_fin?->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Prix par agent</div>
                            <div class="info-value">{{ number_format($contrat->prix_par_agent ?? 0, 0, ',', ' ') }} FCA/mois</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Périodicité de facturation</div>
                            <div class="info-value">{{ ucfirst($contrat->periodicite_facturation) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">TVA</div>
                            <div class="info-value">{{ $contrat->tva ?? 18 }}%</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Montant Mensuel TTC</div>
                            <div class="info-value" style="color: #198754;">
                                {{ number_format($contrat->montant_mensuel_ttc ?? 0, 0, ',', ' ') }} FCA
                            </div>
                        </div>
                    </div>

                    @if($contrat->description_prestation)
                    <div class="mt-4">
                        <div class="info-label">Description de la prestation</div>
                        <div class="info-value">{{ $contrat->description_prestation }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- SIGNATAIRES --}}
            @if($contrat->signataire_client_nom || $contrat->date_signature)
            <div class="card detail-card mb-4 animate-in">
                <div class="card-body">
                    <div class="section-title">Signataires</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-label">Signataire client</div>
                            <div class="info-value">{{ $contrat->signataire_client_nom ?? 'N/A' }}</div>
                            @if($contrat->signataire_client_fonction)
                            <small class="text-muted">{{ $contrat->signataire_client_fonction }}</small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Date de signature</div>
                            <div class="info-value">{{ $contrat->date_signature?->format('d/m/Y') ?? 'Non signé' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- COLONNE LATÉRALE --}}
        <div class="col-lg-4">
            {{-- SITES --}}
            <div class="card detail-card mb-4 animate-in">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Sites ({{ $contrat->sites->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    @if($contrat->sites->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($contrat->sites as $siteContrat)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $siteContrat->site?->nom_site ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $siteContrat->site?->adresse ?? '' }}</small>
                                </div>
                                <span class="badge bg-secondary">{{ $siteContrat->nombre_agents_site }} agents</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-geo-alt fs-1"></i>
                        <p class="mb-0 mt-2">Aucun site associé</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- FACTURES --}}
            <div class="card detail-card animate-in">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-receipt me-2"></i>Factures ({{ $contrat->factures->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    @if($contrat->factures->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($contrat->factures as $facture)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $facture->numero_facture }}</div>
                                    <small class="text-muted">{{ $facture->date_emission?->format('d/m/Y') }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold">{{ number_format($facture->montant_ttc, 0, ',', ' ') }} FCA</div>
                                    <span class="badge bg-{{ $facture->statut === 'payee' ? 'success' : ($facture->statut === 'emise' ? 'warning' : 'danger') }}">
                                        {{ $facture->statut }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-receipt fs-1"></i>
                        <p class="mb-0 mt-2">Aucune facture</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection