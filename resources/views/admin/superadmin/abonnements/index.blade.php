@extends('layouts.app')

@section('title', 'Gestion des Abonnements - Super Admin')

@push('styles')
<style>
    .plan-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .plan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .plan-card .card-header {
        border: none;
        padding: 1.5rem;
    }

    .plan-card .card-body {
        padding: 1.5rem;
    }

    .plan-basic {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
    }

    .plan-premium {
        background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
        color: white;
    }

    .plan-enterprise {
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
        color: white;
    }

    .price-tag {
        font-size: 2.5rem;
        font-weight: 700;
    }

    .price-tag .currency {
        font-size: 1.5rem;
        vertical-align: super;
    }

    .feature-list li {
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .feature-list li:last-child {
        border-bottom: none;
    }

    .stats-card {
        border-radius: 12px;
        border: none;
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: translateY(-2px);
    }

    .table-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    }

    .table-card .card-header {
        background: white;
        border-bottom: 2px solid #f0f0f0;
        padding: 1rem 1.5rem;
    }

    .table-card .card-body {
        padding: 0;
    }

    .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        padding: 1rem;
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    .badge-plan {
        padding: 0.5rem 1rem;
        font-weight: 600;
        border-radius: 50px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .action-btn:hover {
        transform: scale(1.1);
    }

    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }

    .status-active {
        background: #198754;
    }

    .status-inactive {
        background: #6c757d;
    }

    .status-expired {
        background: #dc3545;
    }

    .status-trial {
        background: #ffc107;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper p-4">
    <!-- En-tête de page -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="bi bi-credit-card-2-front-fill text-primary me-2"></i>
                        Gestion des Abonnements
                    </h1>
                    <p class="text-muted mb-0">Gérez les plans d'abonnement et leurs limites</p>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('admin.superadmin.abonnements.create') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-lg me-2"></i>Nouveau Plan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="container-fluid mb-4">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card stats-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-white-50">Total Plans</span>
                                <h2 class="mb-0 fw-bold">{{ $stats['total'] }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="bi bi-layers"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-white-50">Actifs</span>
                                <h2 class="mb-0 fw-bold">{{ $stats['actifs'] }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-dark-50">En Essai</span>
                                <h2 class="mb-0 fw-bold">{{ $stats['en_essai'] }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-secondary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-white-50">Revenu Mensuel</span>
                                <h4 class="mb-0 fw-bold">{{ number_format($stats['revenu_mensuel'] ?? 0, 0, ',', ' ') }} F</h4>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Plans Cards -->
    <div class="container-fluid mb-4">
        <h5 class="mb-3"><i class="bi bi-grid-3x3-gap me-2"></i>Plans d'Abonnement</h5>
        <div class="row g-4">
            @foreach($abonnements as $abonnement)
            <div class="col-md-4">
                <div class="card plan-card plan-{{ $abonnement->formule }}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-white text-{{ $abonnement->formule === 'basic' ? 'primary' : ($abonnement->formule === 'premium' ? 'purple' : 'success') }} badge-plan">
                                {{ strtoupper($abonnement->formule) }}
                            </span>
                        </div>
                        <div>
                            @if($abonnement->est_active)
                            <span class="badge bg-success">Actif</span>
                            @else
                            <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <div class="price-tag mb-3">
                            <span class="currency">{{ number_format($abonnement->montant_mensuel, 0, ',', ' ') }}</span>
                            <span class="fs-6">F<span class="text-white-50">/mois</span></span>
                        </div>

                        <ul class="feature-list list-unstyled text-start mb-0">
                            <li>
                                <i class="bi bi-people me-2"></i>
                                {{ $abonnement->employes_min }} - {{ $abonnement->employes_max }} employés
                            </li>
                            <li>
                                <i class="bi bi-building me-2"></i>
                                {{ $abonnement->sites_max ?? 1 }} sites maximum
                            </li>
                            <li>
                                <i class="bi bi-calendar me-2"></i>
                                @if($abonnement->duree_mois)
                                Durée: {{ $abonnement->duree_mois }} mois
                                @else
                                Durée illimitée
                                @endif
                            </li>
                            <li>
                                <i class="bi bi-arrow-repeat me-2"></i>
                                @if($abonnement->est_renouvele_auto)
                                Renouvellement auto
                                @else
                                Renouvellement manuel
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between">
                        <a href="{{ route('admin.superadmin.abonnements.show', $abonnement->id) }}" class="btn btn-sm btn-light">
                            <i class="bi bi-eye me-1"></i>Détails
                        </a>
                        <a href="{{ route('admin.superadmin.abonnements.edit', $abonnement->id) }}" class="btn btn-sm btn-light">
                            <i class="bi bi-pencil me-1"></i>Modifier
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Tableau détaillé -->
    <div class="container-fluid">
        <div class="card table-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste Détallée des Abonnements</h5>
                    <span class="badge bg-primary">{{ $abonnements->count() }} plans</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Formule</th>
                                <th>Employés</th>
                                <th>Sites Max</th>
                                <th>Prix/Mois</th>
                                <th>Durée</th>
                                <th>Cycle</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($abonnements as $abonnement)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="status-indicator status-{{ $abonnement->est_active ? 'active' : 'inactive' }}"></span>
                                        <strong>{{ ucfirst($abonnement->formule) }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-people me-1"></i>
                                        {{ $abonnement->employes_min }} - {{ $abonnement->employes_max }}
                                    </span>
                                </td>
                                <td>{{ $abonnement->sites_max ?? '-' }}</td>
                                <td>
                                    <strong class="text-success">{{ number_format($abonnement->montant_mensuel, 0, ',', ' ') }} F</strong>
                                </td>
                                <td>
                                    @if($abonnement->duree_mois)
                                    <span class="badge bg-info">{{ $abonnement->duree_mois }} mois</span>
                                    @else
                                    <span class="badge bg-success">Illimitée</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst($abonnement->cycle_facturation ?? 'Mensuel') }}</td>
                                <td>
                                    @if($abonnement->est_en_essai)
                                    <span class="badge bg-warning text-dark">Essai</span>
                                    @elseif($abonnement->est_active && $abonnement->statut === 'actif')
                                    <span class="badge bg-success">Actif</span>
                                    @elseif($abonnement->statut === 'expire')
                                    <span class="badge bg-danger">Expiré</span>
                                    @elseif($abonnement->statut === 'suspendu')
                                    <span class="badge bg-warning text-dark">Suspendu</span>
                                    @elseif($abonnement->statut === 'resilie')
                                    <span class="badge bg-secondary">Résilié</span>
                                    @else
                                    <span class="badge bg-secondary">{{ $abonnement->statut }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.superadmin.abonnements.show', $abonnement->id) }}" class="btn btn-sm btn-outline-info action-btn" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.superadmin.abonnements.edit', $abonnement->id) }}" class="btn btn-sm btn-outline-primary action-btn" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($abonnement->est_active)
                                        <form action="{{ route('admin.superadmin.abonnements.suspend', $abonnement->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning action-btn" title="Suspendre">
                                                <i class="bi bi-pause-circle"></i>
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('admin.superadmin.abonnements.activate', $abonnement->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success action-btn" title="Activer">
                                                <i class="bi bi-play-circle"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('admin.superadmin.abonnements.destroy', $abonnement->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet abonnement?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger action-btn" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Aucun abonnement trouvé
                                        <a href="{{ route('admin.superadmin.abonnements.create') }}" class="btn btn-primary btn-sm ms-2">
                                            Créer un abonnement
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection