@extends('layouts.app')

@section('title', 'Contrats - Super Admin')

@push('styles')
<style>
    .contract-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .contract-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.12);
    }
    
    .stat-card {
        border: none;
        border-radius: 12px;
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .badge-statut {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.75rem;
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
    
    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .data-table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        color: #495057;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .data-table tbody tr {
        transition: background 0.2s ease;
    }
    
    .data-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    .entreprise-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        background: rgba(25, 135, 84, 0.1);
        border-radius: 20px;
        font-size: 0.8rem;
        color: #198754;
    }
    
    .search-box {
        border-radius: 10px;
        border: 1.5px solid #e9ecef;
        padding: 0.6rem 1rem;
        transition: all 0.2s ease;
    }
    
    .search-box:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }
    
    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
    }
    
    .page-animate {
        animation: fadeIn 0.4s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-file-earmark-text-fill me-2"></i>Contrats</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Contrats</li>
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

        <!-- Cartes de statistiques -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card card bg-primary bg-opacity-10 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Total Contrats</p>
                                <h3 class="mb-0 fw-bold">{{ \App\Models\ContratPrestation::count() }}</h3>
                            </div>
                            <div class="stat-icon bg-primary bg-opacity-25 text-primary">
                                <i class="bi bi-file-earmark-text fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card bg-success bg-opacity-10 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Actifs</p>
                                <h3 class="mb-0 fw-bold text-success">{{ \App\Models\ContratPrestation::where('statut', 'en_cours')->count() }}</h3>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-25 text-success">
                                <i class="bi bi-check-circle fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card bg-warning bg-opacity-10 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">En attente</p>
                                <h3 class="mb-0 fw-bold text-warning">{{ \App\Models\ContratPrestation::where('statut', 'brouillon')->count() }}</h3>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-25 text-warning">
                                <i class="bi bi-clock fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card bg-info bg-opacity-10 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Résiliés</p>
                                <h3 class="mb-0 fw-bold text-danger">{{ \App\Models\ContratPrestation::where('statut', 'resilie')->count() }}</h3>
                            </div>
                            <div class="stat-icon bg-danger bg-opacity-25 text-danger">
                                <i class="bi bi-x-circle fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et Recherche -->
        <div class="card contract-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.superadmin.contrats.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="entreprise_id" class="form-select">
                            <option value="">Toutes les entreprises</option>
                            @foreach($entreprises as $entreprise)
                            <option value="{{ $entreprise->id }}" {{ request('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                                {{ $entreprise->nom_entreprise }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="suspendu" {{ request('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                            <option value="resilie" {{ request('statut') == 'resilie' ? 'selected' : '' }}>Résilié</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-filter me-1"></i> Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des contrats -->
        <div class="card contract-card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des contrats</h5>
                <a href="{{ route('admin.superadmin.contrats.create') }}" class="btn btn-success btn-action">
                    <i class="bi bi-plus-circle me-1"></i> Nouveau contrat
                </a>
            </div>
            <div class="card-body p-0">
                @if($contrats->count() > 0)
                <div class="table-responsive">
                    <table class="table data-table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>N° Contrat</th>
                                <th>Entreprise</th>
                                <th>Client</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contrats as $contrat)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $contrat->numero_contrat }}</span>
                                </td>
                                <td>
                                    <div class="entreprise-badge">
                                        <i class="bi bi-building"></i>
                                        {{ $contrat->entreprise?->nom_entreprise ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <span class="text-info fw-bold small">{{ strtoupper(substr($contrat->client?->nom ?? 'N', 0, 2)) }}</span>
                                        </div>
                                        {{ $contrat->client?->nom ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>{{ $contrat->date_debut?->format('d/m/Y') ?? 'N/A' }}</td>
                                <td>
                                    @if($contrat->estExpire())
                                    <span class="text-danger">{{ $contrat->date_fin?->format('d/m/Y') }}</span>
                                    @else
                                    {{ $contrat->date_fin?->format('d/m/Y') }}
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ number_format($contrat->montant_mensuel_ht ?? 0, 0, ',', ' ') }}</span>
                                    <small class="text-muted">FCFA/mois</small>
                                </td>
                                <td>
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
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.superadmin.contrats.show', $contrat->id) }}" class="btn btn-sm btn-outline-primary btn-action" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.superadmin.contrats.edit', $contrat->id) }}" class="btn btn-sm btn-outline-success btn-action" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $contrat->id }}" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal de confirmation de suppression -->
                                    <div class="modal fade" id="deleteModal{{ $contrat->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $contrat->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $contrat->id }}">Confirmer la suppression</h5>
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
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-file-earmark-text fs-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Aucun contrat trouvé</h5>
                    <p class="text-muted">Commencez par créer votre premier contrat.</p>
                    <a href="{{ route('admin.superadmin.contrats.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i> Créer un contrat
                    </a>
                </div>
                @endif
            </div>
            @if($contrats->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Affichage de {{ $contrats->firstItem() }} à {{ $contrats->lastItem() }} sur {{ $contrats->total() }} résultats
                    </div>
                    {{ $contrats->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<!--end::App Content-->
@endsection

