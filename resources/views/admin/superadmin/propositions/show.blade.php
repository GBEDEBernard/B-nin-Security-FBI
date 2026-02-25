@extends('layouts.app')

@section('title', 'Détails Proposition - Super Admin')

@push('styles')
<style>
    .proposition-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
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
    }

    .info-value {
        font-weight: 500;
        color: #212529;
    }

    .badge-statut {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0 text-white"><i class="bi bi-file-earmark-ruled me-2"></i>Proposition de Contrat</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}" class="text-white">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.propositions.index') }}" class="text-white">Propositions</a></li>
                    <li class="breadcrumb-item active text-white-50">Détails</li>
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

        <!-- En-tête -->
        <div class="proposition-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="mb-1">{{ $proposition->nom_entreprise }}</h2>
                    <p class="mb-0 opacity-75">{{ $proposition->nom_commercial }}</p>
                    <div class="mt-2">
                        <span class="badge bg-white bg-opacity-25">
                            <i class="bi bi-{{ match($proposition->type_service) {
                                'garde_renforcee' => 'shield-fill',
                                'garde_simple' => 'shield',
                                'surveillance_electronique' => 'camera-video',
                                'garde_evenementiel' => 'calendar-event',
                                'conseil' => 'lightbulb',
                                default => 'question-circle'
                            } }} me-1"></i>
                            {{ $proposition->type_service_label }}
                        </span>
                        <span class="badge bg-white bg-opacity-25">
                            <i class="bi bi-people me-1"></i>
                            {{ $proposition->nombre_agents }} agents
                        </span>
                    </div>
                </div>
                <div class="col-auto">
                    <span class="badge-statut bg-{{ $proposition->statut_badge_class }}">
                        {{ $proposition->statut_label }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Informations entreprise -->
            <div class="col-md-6">
                <div class="info-card card h-100">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="bi bi-building me-2 text-success"></i>Informations de l'Entreprise</h6>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <div class="info-label">Forme juridique</div>
                            <div class="info-value">{{ $proposition->forme_juridique ?? 'Non spécifié' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $proposition->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Téléphone</div>
                            <div class="info-value">{{ $proposition->telephone }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Adresse</div>
                            <div class="info-value">{{ $proposition->adresse ?? 'Non spécifiée' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Ville / Pays</div>
                            <div class="info-value">{{ $proposition->ville ?? '' }} {{ $proposition->pays ? ', ' . $proposition->pays : '' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Représentant légal -->
            <div class="col-md-6">
                <div class="info-card card h-100">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="bi bi-person-vcard me-2 text-success"></i>Représentant Légal</h6>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <div class="info-label">Nom complet</div>
                            <div class="info-value">{{ $proposition->representant_nom ?? 'Non spécifié' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Fonction</div>
                            <div class="info-value">{{ $proposition->representant_fonction ?? 'Non spécifiée' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $proposition->representant_email ?? 'Non spécifié' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Téléphone</div>
                            <div class="info-value">{{ $proposition->representant_telephone ?? 'Non spécifié' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Besoins -->
            <div class="col-md-6">
                <div class="info-card card h-100">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="bi bi-list-check me-2 text-success"></i>Besoins</h6>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <div class="info-label">Type de service</div>
                            <div class="info-value">{{ $proposition->type_service_label }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Nombre d'agents</div>
                            <div class="info-value">{{ $proposition->nombre_agents }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Budget approximatif</div>
                            <div class="info-value">{{ $proposition->budget_approx ? number_format($proposition->budget_approx, 0, ',', ' ') . ' FCAF' : 'Non spécifié' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Description</div>
                            <div class="info-value">{{ $proposition->description_besoins ?? 'Non spécifiée' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dates et statut -->
            <div class="col-md-6">
                <div class="info-card card h-100">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="bi bi-calendar-check me-2 text-success"></i>Dates & Statut</h6>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <div class="info-label">Date de soumission</div>
                            <div class="info-value">{{ $proposition->date_soumission?->format('d/m/Y à H:i') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date de signature</div>
                            <div class="info-value">{{ $proposition->date_signature?->format('d/m/Y') ?? 'Non signé' }}</div>
                        </div>
                        @if($proposition->date_rejet)
                        <div class="info-item">
                            <div class="info-label">Motif de rejet</div>
                            <div class="info-value text-danger">{{ $proposition->motif_rejet }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-gear me-2"></i>Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-flex gap-3 flex-wrap">
                    <!-- Step 1: Start processing -->
                    @if($proposition->statut === 'soumis')
                    <form action="{{ route('admin.superadmin.propositions.update', $proposition->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="statut" value="en_cours">
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-play me-1"></i> Commencer le traitement
                        </button>
                    </form>
                    @endif

                    <!-- Step 2: Generate and send contract -->
                    @if(in_array($proposition->statut, ['en_cours', 'contrat_envoye']))
                    <form action="{{ route('admin.superadmin.propositions.update', $proposition->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="statut" value="contrat_envoye">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-envelope me-1"></i> Générer et envoyer le contrat
                        </button>
                    </form>
                    @endif

                    <!-- Step 3: Download contract PDF -->
                    @if(in_array($proposition->statut, ['contrat_envoye', 'en_attente_signature']))
                    <a href="{{ route('admin.superadmin.propositions.telecharger', $proposition->id) }}" class="btn btn-success">
                        <i class="bi bi-download me-1"></i> Télécharger le contrat PDF
                    </a>

                    <!-- Send contract button -->
                    <form action="{{ route('admin.superadmin.propositions.envoyer', $proposition->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-send me-1"></i> Renvoyer par email
                        </button>
                    </form>
                    @endif

                    <!-- Step 4: Upload signed contract -->
                    @if($proposition->statut === 'en_attente_signature')
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uploadSignedModal">
                        <i class="bi bi-upload me-1"></i> Soumettre le contrat signé
                    </button>
                    @endif

                    <!-- Step 5: Create enterprise (after signed) -->
                    @if($proposition->statut === 'signe' && !$proposition->entreprise_id)
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#creerEntrepriseModal">
                        <i class="bi bi-building-add me-1"></i> Créer l'entreprise
                    </button>
                    @endif

                    <!-- Show enterprise if already created -->
                    @if($proposition->entreprise_id)
                    <a href="{{ route('admin.superadmin.entreprises.show', $proposition->entreprise_id) }}" class="btn btn-success">
                        <i class="bi bi-building me-1"></i> Voir l'entreprise créée
                    </a>
                    @endif

                    <!-- Reject option -->
                    @if(!in_array($proposition->statut, ['signe', 'rejete']))
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejeterModal">
                        <i class="bi bi-x-circle me-1"></i> Rejeter
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Créer Entreprise -->
<div class="modal fade" id="creerEntrepriseModal" tabindex="-1" aria-labelledby="creerEntrepriseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="creerEntrepriseModalLabel">Créer l'Entreprise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.superadmin.propositions.creerEntreprise', $proposition->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Formule *</label>
                            <select class="form-select" name="formule" required>
                                <option value="">Sélectionner...</option>
                                <option value="essai">Essai (15 jours)</option>
                                <option value="basic">Basic</option>
                                <option value="standard">Standard</option>
                                <option value="premium">Premium</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre d'agents max *</label>
                            <input type="number" class="form-control" name="nombre_agents_max" value="{{ $proposition->nombre_agents }}" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre de sites max *</label>
                            <input type="number" class="form-control" name="nombre_sites_max" value="5" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Montant mensuel (FCFA) *</label>
                            <input type="number" class="form-control" name="montant_mensuel" value="0" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cycle de facturation *</label>
                            <select class="form-select" name="cycle_facturation" required>
                                <option value="mensuel">Mensuel</option>
                                <option value="trimestriel">Trimestriel</option>
                                <option value="annuel">Annuel</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de début contrat *</label>
                            <input type="date" class="form-control" name="date_debut_contrat" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de fin contrat *</label>
                            <input type="date" class="form-control" name="date_fin_contrat" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Créer l'entreprise</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Rejeter -->
<div class="modal fade" id="rejeterModal" tabindex="-1" aria-labelledby="rejeterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejeterModalLabel">Rejeter la proposition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.superadmin.propositions.rejeter', $proposition->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Motif du rejet *</label>
                        <textarea class="form-control" name="motif_rejet" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Upload Signed Contract -->
<div class="modal fade" id="uploadSignedModal" tabindex="-1" aria-labelledby="uploadSignedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadSignedModalLabel">Soumettre le contrat signé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.superadmin.propositions.soumettreSigne', $proposition->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Contrat signé (PDF) *</label>
                        <input type="file" class="form-control" name="fichier_contrat_signe" accept=".pdf" required>
                        <div class="form-text">Formats acceptés: PDF (max 5MB)</div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Le contrat sera automatiquement marqué comme signé après l'upload.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-upload me-1"></i> Soumettre le contrat signé
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection