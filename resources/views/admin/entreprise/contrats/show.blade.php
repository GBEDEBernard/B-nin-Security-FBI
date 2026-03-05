@extends('layouts.app')

@section('title', 'Détails du Contrat - Entreprise')

@push('styles')
<style>
    .detail-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
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
        color: #198754;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }

    .site-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.2s;
    }

    .site-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
    }

    .action-btn {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-file-earmark-text-fill me-2"></i>Détails du Contrat</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.contrats.index') }}">Contrats</a></li>
                    <li class="breadcrumb-item active">{{ $contrat->numero_contrat }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- En-tête du contrat -->
        <div class="card detail-card mb-4">
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
                            <span class="me-3"><i class="bi bi-person me-1"></i> {{ $contrat->client?->nom ?? 'N/A' }}</span>
                            <span class="me-3"><i class="bi bi-calendar me-1"></i> {{ $contrat->date_debut?->format('d/m/Y') }} - {{ $contrat->date_fin?->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="btn-group">
                            <a href="{{ route('admin.entreprise.contrats.edit', $contrat->id) }}" class="btn btn-success action-btn">
                                <i class="bi bi-pencil me-1"></i> Modifier
                            </a>
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#statusModal">
                                        <i class="bi bi-toggle-on me-2"></i> Changer le statut
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.entreprise.contrats.dupliquer', $contrat->id) }}">
                                        <i class="bi bi-copy me-2"></i> Dupliquer
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="bi bi-trash me-2"></i> Supprimer
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
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
                        <div class="info-value">{{ $contrat->nombre_agents_requis }}</div>
                        <small class="text-muted">personnes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card bg-warning bg-opacity-10 h-100">
                    <div class="card-body">
                        <div class="info-label">Sites</div>
                        <div class="info-value">{{ $statsContrat['sites_count'] }}</div>
                        <small class="text-muted">associés</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Informations principales -->
            <div class="col-lg-8">
                <div class="card detail-card mb-4">
                    <div class="card-body">
                        <div class="section-title">Informations du contrat</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-label">Client</div>
                                <div class="info-value">{{ $contrat->client?->nom ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Période</div>
                                <div class="info-value">{{ $contrat->date_debut?->format('d/m/Y') }} au {{ $contrat->date_fin?->format('d/m/Y') }}</div>
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
                                <div class="info-label">Renouvelable</div>
                                <div class="info-value">{{ $contrat->est_renouvelable ? 'Oui' : 'Non' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Durée du préavis</div>
                                <div class="info-value">{{ $contrat->duree_preavis ?? 30 }} jours</div>
                            </div>
                        </div>

                        @if($contrat->description_prestation)
                        <div class="mt-4">
                            <div class="info-label">Description de la prestation</div>
                            <div class="info-value">{{ $contrat->description_prestation }}</div>
                        </div>
                        @endif

                        @if($contrat->conditions_particulieres)
                        <div class="mt-3">
                            <div class="info-label">Conditions particulières</div>
                            <div class="info-value">{{ $contrat->conditions_particulieres }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Signataires -->
                @if($contrat->signataire_client_nom || $contrat->date_signature)
                <div class="card detail-card mb-4">
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

                <!-- Résiliation -->
                @if($contrat->statut === 'resilie')
                <div class="card detail-card mb-4 border-danger">
                    <div class="card-body bg-danger bg-opacity-10">
                        <div class="section-title text-danger">Résiliation</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-label">Date de résiliation</div>
                                <div class="info-value">{{ $contrat->date_resiliation?->format('d/m/Y') }}</div>
                            </div>
                            <div class="col-12">
                                <div class="info-label">Motif</div>
                                <div class="info-value">{{ $contrat->motif_resiliation }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Colonne latérale -->
            <div class="col-lg-4">
                <!-- Sites -->
                <div class="card detail-card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Sites ({{ $contrat->sites->count() }})</h6>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addSiteModal">
                            <i class="bi bi-plus"></i> Ajouter
                        </button>
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

                <!-- Agents affectés -->
                <div class="card detail-card mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="bi bi-people me-2"></i>Agents affectés ({{ $statsContrat['agents_count'] }})</h6>
                    </div>
                    <div class="card-body p-0">
                        @if($contrat->affectations->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($contrat->affectations as $affectation)
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-2">
                                        {{ strtoupper(substr($affectation->employe?->nom ?? 'N', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $affectation->employe?->nom ?? 'N/A' }} {{ $affectation->employe?->prenoms ?? '' }}</div>
                                        <small class="text-muted">{{ $affectation->site?->nom_site ?? 'Site principal' }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-people fs-1"></i>
                            <p class="mb-0 mt-2">Aucun agent affecté</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Factures récentes -->
                <div class="card detail-card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="bi bi-receipt me-2"></i>Factures ({{ $statsContrat['factures_count'] }})</h6>
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
</div>

<!-- Modal Changement de statut -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Changer le statut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.entreprise.contrats.changerStatut', $contrat->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="statut" class="form-label">Nouveau statut</label>
                        <select name="statut" id="statut" class="form-select" required>
                            <option value="brouillon" {{ $contrat->statut == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="en_cours" {{ $contrat->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="suspendu" {{ $contrat->statut == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="termine" {{ $contrat->statut == 'termine' ? 'selected' : '' }}>Terminé</option>
                            <option value="resilie" {{ $contrat->statut == 'resilie' ? 'selected' : '' }}>Résilié</option>
                        </select>
                    </div>
                    <div id="resiliationFields" style="display: {{ $contrat->statut == 'resilie' ? 'block' : 'none' }}">
                        <div class="mb-3">
                            <label for="date_resiliation" class="form-label">Date de résiliation</label>
                            <input type="date" name="date_resiliation" id="date_resiliation" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="motif_resiliation" class="form-label">Motif de résiliation</label>
                            <textarea name="motif_resiliation" id="motif_resiliation" class="form-control" rows="3"></textarea>
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

<!-- Modal Suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le contrat <strong>{{ $contrat->numero_contrat }}</strong> ?</p>
                @if($contrat->factures()->count() > 0 || $contrat->affectations()->count() > 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Ce contrat possède des données associées. La suppression est bloquée.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                @if($contrat->factures()->count() == 0 && $contrat->affectations()->count() == 0)
                <form action="{{ route('admin.entreprise.contrats.destroy', $contrat->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout Site -->
<div class="modal fade" id="addSiteModal" tabindex="-1" aria-labelledby="addSiteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSiteModalLabel">Ajouter un site</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.entreprise.contrats.ajouterSite', $contrat->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted">Sélectionnez un site à associer à ce contrat.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Afficher/masquer les champs de résiliation
    document.getElementById('statut').addEventListener('change', function() {
        const fields = document.getElementById('resiliationFields');
        if (this.value === 'resilie') {
            fields.style.display = 'block';
        } else {
            fields.style.display = 'none';
        }
    });
</script>
@endpush
@endsection