@extends('layouts.app')

@section('title', 'Mes Factures')

@push('styles')
<style>
    :root {
        --pp-surface: #ffffff;
        --pp-border: #e9ecef;
        --pp-text: #212529;
        --pp-text-muted: #6c757d;
        --pp-bg-soft: #f8f9fa;
        --pp-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
    }
    :root[data-bs-theme="dark"] {
        --pp-surface: #1a1d27;
        --pp-border: #2a2d3a;
        --pp-text: #f0f2f8;
        --pp-text-muted: #8b90a8;
        --pp-bg-soft: #1a1d27;
        --pp-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.3);
    }
    .facture-card {
        border: none;
        border-radius: 16px;
        background: var(--pp-surface);
        box-shadow: var(--pp-shadow);
        transition: transform 0.3s ease;
    }
    .facture-card:hover {
        transform: translateY(-3px);
    }
    .data-table thead th {
        background: var(--pp-bg-soft);
        border-bottom: 2px solid var(--pp-border);
        font-weight: 600;
        color: var(--pp-text-muted);
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    .data-table tbody td {
        color: var(--pp-text);
        vertical-align: middle;
    }
    .data-table tbody tr:hover {
        background: rgba(25, 135, 84, 0.05);
    }
    [data-bs-theme="dark"] .data-table tbody tr:hover {
        background: rgba(25, 135, 84, 0.1);
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-receipt me-2"></i>Mes Factures</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Factures</li>
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

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="facture-card card border-0 h-100" style="background: linear-gradient(135deg, #0d6efd22, #0d6efd11);">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-receipt fs-3 text-primary"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                                <p class="text-muted mb-0 small">Total Factures</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="facture-card card border-0 h-100" style="background: linear-gradient(135deg, #19875422, #19875411);">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-currency-dollar fs-3 text-success"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold">{{ number_format($stats['montant_total'], 0, ',', ' ') }}</h3>
                                <p class="text-muted mb-0 small">Montant Total</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="facture-card card border-0 h-100" style="background: linear-gradient(135deg, #0dcaf022, #0dcaf011);">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-check2-all fs-3 text-info"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold">{{ number_format($stats['montant_paye'], 0, ',', ' ') }}</h3>
                                <p class="text-muted mb-0 small">Payé</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="facture-card card border-0 h-100" style="background: linear-gradient(135deg, #dc354522, #dc354511);">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-exclamation-triangle fs-3 text-danger"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold">{{ $stats['impayees'] }}</h3>
                                <p class="text-muted mb-0 small">Impayées</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card facture-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.entreprise.factures.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select">
                                <option value="">Tous</option>
                                <option value="emise" {{ request('statut') == 'emise' ? 'selected' : '' }}>Émise</option>
                                <option value="envoyee" {{ request('statut') == 'envoyee' ? 'selected' : '' }}>Envoyée</option>
                                <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>Payée</option>
                                <option value="partiellement_payee" {{ request('statut') == 'partiellement_payee' ? 'selected' : '' }}>Partielle</option>
                                <option value="impayee" {{ request('statut') == 'impayee' ? 'selected' : '' }}>Impayée</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date début</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date fin</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-funnel me-1"></i> Filtrer
                            </button>
                            <a href="{{ route('admin.entreprise.factures.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card facture-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Historique des Factures</h5>
                <span class="badge bg-secondary">{{ $factures->total() }} facture(s)</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Abonnement</th>
                                <th>Date</th>
                                <th>Période</th>
                                <th>Montant TTC</th>
                                <th>Payé</th>
                                <th>Restant</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($factures as $facture)
                            <tr>
                                <td><strong>{{ $facture->numero_facture }}</strong></td>
                                <td>
                                    @if($facture->abonnement)
                                    <span class="badge bg-info">{{ $facture->abonnement->formule_label }}</span>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{ $facture->date_emission?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ str_pad($facture->mois, 2, '0', STR_PAD_LEFT) . '/' . $facture->annee }}</td>
                                <td class="fw-semibold">{{ number_format($facture->montant_ttc, 0, ',', ' ') }} CFA</td>
                                <td>{{ number_format($facture->montant_paye, 0, ',', ' ') }} CFA</td>
                                <td class="text-{{ $facture->montant_restant > 0 ? 'danger' : 'success' }}">
                                    {{ number_format($facture->montant_restant, 0, ',', ' ') }} CFA
                                </td>
                                <td>
                                    @php
                                    $badges = [
                                        'emise' => 'secondary',
                                        'envoyee' => 'info',
                                        'payee' => 'success',
                                        'partiellement_payee' => 'warning',
                                        'impayee' => 'danger',
                                        'annulee' => 'dark',
                                    ];
                                    $labels = [
                                        'emise' => 'Émise',
                                        'envoyee' => 'Envoyée',
                                        'payee' => 'Payée',
                                        'partiellement_payee' => 'Partielle',
                                        'impayee' => 'Impayée',
                                        'annulee' => 'Annulée',
                                    ];
                                    @endphp
                                    <span class="badge bg-{{ $badges[$facture->statut] ?? 'secondary' }}">
                                        {{ $labels[$facture->statut] ?? $facture->statut }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.entreprise.factures.show', $facture->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.entreprise.factures.download', $facture->id) }}"
                                       class="btn btn-sm btn-outline-success" title="Télécharger PDF">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Aucune facture trouvée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($factures->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $factures->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
