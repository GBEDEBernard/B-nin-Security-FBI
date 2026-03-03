@extends('layouts.app')

@section('title', 'Détails de l\'Abonnement - ' . ($abonnement->formule_label ?? 'Abonnement'))

@push('styles')
<style>
    /* Header de profil avec gradient dynamique */
    .plan-header {
        border-radius: 20px;
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .plan-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
    }

    .plan-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .plan-basic-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 50%, #084298 100%);
    }

    .plan-premium-header {
        background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 50%, #4a278a 100%);
    }

    .plan-enterprise-header {
        background: linear-gradient(135deg, #198754 0%, #146c43 50%, #0f5132 100%);
    }

    .plan-icon {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .stat-card-ultra {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .stat-card-ultra:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
    }

    .stat-card-ultra .card-body {
        padding: 1.5rem;
    }

    .stat-icon-ultra {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .info-card-ultra {
        border: none;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .info-card-ultra .card-header {
        background: white;
        border-bottom: 2px solid #f0f0f0;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }

    .info-item-ultra {
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
    }

    .info-item-ultra:hover {
        background: #f8f9fa;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        margin-left: -0.5rem;
        margin-right: -0.5rem;
        border-radius: 8px;
    }

    .info-item-ultra:last-child {
        border-bottom: none;
    }

    .info-label-ultra {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .info-value-ultra {
        font-weight: 600;
        color: #212529;
        font-size: 1rem;
    }

    .badge-plan-ultra {
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .action-btn-ultra {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .action-btn-ultra:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    .nav-tabs-ultra .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 600;
        padding: 1rem 1.5rem;
        position: relative;
        transition: all 0.3s;
    }

    .nav-tabs-ultra .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 3px;
        background: #198754;
        transition: width 0.3s;
    }

    .nav-tabs-ultra .nav-link:hover {
        color: #198754;
        background: rgba(25, 135, 84, 0.05);
    }

    .nav-tabs-ultra .nav-link.active {
        color: #198754;
        background: transparent;
    }

    .nav-tabs-ultra .nav-link.active::after {
        width: 100%;
    }

    .table-ultra {
        margin-bottom: 0;
    }

    .table-ultra thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        padding: 1rem;
    }

    .table-ultra tbody tr:hover {
        background: #f8f9fa;
    }

    .entreprise-tag {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .entreprise-tag:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .progress-ultra {
        height: 8px;
        border-radius: 10px;
        background: #e9ecef;
        overflow: hidden;
    }

    .progress-bar-ultra {
        height: 100%;
        border-radius: 10px;
        transition: width 1s ease;
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-credit-card-2-front-fill me-2 text-success"></i>
                    Détails de l'Abonnement
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.abonnements.index') }}">Abonnements</a></li>
                    <li class="breadcrumb-item active">{{ $abonnement->formule_label }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        <!-- En-tête de profil avec gradient dynamique selon le plan -->
        @php
        $planClass = match($abonnement->formule) {
        'basic' => 'plan-basic-header',
        'premium' => 'plan-premium-header',
        'enterprise' => 'plan-enterprise-header',
        default => 'plan-basic-header'
        };

        $planColor = match($abonnement->formule) {
        'basic' => '#0d6efd',
        'premium' => '#6f42c1',
        'enterprise' => '#198754',
        default => '#0d6efd'
        };
        @endphp

        <div class="plan-header {{ $planClass }} mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="plan-icon">
                        @switch($abonnement->formule)
                        @case('basic')
                        <i class="bi bi-star"></i>
                        @break
                        @case('premium')
                        <i class="bi bi-gem"></i>
                        @break
                        @case('enterprise')
                        <i class="bi bi-building"></i>
                        @break
                        @default
                        <i class="bi bi-credit-card"></i>
                        @endswitch
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="mb-1 fw-bold">{{ $abonnement->formule_label }}</h2>
                            <p class="mb-0 opacity-75">
                                @if($abonnement->description)
                                {{ $abonnement->description }}
                                @else
                                Plan d'abonnement pour {{ $abonnement->employes_min ?? 1 }} - {{ $abonnement->employes_max ?? 10 }} employés
                                @endif
                            </p>
                            <div class="mt-3 d-flex gap-2 flex-wrap">
                                @if($abonnement->est_en_essai)
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-hourglass-split me-1"></i> En essai
                                </span>
                                @elseif($abonnement->est_active && $abonnement->statut === 'actif')
                                <span class="badge bg-white text-success">
                                    <i class="bi bi-check-circle me-1"></i> Actif
                                </span>
                                @elseif($abonnement->statut === 'expire')
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i> Expiré
                                </span>
                                @elseif($abonnement->statut === 'suspendu')
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-pause-circle me-1"></i> Suspendu
                                </span>
                                @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-dash-circle me-1"></i> {{ $abonnement->statut_label ?? 'Inactif' }}
                                </span>
                                @endif
                                <span class="badge bg-white bg-opacity-25">
                                    <i class="bi bi-calendar me-1"></i>
                                    @if($abonnement->duree_mois)
                                    {{ $abonnement->duree_mois }} mois
                                    @else
                                    Illimité
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fs-1 fw-bold">{{ number_format($abonnement->montant_mensuel ?? 0, 0, ',', ' ') }}</div>
                            <div class="opacity-75">FCFA / mois</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cartes de statistiques -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card-ultra card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold">Entreprises</p>
                                <h3 class="mb-0 fw-bold">{{ $abonnement->entreprises_count ?? 0 }}</h3>
                            </div>
                            <div class="stat-icon-ultra bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-building"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress-ultra">
                                <div class="progress-bar-ultra bg-primary" style="width: {{ min(($abonnement->entreprises_count ?? 0) * 20, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card-ultra card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold">Agents Max</p>
                                <h3 class="mb-0 fw-bold">{{ $abonnement->employes_max ?? '∞' }}</h3>
                            </div>
                            <div class="stat-icon-ultra bg-info bg-opacity-10 text-info">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Nombre maximum d'employés</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card-ultra card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold">Sites Max</p>
                                <h3 class="mb-0 fw-bold">{{ $abonnement->sites_max ?? '∞' }}</h3>
                            </div>
                            <div class="stat-icon-ultra bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Nombre maximum de sites</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card-ultra card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold">Jours Restants</p>
                                <h3 class="mb-0 fw-bold">{{ $abonnement->jours_restants ?? '∞' }}</h3>
                            </div>
                            <div class="stat-icon-ultra bg-success bg-opacity-10 text-success">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            @if($abonnement->jours_restants && $abonnement->jours_restants < 30)
                                <span class="text-warning small">
                                <i class="bi bi-exclamation-triangle me-1"></i> Expire bientôt
                                </span>
                                @elseif($abonnement->jours_restants)
                                <span class="text-success small">En cours</span>
                                @else
                                <span class="text-muted small">Illimité</span>
                                @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglets d'informations -->
        <div class="card info-card-ultra mb-4">
            <div class="card-header p-0">
                <ul class="nav nav-tabs-ultra" id="abonnementTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                            <i class="bi bi-info-circle me-2"></i>Détails
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="entreprises-tab" data-bs-toggle="tab" data-bs-target="#entreprises" type="button" role="tab">
                            <i class="bi bi-building me-2"></i>Entreprises ({{ $abonnement->entreprises_count ?? 0 }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="factures-tab" data-bs-toggle="tab" data-bs-target="#factures" type="button" role="tab">
                            <i class="bi bi-receipt me-2"></i>Factures ({{ $factures->count() ?? 0 }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="actions-tab" data-bs-toggle="tab" data-bs-target="#actions" type="button" role="tab">
                            <i class="bi bi-gear me-2"></i>Actions
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="abonnementTabsContent">

                    <!-- Onglet Détails -->
                    <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                        <div class="row g-4">
                            <!-- Colonne de gauche -->
                            <div class="col-md-6">
                                <div class="info-card-ultra card h-100">
                                    <div class="card-header">
                                        <i class="bi bi-credit-card me-2 text-success"></i>Informations du Plan
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="info-item-ultra">
                                            <div class="info-label-ultra">Formule</div>
                                            <div class="info-value-ultra">
                                                <span class="badge-plan-ultra bg-{{ $abonnement->formule === 'basic' ? 'primary' : ($abonnement->formule === 'premium' ? 'purple' : 'success') }}">
                                                    {{ $abonnement->formule_label }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="info-item-ultra">
                                            <div class="info-label-ultra">Description</div>
                                            <div class="info-value-ultra">{{ $abonnement->description ?? 'Aucune description' }}</div>
                                        </div>
                                        <div class="info-item-ultra">
                                            <div class="info-label-ultra">Nombre d'agents</div>
                                            <div class="info-value-ultra">{{ $abonnement->employes_min ?? 1 }} - {{ $abonnement->employes_max ?? 10 }} employés</div>
                                        </div>
                                        <div class="info-item-ultra">
                                            <div class="info-label-ultra">Nombre de sites</div>
                                            <div class="info-value-ultra">{{ $abonnement->sites_max ?? 1 }} site(s) maximum</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Colonne de droite -->
                            <div class="col-md-6">
                                <div class="info-card-ultra card h-100">
                                    <div class="card-header">
                                        <i class="bi bi-calendar me-2 text-success"></i>Période & Facturation
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="info-item-ultra">
                                            <div class="info-label-ultra">Date de début</div>
                                            <div class="info-value-ultra">{{ $abonnement->date_debut?->format('d/m/Y') ?? 'Non défini' }}</div>
                                        </div>
                                        <div class="info-item-ultra">
                                            <div class="info-label-ultra">Date de fin</div>
                                            <div class="info-value-ultra">
                                                @if($abonnement->date_fin)
                                                @if($abonnement->date_fin->isPast())
                                                <span class="text-danger">{{ $abonnement->date_fin->format('d/m/Y') }} (Expiré)</span>
                                                @elseif($abonnement->date_fin->diffInDays(now()) < 30)
                                                    <span class="text-warning">{{ $abonnement->date_fin->format('d/m/Y') }} (Expire bientôt)</span>
                                                    @else
                                                    {{ $abonnement->date_fin->format('d/m/Y') }}
                                                    @endif
                                                    @else
                                                    <span class="text-success">Illimitée</span>
                                                    @endif
                                            </div>
                                        </div>
                                        <div class="info-item-ultra">
                                            <div class="info-label-ultra">Fin de l'essai</div>
                                            <div class="info-value-ultra">
                                                @if($abonnement->date_fin_essai)
                                                {{ $abonnement->date_fin_essai->format('d/m/Y') }}
                                                @if($abonnement->date_fin_essai->isPast())
                                                <span class="badge bg-danger ms-2">Expiré</span>
                                                @endif
                                                @else
                                                <span class="text-muted">Non défini</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="info-item-ultra">
                                            <div class="info-label-ultra">Cycle de facturation</div>
                                            <div class="info-value-ultra">{{ ucfirst($abonnement->cycle_facturation ?? 'mensuel') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tarification -->
                            <div class="col-md-6">
                                <div class="info-card-ultra card h-100">
                                    <div class="card-header">
                                        <i class="bi bi-cash-stack me-2 text-success"></i>Tarification
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="info-item-ultra">
                                            <div class="info-label-ultra">Montant mensuel</div>
                                            <div class="info-value-ultra text-success fs-4">{{ number_format($abonnement->montant_mensuel ?? 0, 0, ',', ' ') }} FCAF</div>
                                        </div>
                                        <div class="info-item-ultra">
                                            <div class="info-label-ultra">Montant période</div>
                                            <div class="info-value-ultra">{{ number_format($abonnement->montant_periode ?? 0, 0, ',', ' ') }} FCAF</div>
                                        </div>
                                        <div class="info-item-ultra">
                                            <div class="info-label-ultra">Mode de paiement</div>
                                            <div class="info-value-ultra">{{ $abonnement->mode_paiement ?? 'Non défini' }}</div>
                                        </div>
                                        <div class="info-item-ultra">
                                            <div class="info-label-ultra">Renouvellement</div>
                                            <div class="info-value-ultra">
                                                @if($abonnement->est_renouvele_auto)
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Automatique</span>
                                                @else
                                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i> Manuel</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="col-md-6">
                                <div class="info-card-ultra card h-100">
                                    <div class="card-header">
                                        <i class="bi bi-sticky me-2 text-success"></i>Notes
                                    </div>
                                    <div class="card-body">
                                        @if($abonnement->notes)
                                        <p class="mb-0">{{ $abonnement->notes }}</p>
                                        @else
                                        <p class="text-muted mb-0">Aucune note</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Onglet Entreprises -->
                    <div class="tab-pane fade" id="entreprises" role="tabpanel" aria-labelledby="entreprises-tab">
                        @if($abonnement->entreprises->count() > 0)
                        <div class="row g-3">
                            @foreach($abonnement->entreprises as $entreprise)
                            <div class="col-md-6 col-lg-4">
                                <div class="entreprise-tag p-3">
                                    <div class="d-flex align-items-center">
                                        @if($entreprise->logo)
                                        <img src="{{ $entreprise->logoUrl }}" alt="Logo" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                        <div class="rounded bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">{{ $entreprise->nom_entreprise }}</div>
                                            <small class="text-muted">{{ $entreprise->email }}</small>
                                        </div>
                                        <div class="ms-2">
                                            @if($entreprise->est_active)
                                            <span class="badge bg-success">Actif</span>
                                            @else
                                            <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-3 d-flex gap-2">
                                        <a href="{{ route('admin.superadmin.entreprises.show', $entreprise->id) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                                            <i class="bi bi-eye me-1"></i> Voir
                                        </a>
                                        <form action="{{ route('admin.superadmin.abonnements.retirer', ['id' => $abonnement->id, 'entrepriseId' => $entreprise->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Retirer l'abonnement">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-building fs-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Aucune entreprise</h5>
                            <p class="text-muted">Aucune entreprise n'est liée à cet abonnement.</p>
                        </div>
                        @endif

                        <!-- Formulaire pour assigner une entreprise -->
                        <div class="mt-4 border-top pt-4">
                            <h6 class="mb-3"><i class="bi bi-plus-circle me-2"></i>Assigner une entreprise</h6>
                            <form action="{{ route('admin.superadmin.abonnements.assigner', $abonnement->id) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <select class="form-select" name="entreprise_id" required>
                                    <option value="">Sélectionner une entreprise...</option>
                                    @foreach(\App\Models\Entreprise::whereNull('abonnement_id')->orWhere('abonnement_id', $abonnement->id)->get() as $entreprise)
                                    <option value="{{ $entreprise->id }}">{{ $entreprise->nom_entreprise }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-plus-lg me-1"></i> Assigner
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Onglet Factures -->
                    <div class="tab-pane fade" id="factures" role="tabpanel" aria-labelledby="factures-tab">
                        @if($factures->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-ultra">
                                <thead>
                                    <tr>
                                        <th>N° Facture</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($factures as $facture)
                                    <tr>
                                        <td><span class="fw-semibold">{{ $facture->numero_facture ?? $facture->id }}</span></td>
                                        <td>{{ $facture->created_at->format('d/m/Y') }}</td>
                                        <td class="fw-bold">{{ number_format($facture->montant_ttc ?? 0, 0, ',', ' ') }} FCAF</td>
                                        <td>
                                            @if($facture->statut == 'payee')
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Payée</span>
                                            @elseif($facture->statut == 'en_attente')
                                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> En attente</span>
                                            @else
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Impayée</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-receipt fs-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Aucune facture</h5>
                            <p class="text-muted">Aucune facture n'est associée à cet abonnement.</p>
                        </div>
                        @endif
                    </div>

                    <!-- Onglet Actions -->
                    <div class="tab-pane fade" id="actions" role="tabpanel" aria-labelledby="actions-tab">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-card-ultra card">
                                    <div class="card-header">
                                        <i class="bi bi-lightning me-2 text-warning"></i>Actions rapides
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-3">
                                            @if($abonnement->est_active)
                                            <form action="{{ route('admin.superadmin.abonnements.suspend', $abonnement->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-warning action-btn-ultra w-100">
                                                    <i class="bi bi-pause-circle me-2"></i> Suspendre l'abonnement
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{ route('admin.superadmin.abonnements.activate', $abonnement->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success action-btn-ultra w-100">
                                                    <i class="bi bi-play-circle me-2"></i> Activer l'abonnement
                                                </button>
                                            </form>
                                            @endif

                                            <button type="button" class="btn btn-outline-primary action-btn-ultra" data-bs-toggle="modal" data-bs-target="#renewModal">
                                                <i class="bi bi-arrow-repeat me-2"></i> Renouveler l'abonnement
                                            </button>

                                            <form action="{{ route('admin.superadmin.abonnements.resilier', $abonnement->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger action-btn-ultra w-100" onclick="return confirm('Êtes-vous sûr de vouloir résilier cet abonnement?');">
                                                    <i class="bi bi-x-octagon me-2"></i> Résilier l'abonnement
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card-ultra card">
                                    <div class="card-header">
                                        <i class="bi me-2" style="color: #6f42c1;"></i>Autres actions
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-3">
                                            <a href="{{ route('admin.superadmin.abonnements.edit', $abonnement->id) }}" class="btn btn-outline-primary action-btn-ultra">
                                                <i class="bi bi-pencil me-2"></i> Modifier l'abonnement
                                            </a>
                                            <button class="btn btn-outline-info action-btn-ultra" onclick="window.print()">
                                                <i class="bi bi-printer me-2"></i> Imprimer les détails
                                            </button>
                                            <button type="button" class="btn btn-outline-danger action-btn-ultra" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                <i class="bi bi-trash me-2"></i> Supprimer l'abonnement
                                            </button>
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
</div>
<!--end::App Content-->

<!-- Modal de renouvellement -->
<div class="modal fade" id="renewModal" tabindex="-1" aria-labelledby="renewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="renewModalLabel">Renouveler l'abonnement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.superadmin.abonnements.renew', $abonnement->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="date_fin" class="form-label">Nouvelle date de fin</label>
                        <input type="date" class="form-control" id="date_fin" name="date_fin" required>
                    </div>
                    <div class="mb-3">
                        <label for="montant_mensuel" class="form-label">Montant mensuel (F CFA)</label>
                        <input type="number" class="form-control" id="montant_mensuel" name="montant_mensuel" value="{{ $abonnement->montant_mensuel }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="cycle_facturation" class="form-label">Cycle de facturation</label>
                        <select class="form-select" id="cycle_facturation" name="cycle_facturation">
                            <option value="mensuel" {{ $abonnement->cycle_facturation == 'mensuel' ? 'selected' : '' }}>Mensuel</option>
                            <option value="trimestriel" {{ $abonnement->cycle_facturation == 'trimestriel' ? 'selected' : '' }}>Trimestriel</option>
                            <option value="semestriel" {{ $abonnement->cycle_facturation == 'semestriel' ? 'selected' : '' }}>Semestriel</option>
                            <option value="annuel" {{ $abonnement->cycle_facturation == 'annuel' ? 'selected' : '' }}>Annuel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Renouveler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'abonnement <strong>{{ $abonnement->formule_label }}</strong> ?</p>
                @if($abonnement->entreprises_count > 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ $abonnement->entreprises_count }} entreprise(s) sont liées à cet abonnement.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.superadmin.abonnements.destroy', $abonnement->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection