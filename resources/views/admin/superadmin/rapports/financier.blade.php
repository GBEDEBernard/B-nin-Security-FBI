@extends('layouts.app')

@section('title', 'Rapport Financier - Super Admin')

@push('styles')
<style>
    .stat-card { animation: fadeIn 0.5s ease forwards; opacity: 0; }
    .stat-card:nth-child(1) { animation-delay: 0.05s; }
    .stat-card:nth-child(2) { animation-delay: 0.1s; }
    .stat-card:nth-child(3) { animation-delay: 0.15s; }
    .stat-card:nth-child(4) { animation-delay: 0.2s; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold"><i class="bi bi-currency-dollar me-2 text-success"></i>Rapport Financier</h2>
            <p class="text-muted mb-0">{{ $dateDebut instanceof \Carbon\Carbon ? $dateDebut->format('d/m/Y') : $dateDebut }} - {{ $dateFin instanceof \Carbon\Carbon ? $dateFin->format('d/m/Y') : $dateFin }}</p>
        </div>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2 align-items-end">
                <div>
                    <label class="form-label small">Du</label>
                    <input type="date" name="date_debut" class="form-control form-control-sm" value="{{ $dateDebut instanceof \Carbon\Carbon ? $dateDebut->format('Y-m-d') : $dateDebut }}">
                </div>
                <div>
                    <label class="form-label small">Au</label>
                    <input type="date" name="date_fin" class="form-control form-control-sm" value="{{ $dateFin instanceof \Carbon\Carbon ? $dateFin->format('Y-m-d') : $dateFin }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel"></i></button>
            </form>
            <a href="{{ route('admin.superadmin.rapports.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3 stat-card">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary fs-1 mb-2"><i class="bi bi-receipt"></i></div>
                    <h3 class="fw-bold mb-0">{{ $stats['nombre'] }}</h3>
                    <small class="text-muted">Factures</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 stat-card">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info fs-1 mb-2"><i class="bi bi-currency-dollar"></i></div>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['montant_total'], 0, ',', ' ') }}</h3>
                    <small class="text-muted">Total CFA</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 stat-card">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success fs-1 mb-2"><i class="bi bi-check2-circle"></i></div>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['montant_paye'], 0, ',', ' ') }}</h3>
                    <small class="text-muted">Payé CFA</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 stat-card">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-danger fs-1 mb-2"><i class="bi bi-exclamation-triangle"></i></div>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['montant_restant'], 0, ',', ' ') }}</h3>
                    <small class="text-muted">Impayé CFA</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-list-ul me-2"></i>Détail des factures</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr><th>N°</th><th>Entreprise</th><th>Montant</th><th>Payé</th><th>Restant</th><th>Statut</th></tr>
                    </thead>
                    <tbody>
                        @forelse($factures as $f)
                        <tr>
                            <td class="fw-semibold">{{ $f->numero_facture }}</td>
                            <td>{{ $f->entreprise?->nom_entreprise ?? '-' }}</td>
                            <td>{{ number_format($f->montant_ttc, 0, ',', ' ') }} CFA</td>
                            <td class="text-success">{{ number_format($f->montant_paye, 0, ',', ' ') }} CFA</td>
                            <td class="text-danger">{{ number_format($f->montant_restant, 0, ',', ' ') }} CFA</td>
                            <td><span class="badge bg-{{ ['emise'=>'secondary','envoyee'=>'info','payee'=>'success','partiellement_payee'=>'warning','impayee'=>'danger','annulee'=>'dark'][$f->statut] ?? 'secondary' }}">{{ $f->statut_label }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Aucune facture</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection