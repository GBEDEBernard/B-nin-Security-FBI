@extends('layouts.app')

@section('title', 'Détails du Site - Entreprise')

@push('styles')
<style>
    .detail-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #198754;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }

    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #6c757d;
    }

    .info-value {
        font-size: 0.95rem;
        color: #212529;
        font-weight: 500;
    }

    .badge-risque {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge-faible {
        background: rgba(25, 135, 84, 0.15);
        color: #198754;
    }

    .badge-moyen {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }

    .badge-haut {
        background: rgba(253, 126, 20, 0.15);
        color: #fd7e14;
    }

    .badge-critique {
        background: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Détails du Site</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.sites.index') }}">Sites</a></li>
                    <li class="breadcrumb-item active">{{ $site->nom_site }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- En-tête -->
        <div class="card detail-card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center mb-2">
                            <h4 class="mb-0 me-3">{{ $site->nom_site }}</h4>
                            <span class="badge bg-{{ $site->est_actif ? 'success' : 'secondary' }}">{{ $site->est_actif ? 'Actif' : 'Inactif' }}</span>
                        </div>
                        <div class="text-muted">{{ $site->code_site }}</div>
                        <div class="mt-2">
                            <span><i class="bi bi-person me-1"></i> {{ $site->client?->nom ?? 'N/A' }}</span>
                            <span class="ms-3"><i class="bi bi-geo me-1"></i> {{ $site->adresse }}, {{ $site->ville }}</span>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('admin.entreprise.sites.edit', $site->id) }}" class="btn btn-success"><i class="bi bi-pencil me-1"></i> Modifier</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Informations -->
                <div class="card detail-card mb-4">
                    <div class="card-body">
                        <div class="section-title">Informations du site</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-label">Client</div>
                                <div class="info-value">{{ $site->client?->nom ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Niveau de risque</div>
                                <div class="info-value"><span class="badge-risque badge-{{ $site->niveau_risque ?? 'faible' }}">{{ ucfirst($site->niveau_risque ?? 'faible') }}</span></div>
                            </div>
                            <div class="col-12">
                                <div class="info-label">Adresse</div>
                                <div class="info-value">{{ $site->adresse }}, {{ $site->ville }} {{ $site->commune ? ', ' . $site->commune : '' }} {{ $site->quartier ? ', ' . $site->quartier : '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                @if($site->contact_nom || $site->contact_telephone)
                <div class="card detail-card mb-4">
                    <div class="card-body">
                        <div class="section-title">Contact sur site</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="info-label">Nom</div>
                                <div class="info-value">{{ $site->contact_nom ?? '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Téléphone</div>
                                <div class="info-value">{{ $site->contact_telephone ?? '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $site->contact_email ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Stats -->
                <div class="card detail-card mb-4">
                    <div class="card-body">
                        <div class="section-title">Statistiques</div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Contrats associés</span>
                            <span class="fw-bold">{{ $statsSite['contrats_count'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Agents affectés</span>
                            <span class="fw-bold">{{ $statsSite['agents_count'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="card detail-card">
                    <div class="card-body">
                        <div class="section-title">Actions</div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.entreprise.sites.toggleStatut', $site->id) }}" class="btn btn-outline-{{ $site->est_actif ? 'warning' : 'success' }}">
                                <i class="bi bi-toggle-on me-1"></i> {{ $site->est_actif ? 'Désactiver' : 'Activer' }}
                            </a>
                            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="bi bi-trash me-1"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer le site</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer <strong>{{ $site->nom_site }}</strong> ?</p>
                @if($site->contrats()->count() > 0 || $site->affectations()->count() > 0)
                <div class="alert alert-warning">Ce site est associé à des contrats ou des agents.</div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                @if($site->contrats()->count() == 0 && $site->affectations()->count() == 0)
                <form action="{{ route('admin.entreprise.sites.destroy', $site->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection