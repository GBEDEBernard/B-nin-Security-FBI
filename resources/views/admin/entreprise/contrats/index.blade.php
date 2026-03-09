@extends('layouts.app')

@section('title', 'Contrats - Entreprise')

@push('styles')
<style>
    /* ═══════════════════════════════════════════════════════════
       CONTRATS INDEX — PREMIUM DESIGN
    ═══════════════════════════════════════════════════════════ */

    .page-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        padding: 1.75rem 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 4px 20px rgba(25, 135, 84, 0.3);
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

    /* ── Stat cards ── */
    .stat-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
        background: var(--bs-body-bg);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
    }

    .stat-card .card-body {
        padding: 1.5rem;
    }

    .stat-card .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .stat-card .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--bs-secondary-color);
        margin-bottom: 0.25rem;
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        color: var(--bs-body-color);
    }

    .stat-card .stat-decoration {
        position: absolute;
        right: -10px;
        top: -10px;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        opacity: 0.06;
    }

    /* ── Filtres ── */
    .filter-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
        background: var(--bs-body-bg);
        margin-bottom: 1.5rem;
    }

    .filter-card .card-body {
        padding: 1.25rem 1.5rem;
    }

    .filter-input {
        border-radius: 12px;
        border: 2px solid var(--bs-border-color);
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
        transition: all 0.25s;
        background: var(--bs-body-bg);
        color: var(--bs-body-color);
    }

    .filter-input:focus {
        border-color: #198754;
        box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.12);
        outline: none;
    }

    .btn-filter {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        border-radius: 12px;
        padding: 0.65rem 1.5rem;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(25, 135, 84, 0.3);
        color: white;
    }

    .btn-reset {
        border: 2px solid var(--bs-border-color);
        border-radius: 12px;
        padding: 0.65rem 1.25rem;
        background: var(--bs-body-bg);
        color: var(--bs-secondary-color);
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.25s;
    }

    .btn-reset:hover {
        border-color: #198754;
        color: #198754;
        background: rgba(25, 135, 84, 0.05);
    }

    .btn-new-contrat {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        border-radius: 12px;
        padding: 0.65rem 1.5rem;
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-new-contrat:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(25, 135, 84, 0.35);
        color: white;
    }

    /* ── Table card ── */
    .table-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
        background: var(--bs-body-bg);
        overflow: hidden;
    }

    .table-card .card-header {
        background: var(--bs-body-bg);
        border-bottom: 2px solid var(--bs-border-color);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .table-card .card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1rem;
        color: var(--bs-body-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* ── Table ── */
    .data-table {
        margin: 0;
    }

    .data-table thead tr {
        background: var(--bs-tertiary-bg);
    }

    .data-table thead th {
        padding: 0.875rem 1rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--bs-secondary-color);
        border: none;
        white-space: nowrap;
    }

    .data-table tbody tr {
        border-bottom: 1px solid var(--bs-border-color);
        transition: background 0.2s;
    }

    .data-table tbody tr:last-child {
        border-bottom: none;
    }

    .data-table tbody tr:hover {
        background: var(--bs-tertiary-bg);
    }

    .data-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        font-size: 0.9rem;
        border: none;
    }

    /* ── Numéro contrat ── */
    .contrat-numero {
        font-weight: 700;
        font-size: 0.9rem;
        color: #198754;
        font-family: monospace;
        background: rgba(25, 135, 84, 0.08);
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        display: inline-block;
    }

    /* ── Client badge ── */
    .client-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(13, 110, 253, 0.08);
        color: #0d6efd;
        border-radius: 20px;
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* ── Statut badges ── */
    .badge-statut {
        padding: 0.35rem 0.8rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        white-space: nowrap;
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

    .badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
        background: currentColor;
    }

    /* ── Montant ── */
    .montant-value {
        font-weight: 700;
        color: var(--bs-body-color);
        font-size: 0.9rem;
    }

    .montant-unit {
        font-size: 0.75rem;
        color: var(--bs-secondary-color);
    }

    /* ── Actions ── */
    .btn-action-group {
        display: flex;
        gap: 0.35rem;
        justify-content: flex-end;
    }

    .btn-act {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        border: 2px solid transparent;
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-act-view {
        background: rgba(13, 110, 253, 0.08);
        color: #0d6efd;
        border-color: rgba(13, 110, 253, 0.15);
    }

    .btn-act-edit {
        background: rgba(25, 135, 84, 0.08);
        color: #198754;
        border-color: rgba(25, 135, 84, 0.15);
    }

    .btn-act-del {
        background: rgba(220, 53, 69, 0.08);
        color: #dc3545;
        border-color: rgba(220, 53, 69, 0.15);
    }

    .btn-act:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
    }

    .btn-act-view:hover {
        background: rgba(13, 110, 253, 0.15);
    }

    .btn-act-edit:hover {
        background: rgba(25, 135, 84, 0.15);
    }

    .btn-act-del:hover {
        background: rgba(220, 53, 69, 0.15);
    }

    /* ── Vide ── */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: rgba(25, 135, 84, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #198754;
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

    /* ── Alertes session ── */
    .session-success {
        background: rgba(25, 135, 84, 0.08);
        border: 1px solid rgba(25, 135, 84, 0.25);
        border-radius: 16px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #198754;
        font-weight: 600;
    }

    .session-error {
        background: rgba(220, 53, 69, 0.08);
        border: 1px solid rgba(220, 53, 69, 0.25);
        border-radius: 16px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #dc3545;
        font-weight: 600;
    }

    /* ── Pagination ── */
    .pagination-wrap {
        padding: 1rem 1.5rem;
        border-top: 2px solid var(--bs-border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .pagination-info {
        font-size: 0.8rem;
        color: var(--bs-secondary-color);
    }

    /* ── Expiration warning ── */
    .expiry-warning {
        color: #fd7e14;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .expiry-expired {
        color: #dc3545;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* ── Modal ── */
    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        border-bottom: 2px solid var(--bs-border-color);
        padding: 1.25rem 1.5rem;
        border-radius: 20px 20px 0 0;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 2px solid var(--bs-border-color);
        padding: 1.25rem 1.5rem;
    }

    .btn-confirm-del {
        background: linear-gradient(135deg, #dc3545, #c82333);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        padding: 0.65rem 1.5rem;
        transition: all 0.2s;
    }

    .btn-confirm-del:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(220, 53, 69, 0.3);
        color: white;
    }

    .btn-modal-cancel {
        border: 2px solid var(--bs-border-color);
        border-radius: 10px;
        background: var(--bs-body-bg);
        color: var(--bs-body-color);
        font-weight: 600;
        padding: 0.65rem 1.25rem;
        transition: all 0.2s;
    }

    .btn-modal-cancel:hover {
        background: var(--bs-tertiary-bg);
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

    .animate-in:nth-child(1) {
        animation-delay: 0.05s;
    }

    .animate-in:nth-child(2) {
        animation-delay: 0.10s;
    }

    .animate-in:nth-child(3) {
        animation-delay: 0.15s;
    }

    .animate-in:nth-child(4) {
        animation-delay: 0.20s;
    }

    .animate-in:nth-child(5) {
        animation-delay: 0.25s;
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
                    Gestion des Contrats
                </h3>
                <div class="mt-1 opacity-75" style="font-size:0.875rem;">
                    {{ $stats['total'] }} contrat{{ $stats['total'] > 1 ? 's' : '' }} au total
                    — {{ $stats['actifs'] }} en cours
                </div>
            </div>
            <div class="col-md-5">
                <ol class="breadcrumb float-md-end mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.entreprise.index') }}">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Contrats</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ALERTES SESSION --}}
    @if(session('success'))
    <div class="session-success animate-in">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="session-error animate-in">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- STATISTIQUES --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3 animate-in">
            <div class="stat-card card h-100">
                <div class="stat-decoration" style="background:#0d6efd;"></div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background:rgba(13,110,253,0.1);color:#0d6efd;">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div>
                            <div class="stat-label">Total</div>
                            <div class="stat-value" style="color:#0d6efd;">{{ $stats['total'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 animate-in">
            <div class="stat-card card h-100">
                <div class="stat-decoration" style="background:#198754;"></div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background:rgba(25,135,84,0.1);color:#198754;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <div class="stat-label">En cours</div>
                            <div class="stat-value" style="color:#198754;">{{ $stats['actifs'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 animate-in">
            <div class="stat-card card h-100">
                <div class="stat-decoration" style="background:#ffc107;"></div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background:rgba(255,193,7,0.12);color:#c49a00;">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <div>
                            <div class="stat-label">Brouillons</div>
                            <div class="stat-value" style="color:#c49a00;">{{ $stats['brouillon'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 animate-in">
            <div class="stat-card card h-100">
                <div class="stat-decoration" style="background:#dc3545;"></div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background:rgba(220,53,69,0.1);color:#dc3545;">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div>
                            <div class="stat-label">Résiliés</div>
                            <div class="stat-value" style="color:#dc3545;">{{ $stats['resilies'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTRES --}}
    <div class="filter-card card animate-in">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.entreprise.contrats.index') }}"
                class="row g-2 align-items-end">
                {{-- Recherche --}}
                <div class="col-md-4">
                    <label class="form-label mb-1" style="font-size:0.8rem;font-weight:600;color:var(--bs-secondary-color);">
                        <i class="bi bi-search me-1"></i>RECHERCHE
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"
                            style="border-radius:12px 0 0 12px;border:2px solid var(--bs-border-color);border-right:none;background:var(--bs-body-bg);">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search"
                            class="filter-input"
                            style="border-left:none;border-radius:0 12px 12px 0;"
                            placeholder="N° contrat, intitulé…"
                            value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Statut --}}
                <div class="col-md-3">
                    <label class="form-label mb-1" style="font-size:0.8rem;font-weight:600;color:var(--bs-secondary-color);">
                        <i class="bi bi-flag me-1"></i>STATUT
                    </label>
                    <select name="statut" class="filter-input w-100">
                        <option value="">Tous les statuts</option>
                        <option value="brouillon" {{ request('statut') == 'brouillon'  ? 'selected' : '' }}>📝 Brouillon</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours'   ? 'selected' : '' }}>✅ En cours</option>
                        <option value="suspendu" {{ request('statut') == 'suspendu'   ? 'selected' : '' }}>⏸ Suspendu</option>
                        <option value="termine" {{ request('statut') == 'termine'    ? 'selected' : '' }}>🏁 Terminé</option>
                        <option value="resilie" {{ request('statut') == 'resilie'    ? 'selected' : '' }}>🚫 Résilié</option>
                    </select>
                </div>

                {{-- Boutons --}}
                <div class="col-md-5 d-flex align-items-end gap-2 justify-content-md-end">
                    <button type="submit" class="btn-filter btn">
                        <i class="bi bi-funnel-fill me-1"></i>Filtrer
                    </button>
                    @if(request()->hasAny(['search','statut']))
                    <a href="{{ route('admin.entreprise.contrats.index') }}" class="btn-reset btn">
                        <i class="bi bi-x-circle me-1"></i>Réinitialiser
                    </a>
                    @endif
                    <a href="{{ route('admin.entreprise.contrats.create') }}" class="btn-new-contrat">
                        <i class="bi bi-plus-circle-fill"></i>
                        Nouveau contrat
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-card card animate-in">
        <div class="card-header">
            <h5>
                <i class="bi bi-list-ul" style="color:#198754;"></i>
                Liste des contrats
            </h5>
            <span style="font-size:0.8rem;color:var(--bs-secondary-color);font-weight:600;">
                {{ $contrats->total() }} résultat{{ $contrats->total() > 1 ? 's' : '' }}
            </span>
        </div>

        @if($contrats->count() > 0)
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th>N° Contrat</th>
                        <th>Intitulé</th>
                        <th>Client</th>
                        <th>Période</th>
                        <th>Montant HT/mois</th>
                        <th>Sites</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contrats as $contrat)
                    <tr>
                        {{-- Numéro --}}
                        <td>
                            <span class="contrat-numero">{{ $contrat->numero_contrat }}</span>
                        </td>

                        {{-- Intitulé --}}
                        <td>
                            <div style="font-weight:600;color:var(--bs-body-color);font-size:0.875rem;">
                                {{ Str::limit($contrat->intitule, 40) }}
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-people me-1"></i>{{ $contrat->nombre_agents_requis }} agent(s)
                            </small>
                        </td>

                        {{-- Client --}}
                        <td>
                            <div class="client-chip">
                                <i class="bi bi-person-fill"></i>
                                {{ $contrat->client?->nom_affichage ?? $contrat->client?->nom ?? 'N/A' }}
                            </div>
                        </td>

                        {{-- Période --}}
                        <td>
                            <div style="font-size:0.875rem;font-weight:600;">
                                {{ $contrat->date_debut?->format('d/m/Y') ?? '—' }}
                            </div>
                            <small class="text-muted">
                                au {{ $contrat->date_fin?->format('d/m/Y') ?? '—' }}
                            </small>
                            @if($contrat->date_fin && $contrat->statut === 'en_cours')
                            @php $joursRestants = now()->diffInDays($contrat->date_fin, false); @endphp
                            @if($joursRestants < 0)
                                <div class="expiry-expired">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Expiré
        </div>
        @elseif($joursRestants <= 30)
            <div class="expiry-warning">
            <i class="bi bi-clock me-1"></i>{{ $joursRestants }}j restants
    </div>
    @endif
    @endif
    </td>

    {{-- Montant --}}
    <td>
        <div class="montant-value">
            {{ number_format($contrat->montant_mensuel_ht ?? 0, 0, ',', ' ') }}
        </div>
        <div class="montant-unit">FCFA HT</div>
    </td>

    {{-- Sites --}}
    <td>
        <span class="badge bg-info bg-opacity-10 text-info">
            <i class="bi bi-geo-alt me-1"></i>
            {{ $contrat->nombre_sites ?? 0 }} site(s)
        </span>
    </td>

    {{-- Statut --}}
    <td>
        <span class="badge-statut badge-{{ $contrat->statut }}">
            <span class="badge-dot"></span>
            {{ match($contrat->statut) {
                                    'brouillon' => 'Brouillon',
                                    'en_cours'  => 'En cours',
                                    'suspendu'  => 'Suspendu',
                                    'termine'   => 'Terminé',
                                    'resilie'   => 'Résilié',
                                    default     => $contrat->statut
                                } }}
        </span>
    </td>

    {{-- Actions --}}
    <td>
        <div class="btn-action-group">
            <a href="{{ route('admin.entreprise.contrats.show', $contrat->id) }}"
                class="btn-act btn-act-view" title="Voir le détail">
                <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('admin.entreprise.contrats.edit', $contrat->id) }}"
                class="btn-act btn-act-edit" title="Modifier">
                <i class="bi bi-pencil"></i>
            </a>
            <button type="button"
                class="btn-act btn-act-del"
                title="Supprimer"
                data-bs-toggle="modal"
                data-bs-target="#deleteModal{{ $contrat->id }}">
                <i class="bi bi-trash3"></i>
            </button>
        </div>

        {{-- Modal suppression --}}
        <div class="modal fade" id="deleteModal{{ $contrat->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                            Confirmer la suppression
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2">Vous êtes sur le point de supprimer le contrat :</p>
                        <div style="background:var(--bs-tertiary-bg);border-radius:12px;padding:1rem;margin-bottom:1rem;">
                            <div style="font-weight:700;color:var(--bs-body-color);">
                                {{ $contrat->numero_contrat }}
                            </div>
                            <div style="font-size:0.875rem;color:var(--bs-secondary-color);">
                                {{ $contrat->intitule }}
                            </div>
                        </div>
                        @if($contrat->factures()->count() > 0 || $contrat->affectations()->count() > 0)
                        <div style="background:rgba(255,193,7,0.1);border:1px solid rgba(255,193,7,0.3);border-radius:12px;padding:1rem;">
                            <div style="font-weight:700;color:#c49a00;display:flex;align-items:center;gap:0.5rem;">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Suppression impossible
                            </div>
                            <div style="font-size:0.875rem;color:var(--bs-secondary-color);margin-top:0.25rem;">
                                Ce contrat possède des données associées
                                ({{ $contrat->factures()->count() }} facture(s),
                                {{ $contrat->affectations()->count() }} affectation(s)).
                            </div>
                        </div>
                        @else
                        <p class="text-muted mb-0" style="font-size:0.875rem;">
                            <i class="bi bi-info-circle me-1"></i>
                            Cette action est irréversible.
                        </p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-cancel btn" data-bs-dismiss="modal">
                            Annuler
                        </button>
                        @if($contrat->factures()->count() == 0 && $contrat->affectations()->count() == 0)
                        <form action="{{ route('admin.entreprise.contrats.destroy', $contrat->id) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-confirm-del btn">
                                <i class="bi bi-trash3-fill me-1"></i>Supprimer définitivement
                            </button>
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

{{-- Pagination --}}
@if($contrats->hasPages())
<div class="pagination-wrap">
    <div class="pagination-info">
        Affichage de <strong>{{ $contrats->firstItem() }}</strong>
        à <strong>{{ $contrats->lastItem() }}</strong>
        sur <strong>{{ $contrats->total() }}</strong> résultats
    </div>
    {{ $contrats->appends(request()->query())->links() }}
</div>
@endif

@else
{{-- État vide --}}
<div class="empty-state">
    <div class="empty-icon">
        <i class="bi bi-file-earmark-text"></i>
    </div>
    @if(request()->hasAny(['search','statut']))
    <h5>Aucun résultat</h5>
    <p>Aucun contrat ne correspond à vos critères de recherche.</p>
    <a href="{{ route('admin.entreprise.contrats.index') }}"
        class="btn-new-contrat" style="display:inline-flex;">
        <i class="bi bi-x-circle"></i>
        Effacer les filtres
    </a>
    @else
    <h5>Aucun contrat pour le moment</h5>
    <p>Commencez par créer votre premier contrat de prestation.</p>
    <a href="{{ route('admin.entreprise.contrats.create') }}"
        class="btn-new-contrat" style="display:inline-flex;">
        <i class="bi bi-plus-circle-fill"></i>
        Créer un contrat
    </a>
    @endif
</div>
@endif

</div>

</div>
@endsection