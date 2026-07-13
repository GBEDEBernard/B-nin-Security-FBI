@extends('layouts.app')

@section('title', 'Proposition #' . $proposition->id . ' - Super Admin')

@push('styles')
<style>
    :root {
        --pp-surface: #ffffff;
        --pp-border: #e9ecef;
        --pp-text: #212529;
        --pp-text-muted: #6c757d;
        --pp-bg-soft: #f8f9fa;
        --pp-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
        --pp-shadow-lg: 0 0.5rem 1rem rgba(0,0,0,0.08);
    }
    :root[data-bs-theme="dark"] {
        --pp-surface: #1a1d27;
        --pp-border: #2a2d3a;
        --pp-text: #f0f2f8;
        --pp-text-muted: #8b90a8;
        --pp-bg-soft: #1a1d27;
        --pp-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.3);
        --pp-shadow-lg: 0 0.5rem 1rem rgba(0,0,0,0.4);
    }

    .profile-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
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
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .profile-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 50%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .info-card {
        border: none;
        border-radius: 12px;
        background: var(--pp-surface);
        box-shadow: var(--pp-shadow);
        transition: transform 0.2s ease;
    }
    .info-card:hover {
        transform: translateY(-2px);
    }
    .info-card .card-header {
        background: transparent;
        border-bottom: 1px solid var(--pp-border);
        padding: 1rem 1.25rem;
    }
    .info-card .card-header h6 {
        color: var(--pp-text);
        font-weight: 600;
    }
    .info-card .card-body {
        padding: 0;
    }

    .info-item {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--pp-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        font-size: 0.8rem;
        color: var(--pp-text-muted);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .info-value {
        font-weight: 500;
        color: var(--pp-text);
        text-align: right;
    }

    .action-card {
        border: none;
        border-radius: 12px;
        background: var(--pp-surface);
        box-shadow: var(--pp-shadow);
    }
    .action-card .card-header {
        background: transparent;
        border-bottom: 1px solid var(--pp-border);
    }

    [data-bs-theme="dark"] .modal-content {
        background: #1a1d27;
        border-color: #2a2d3a;
    }
    [data-bs-theme="dark"] .modal-header {
        border-bottom-color: #2a2d3a;
    }
    [data-bs-theme="dark"] .modal-footer {
        border-top-color: #2a2d3a;
    }
    [data-bs-theme="dark"] .modal .btn-close {
        filter: invert(1);
    }
    [data-bs-theme="dark"] .modal .form-label {
        color: #f0f2f8;
    }
    [data-bs-theme="dark"] .modal .form-control,
    [data-bs-theme="dark"] .modal .form-select {
        background: #121212;
        border-color: #2a2d3a;
        color: #f0f2f8;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-file-earmark-ruled me-2"></i>Proposition #{{ $proposition->id }}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.propositions.index') }}">Propositions</a></li>
                    <li class="breadcrumb-item active">#{{ $proposition->id }}</li>
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

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="profile-header mb-4">
            <div class="row align-items-center position-relative">
                <div class="col">
                    <h2 class="mb-1 fw-bold">{{ $proposition->nom_entreprise }}</h2>
                    @if($proposition->entreprise)
                    <a href="{{ route('admin.superadmin.entreprises.show', $proposition->entreprise_id) }}" class="text-white opacity-75 text-decoration-none">
                        <i class="bi bi-building me-1"></i>{{ $proposition->entreprise->slug }}
                    </a>
                    @endif
                    <div class="mt-2 d-flex gap-2 flex-wrap">
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
                            <i class="bi bi-people me-1"></i>{{ $proposition->nombre_agents }} agents
                        </span>
                        @if($proposition->budget_approx)
                        <span class="badge bg-white bg-opacity-25">
                            <i class="bi bi-coin me-1"></i>{{ number_format($proposition->budget_approx, 0, ',', ' ') }} CFA
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-auto">
                    <span class="badge fs-6 px-3 py-2 bg-white text-{{ $proposition->statut_badge_class }}">
                        {{ $proposition->statut_label }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-card card h-100">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-building me-2 text-success"></i>Entreprise</h6>
                            </div>
                            <div class="card-body">
                                <div class="info-item">
                                    <span class="info-label">Raison sociale</span>
                                    <span class="info-value">{{ $proposition->nom_entreprise }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Forme juridique</span>
                                    <span class="info-value">{{ $proposition->forme_juridique ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">{{ $proposition->email }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Téléphone</span>
                                    <span class="info-value">{{ $proposition->telephone }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Adresse</span>
                                    <span class="info-value">{{ $proposition->adresse ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Ville / Pays</span>
                                    <span class="info-value">{{ $proposition->ville ?? '—' }}{{ $proposition->pays ? ', ' . $proposition->pays : '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card card h-100">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-person-vcard me-2 text-success"></i>Représentant légal</h6>
                            </div>
                            <div class="card-body">
                                <div class="info-item">
                                    <span class="info-label">Nom complet</span>
                                    <span class="info-value">{{ $proposition->representant_nom ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Fonction</span>
                                    <span class="info-value">{{ $proposition->representant_fonction ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">{{ $proposition->representant_email ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Téléphone</span>
                                    <span class="info-value">{{ $proposition->representant_telephone ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="info-card card h-100">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-list-check me-2 text-success"></i>Besoins & prestation</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-0">
                                    <div class="col-md-3">
                                        <div class="info-item">
                                            <span class="info-label">Type de service</span>
                                            <span class="info-value">{{ $proposition->type_service_label }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-item">
                                            <span class="info-label">Agents requis</span>
                                            <span class="info-value">{{ $proposition->nombre_agents }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-item">
                                            <span class="info-label">Budget</span>
                                            <span class="info-value">{{ $proposition->budget_approx ? number_format($proposition->budget_approx, 0, ',', ' ') . ' CFA' : '—' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-item">
                                            <span class="info-label">Statut</span>
                                            <span class="info-value"><span class="badge bg-{{ $proposition->statut_badge_class }}">{{ $proposition->statut_label }}</span></span>
                                        </div>
                                    </div>
                                </div>
                                @if($proposition->description_besoins)
                                <div class="info-item">
                                    <span class="info-label">Description</span>
                                    <span class="info-value text-start" style="max-width: 70%;">{{ $proposition->description_besoins }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="info-card card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-calendar-event me-2 text-success"></i>Dates</h6>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <span class="info-label">Soumission</span>
                            <span class="info-value">{{ $proposition->date_soumission?->format('d/m/Y H:i') ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Dernière modif.</span>
                            <span class="info-value">{{ $proposition->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($proposition->date_signature)
                        <div class="info-item">
                            <span class="info-label">Signature</span>
                            <span class="info-value text-success">{{ $proposition->date_signature->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                        @if($proposition->date_rejet)
                        <div class="info-item">
                            <span class="info-label">Rejet</span>
                            <span class="info-value text-danger">{{ $proposition->date_rejet->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                        @if($proposition->traite_par)
                        <div class="info-item">
                            <span class="info-label">Traité par</span>
                            <span class="info-value">{{ $proposition->traiterPar?->name ?? '—' }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                @if($proposition->notes)
                <div class="info-card card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-sticky me-2 text-success"></i>Notes internes</h6>
                    </div>
                    <div class="card-body">
                        <div class="p-3">
                            <p class="mb-0" style="color: var(--pp-text);">{{ $proposition->notes }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($proposition->motif_rejet)
                <div class="info-card card mb-4 border-danger">
                    <div class="card-header bg-danger bg-opacity-10">
                        <h6 class="mb-0 text-danger"><i class="bi bi-x-circle me-2"></i>Motif du rejet</h6>
                    </div>
                    <div class="card-body">
                        <div class="p-3">
                            <p class="mb-0 text-danger">{{ $proposition->motif_rejet }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($proposition->contrat_pdf_path)
                <div class="info-card card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-file-pdf me-2 text-danger"></i>Contrat</h6>
                    </div>
                    <div class="card-body p-3">
                        <a href="{{ asset('storage/' . $proposition->contrat_pdf_path) }}" target="_blank" class="btn btn-outline-danger w-100">
                            <i class="bi bi-file-pdf me-1"></i> Télécharger le contrat PDF
                        </a>
                    </div>
                </div>
                @endif

                @if($proposition->fichier_contrat_signe)
                <div class="info-card card mb-4 border-success">
                    <div class="card-header bg-success bg-opacity-10">
                        <h6 class="mb-0 text-success"><i class="bi bi-file-check me-2"></i>Contrat signé</h6>
                    </div>
                    <div class="card-body p-3">
                        <a href="{{ asset('storage/' . $proposition->fichier_contrat_signe) }}" target="_blank" class="btn btn-outline-success w-100">
                            <i class="bi bi-file-check me-1"></i> Voir le contrat signé
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="action-card card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-gear me-2 text-success"></i>Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-flex gap-3 flex-wrap">
                    @if($proposition->statut === 'soumis')
                    <form action="{{ route('admin.superadmin.propositions.update', $proposition->id) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="statut" value="en_cours">
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-play me-1"></i> Commencer le traitement
                        </button>
                    </form>
                    @endif

                    @if(in_array($proposition->statut, ['en_cours', 'contrat_envoye']))
                    <form action="{{ route('admin.superadmin.propositions.update', $proposition->id) }}" method="POST" class="d-inline">
                        @csrf @method('PUT')
                        <input type="hidden" name="statut" value="contrat_envoye">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-envelope me-1"></i> Marquer contrat envoyé
                        </button>
                    </form>
                    @endif

                    @if(in_array($proposition->statut, ['contrat_envoye', 'en_attente_signature']))
                    <a href="{{ route('admin.superadmin.propositions.telecharger', $proposition->id) }}" class="btn btn-success">
                        <i class="bi bi-download me-1"></i> Télécharger le PDF
                    </a>
                    <form action="{{ route('admin.superadmin.propositions.envoyer', $proposition->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-send me-1"></i> Envoyer par email
                        </button>
                    </form>
                    @endif

                    @if($proposition->statut === 'en_attente_signature')
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uploadSignedModal">
                        <i class="bi bi-upload me-1"></i> Soumettre contrat signé
                    </button>
                    @endif

                    @if($proposition->statut === 'signe' && !$proposition->entreprise_id)
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#creerEntrepriseModal">
                        <i class="bi bi-building-add me-1"></i> Créer l'entreprise
                    </button>
                    @endif

                    @if($proposition->entreprise_id)
                    <a href="{{ route('admin.superadmin.entreprises.show', $proposition->entreprise_id) }}" class="btn btn-success">
                        <i class="bi bi-building me-1"></i> Voir l'entreprise
                    </a>
                    @endif

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

{{-- Modal Créer Entreprise --}}
<div class="modal fade" id="creerEntrepriseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-building-add me-2 text-success"></i>Créer l'entreprise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.superadmin.propositions.creerEntreprise', $proposition->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Formule <span class="text-danger">*</span></label>
                            <select class="form-select" name="formule" required>
                                <option value="">Sélectionner...</option>
                                <option value="essai">Essai (15 jours)</option>
                                <option value="basic">Basic</option>
                                <option value="standard">Standard</option>
                                <option value="premium">Premium</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Agents max <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="nombre_agents_max" value="{{ $proposition->nombre_agents }}" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sites max <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="nombre_sites_max" value="5" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Montant mensuel (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="montant_mensuel" value="{{ $proposition->budget_approx ?? 0 }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cycle facturation <span class="text-danger">*</span></label>
                            <select class="form-select" name="cycle_facturation" required>
                                <option value="mensuel">Mensuel</option>
                                <option value="trimestriel">Trimestriel</option>
                                <option value="annuel">Annuel</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date début contrat <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date_debut_contrat" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date fin contrat <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date_fin_contrat" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Créer l'entreprise</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Rejeter --}}
<div class="modal fade" id="rejeterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-x-circle me-2 text-danger"></i>Rejeter la proposition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.superadmin.propositions.rejeter', $proposition->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Motif du rejet <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="motif_rejet" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Upload Signed --}}
<div class="modal fade" id="uploadSignedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-upload me-2 text-warning"></i>Soumettre le contrat signé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.superadmin.propositions.soumettreSigne', $proposition->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fichier PDF signé <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="fichier_contrat_signe" accept=".pdf" required>
                        <div class="form-text">PDF uniquement, max 5 Mo</div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Le contrat sera marqué comme signé après l'upload.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Soumettre</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
