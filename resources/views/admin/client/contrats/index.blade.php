@extends('layouts.app')

@section('title', 'Mes Contrats - Client')

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

    .contrat-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        background: var(--bs-body-bg);
        transition: all 0.3s ease;
    }

    .contrat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .stat-badge {
        padding: 0.35rem 0.8rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .badge-brouillon {
        background: rgba(108, 117, 125, 0.12);
        color: #6c757d;
    }

    .badge-en_cours {
        background: rgba(25, 135, 84, 0.12);
        color: #198754;
    }

    .badge-suspendu {
        background: rgba(255, 193, 7, 0.15);
        color: #c49a00;
    }

    .badge-termine {
        background: rgba(13, 110, 253, 0.12);
        color: #0d6efd;
    }

    .badge-resilie {
        background: rgba(220, 53, 69, 0.12);
        color: #dc3545;
    }

    .info-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--bs-secondary-color);
        margin-bottom: 0.2rem;
    }

    .info-value {
        font-size: 0.95rem;
        color: var(--bs-body-color);
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: rgba(13, 110, 253, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #0d6efd;
        margin: 0 auto 1.5rem;
    }

    .empty-state h5 {
        font-weight: 700;
        color: var(--bs-body-color);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--bs-secondary-color);
        margin-bottom: 1.5rem;
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
                    Mes Contrats
                </h3>
                <div class="mt-1 opacity-75" style="font-size:0.875rem;">
                    {{ $contrats->count() }} contrat{{ $contrats->count() > 1 ? 's' : '' }}
                </div>
            </div>
            <div class="col-md-5">
                <ol class="breadcrumb float-md-end mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.client.index') }}">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Contrats</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- LISTE DES CONTRATS --}}
    @if($contrats->count() > 0)
    <div class="row g-4">
        @foreach($contrats as $contrat)
        <div class="col-md-6 col-lg-4 animate-in">
            <div class="contrat-card card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-1" style="font-weight: 700;">{{ $contrat->numero_contrat }}</h5>
                            <span class="stat-badge badge-{{ $contrat->statut }}">
                                {{ match($contrat->statut) {
                                    'brouillon' => 'Brouillon',
                                    'en_cours'  => 'En cours',
                                    'suspendu'  => 'Suspendu',
                                    'termine'   => 'Terminé',
                                    'resilie'   => 'Résilié',
                                    default     => $contrat->statut
                                } }}
                            </span>
                        </div>
                    </div>

                    <h6 class="mb-3" style="color: var(--bs-body-color);">{{ $contrat->intitule }}</h6>

                    <div class="mb-3">
                        <div class="info-label">Entreprise</div>
                        <div class="info-value">{{ $contrat->entreprise?->nom ?? 'N/A' }}</div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="info-label">Montant/mois</div>
                            <div class="info-value" style="color: #198754;">
                                {{ number_format($contrat->montant_mensuel_ht ?? 0, 0, ',', ' ') }} FCA
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Prix/agent</div>
                            <div class="info-value">
                                {{ number_format($contrat->prix_par_agent ?? 0, 0, ',', ' ') }} FCA
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="info-label">Agents</div>
                            <div class="info-value">{{ $contrat->nombre_agents_requis ?? 0 }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Sites</div>
                            <div class="info-value">{{ $contrat->nombre_sites ?? 0 }}</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="info-label">Période</div>
                        <div class="info-value" style="font-size: 0.85rem;">
                            {{ $contrat->date_debut?->format('d/m/Y') ?? '—' }}
                            au
                            {{ $contrat->date_fin?->format('d/m/Y') ?? '—' }}
                        </div>
                    </div>

                    <a href="{{ route('admin.client.contrats.show', $contrat->id) }}"
                        class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-eye me-1"></i> Voir les détails
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @else
    {{-- ÉTAT VIDE --}}
    <div class="empty-state">
        <div class="empty-icon">
            <i class="bi bi-file-earmark-text"></i>
        </div>
        <h5>Aucun contrat</h5>
        <p>Vous n'avez pas encore de contrat de prestation.</p>
    </div>
    @endif

</div>
@endsection