@extends('layouts.app')

@section('title', 'Proposition #' . $proposition->id)

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

    .proposition-card {
        border: none;
        border-radius: 16px;
        background: var(--pp-surface);
        box-shadow: var(--pp-shadow);
        transition: transform 0.2s ease;
    }
    .proposition-card:hover {
        transform: translateY(-2px);
    }
    .proposition-card .card-header {
        background: transparent;
        border-bottom: 1px solid var(--pp-border);
    }
    .proposition-card .card-header h5,
    .proposition-card .card-header h6 {
        color: var(--pp-text);
    }

    .detail-label {
        font-size: 0.75rem;
        color: var(--pp-text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .detail-value {
        font-size: 1rem;
        font-weight: 500;
        color: var(--pp-text);
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
    [data-bs-theme="dark"] .modal .form-control {
        background: #121212;
        border-color: #2a2d3a;
        color: #f0f2f8;
    }
    [data-bs-theme="dark"] .text-muted {
        color: var(--pp-text-muted) !important;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-file-earmark-ruled me-2"></i>
                    Proposition #{{ $proposition->id }}
                    <span class="badge bg-{{ $proposition->statut_badge_class }} ms-2">{{ $proposition->statut_label }}</span>
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.propositions.index') }}">Propositions</a></li>
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

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card proposition-card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Détails de la prestation</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="detail-label">Type de service</div>
                                <div class="detail-value">{{ $proposition->type_service_label }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="detail-label">Nombre d'agents</div>
                                <div class="detail-value">{{ $proposition->nombre_agents }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="detail-label">Budget estimé</div>
                                <div class="detail-value">{{ $proposition->budget_approx ? number_format($proposition->budget_approx, 0, ',', ' ') . ' CFA' : 'Non spécifié' }}</div>
                            </div>
                            @if($proposition->description_besoins)
                            <div class="col-md-12">
                                <div class="detail-label">Description des besoins</div>
                                <div class="detail-value">{{ $proposition->description_besoins }}</div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="detail-label">Date de soumission</div>
                                <div class="detail-value">{{ $proposition->date_soumission?->format('d/m/Y à H:i') ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Statut actuel</div>
                                <div class="detail-value">
                                    <span class="badge bg-{{ $proposition->statut_badge_class }}">{{ $proposition->statut_label }}</span>
                                </div>
                            </div>
                            @if($proposition->date_signature)
                            <div class="col-md-6">
                                <div class="detail-label">Date de signature</div>
                                <div class="detail-value">{{ $proposition->date_signature->format('d/m/Y à H:i') }}</div>
                            </div>
                            @endif
                            @if($proposition->date_rejet)
                            <div class="col-md-6">
                                <div class="detail-label">Date de rejet</div>
                                <div class="detail-value">{{ $proposition->date_rejet->format('d/m/Y à H:i') }}</div>
                            </div>
                            @endif
                            @if($proposition->motif_rejet)
                            <div class="col-md-12">
                                <div class="detail-label">Motif du rejet</div>
                                <div class="detail-value text-danger">{{ $proposition->motif_rejet }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($proposition->contrat_pdf_path)
                <div class="card proposition-card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-file-pdf me-2"></i>Contrat</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ asset('storage/' . $proposition->contrat_pdf_path) }}" target="_blank" class="btn btn-outline-danger">
                            <i class="bi bi-file-pdf"></i> Télécharger le contrat
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-4">
                @if(in_array($proposition->statut, ['soumis', 'en_cours', 'contrat_envoye', 'en_attente_signature']))
                <div class="card proposition-card mb-4" style="border: 1px solid var(--pp-border);">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-check-circle me-2 text-success"></i>Actions</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.entreprise.propositions.accepter', $proposition->id) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Accepter cette proposition ?')">
                                <i class="bi bi-check-lg me-1"></i> Accepter la proposition
                            </button>
                        </form>

                        <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#modalRefuser">
                            <i class="bi bi-x-lg me-1"></i> Refuser
                        </button>
                    </div>
                </div>
                @endif

                @if($proposition->statut === 'signe')
                <div class="card proposition-card mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Acceptée</h5>
                    </div>
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle-fill text-success fs-1"></i>
                        <p class="mt-2 mb-0">Vous avez accepté cette proposition le {{ $proposition->date_signature?->format('d/m/Y') }}</p>
                    </div>
                </div>
                @endif

                @if($proposition->statut === 'rejete')
                <div class="card proposition-card mb-4 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-x-circle me-2"></i>Refusée</h5>
                    </div>
                    <div class="card-body text-center">
                        <i class="bi bi-x-circle-fill text-danger fs-1"></i>
                        <p class="mt-2 mb-0">Vous avez refusé cette proposition le {{ $proposition->date_rejet?->format('d/m/Y') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Refuser --}}
@if(in_array($proposition->statut, ['soumis', 'en_cours', 'contrat_envoye', 'en_attente_signature']))
<div class="modal fade" id="modalRefuser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.entreprise.propositions.refuser', $proposition->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Refuser la proposition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Motif du refus <span class="text-danger">*</span></label>
                        <textarea name="motif_rejet" class="form-control" rows="4" required minlength="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer le refus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
