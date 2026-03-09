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
        color: #0d6efd;
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
        background: rgba(13, 110, 253, 0.15);
        color: #0d6efd;
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

    .stat-card {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
        border-radius: 12px;
        padding: 1.25rem;
    }

    .stat-card.success {
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
    }

    .stat-card.warning {
        background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%);
    }

    .stat-card.danger {
        background: linear-gradient(135deg, #dc3545 0%, #bb2d3b 100%);
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

    .contrat-item {
        border-left: 3px solid #0d6efd;
        padding-left: 1rem;
        margin-bottom: 1rem;
    }

    .progress-couverture {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
    }

    .progress-couverture .progress-bar {
        border-radius: 4px;
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
                <!-- Client lié -->
                <div class="card detail-card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="section-title mb-0">Client lié à ce site</div>
                            @if($site->client)
                            <a href="{{ route('admin.entreprise.clients.show', $site->client->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-arrow-right me-1"></i> Voir le client
                            </a>
                            @endif
                        </div>
                        @if($site->client)
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-label">Nom du client</div>
                                <div class="info-value">{{ $site->client->nom }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Type</div>
                                <div class="info-value">{{ $site->client->type_label ?? ucfirst($site->client->type_client) }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $site->client->email ?? '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Téléphone</div>
                                <div class="info-value">{{ $site->client->telephone ?? '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Ville</div>
                                <div class="info-value">{{ $site->client->ville ?? '-' }}</div>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-warning">Aucun client lié à ce site.</div>
                        @endif
                    </div>
                </div>

                <!-- Contrats liés -->
                <div class="card detail-card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="section-title mb-0">Contrats liés à ce site</div>
                            <span class="badge bg-primary">{{ $contrats->count() }} contrat(s)</span>
                        </div>
                        
                        @if($contrats->count() > 0)
                            @foreach($contrats as $contrat)
                            <div class="contrat-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $contrat->intitule }}</h6>
                                        <div class="text-muted small">
                                            <i class="bi bi-hash me-1"></i>{{ $contrat->numero_contrat }}
                                            <span class="ms-2">
                                                <span class="badge bg-{{ $contrat->statut === 'en_cours' ? 'success' : 'secondary' }}">
                                                    {{ $contrat->statut_label ?? ucfirst($contrat->statut) }}
                                                </span>
                                            </span>
                                        </div>
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $contrat->date_debut?->format('d/m/Y') ?? 'N/A' }} - {{ $contrat->date_fin?->format('d/m/Y') ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="info-label">Agents requis</div>
                                        <div class="info-value">
                                            <span class="badge bg-{{ $contrat->nombre_agents_requis > 0 ? 'primary' : 'secondary' }}">
                                                {{ $contrat->nombre_agents_requis ?? 0 }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                        <div class="alert alert-info">Aucun contrat associé à ce site.</div>
                        @endif
                    </div>
                </div>

                <!-- Agents affectés -->
                <div class="card detail-card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="section-title mb-0">Agents affectés à ce site</div>
                            @if($employesDisponibles->count() > 0 && $statsSite['agents_manquants'] > 0)
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addAgentModal">
                                <i class="bi bi-person-plus me-1"></i> Ajouter un agent
                            </button>
                            @endif
                        </div>
                        
                        <!-- Statistiques agents -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="small opacity-75">Agents requis</div>
                                    <div class="fs-4 fw-bold">{{ $statsSite['agents_requis'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card success">
                                    <div class="small opacity-75">Agents affectés</div>
                                    <div class="fs-4 fw-bold">{{ $statsSite['agents_count'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card {{ $statsSite['agents_manquants'] > 0 ? 'danger' : 'success' }}">
                                    <div class="small opacity-75">Agents manquants</div>
                                    <div class="fs-4 fw-bold">{{ $statsSite['agents_manquants'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Barre de progression -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small text-muted">Couverture en agents</span>
                                <span class="small fw-bold">{{ $statsSite['pourcentage_couverture'] }}%</span>
                            </div>
                            <div class="progress-couverture">
                                <div class="progress-bar bg-{{ $statsSite['pourcentage_couverture'] >= 100 ? 'success' : ($statsSite['pourcentage_couverture'] >= 50 ? 'warning' : 'danger') }}" 
                                     style="width: {{ $statsSite['pourcentage_couverture'] }}%"></div>
                            </div>
                        </div>

                        <!-- Liste des agents -->
                        @if($affectations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Agent</th>
                                            <th>Poste</th>
                                            <th>Période</th>
                                            <th>Statut</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($affectations as $affectation)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="agent-avatar me-2">
                                                        {{ $affectation->employe?->initiales ?? 'N/A' }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $affectation->employe?->nomComplet ?? 'N/A' }}</div>
                                                        <div class="small text-muted">{{ $affectation->employe?->matricule ?? '' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $affectation->role_site ?? $affectation->poste ?? '-' }}</td>
                                            <td>
                                                <small>
                                                    {{ $affectation->date_debut?->format('d/m/Y') ?? '' }}<br>
                                                    {{ $affectation->date_fin?->format('d/m/Y') ?? 'En cours' }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $affectation->statut === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($affectation->statut) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.entreprise.employes.show', $affectation->employe->id) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Aucun agent n'est affecté à ce site pour le moment.
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informations du site -->
                <div class="card detail-card mb-4">
                    <div class="card-body">
                        <div class="section-title">Informations du site</div>
                        <div class="row g-3">
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
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Agents affectés</span>
                            <span class="fw-bold">{{ $statsSite['agents_count'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Agents requis</span>
                            <span class="fw-bold">{{ $statsSite['agents_requis'] }}</span>
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

<!-- Modal Ajouter Agent -->
@if($employesDisponibles->count() > 0)
<div class="modal fade" id="addAgentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un agent au site</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.entreprise.affectations.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Agent</label>
                        <select name="employe_id" class="form-select" required>
                            <option value="">Sélectionner un agent</option>
                            @foreach($employesDisponibles as $employe)
                            <option value="{{ $employe->id }}">{{ $employe->nomComplet }} ({{ $employe->matricule }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contrat</label>
                        <select name="contrat_id" class="form-select" required>
                            <option value="">Sélectionner un contrat</option>
                            @foreach($contrats as $contrat)
                            <option value="{{ $contrat->id }}">{{ $contrat->intitule }} ({{ $contrat->numero_contrat }})</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="site_id" value="{{ $site->id }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date début</label>
                            <input type="date" name="date_debut" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date fin</label>
                            <input type="date" name="date_fin" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Poste</label>
                        <input type="text" name="poste" class="form-control" placeholder="Agent de sécurité" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Instructions</label>
                        <textarea name="instructions" class="form-control" rows="3" placeholder="Instructions particulières..."></textarea>
                    </div>
                    <input type="hidden" name="statut" value="active">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter l'agent</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Modal Suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer le site</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

