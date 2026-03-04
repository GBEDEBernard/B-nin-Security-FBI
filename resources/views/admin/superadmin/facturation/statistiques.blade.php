@extends('layouts.app')

@section('title', 'Statistiques - Facturation')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-bar-chart text-success me-2"></i>
                Statistiques de Facturation
            </h2>
            <p class="text-muted mb-0">Vue d'ensemble de la facturation</p>
        </div>
        <a href="{{ route('admin.superadmin.facturation.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Retour aux factures
        </a>
    </div>

    <!-- Stats du mois en cours -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="card-title text-muted">Factures ce mois</h6>
                    <h3 class="mb-0 text-primary">{{ $stats['nombre_mois'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="card-title text-muted">Montant TTC ce mois</h6>
                    <h3 class="mb-0 text-success">{{ number_format($stats['montant_mois'] ?? 0, 0, ',', ' ') }} CFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="card-title text-muted">Payé ce mois</h6>
                    <h3 class="mb-0 text-info">{{ number_format($stats['paye_mois'] ?? 0, 0, ',', ' ') }} CFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="card-title text-muted">Entreprises facturées</h6>
                    <h3 class="mb-0 text-warning">{{ $stats['total_entreprises'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Évolution mensuelle -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Évolution mensuelle (12 derniers mois)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th class="text-center">Nombre de factures</th>
                            <th class="text-end">Montant TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evolution as $item)
                        <tr>
                            <td>{{ $item['mois'] }}</td>
                            <td class="text-center">{{ $item['nombre'] }}</td>
                            <td class="text-end">{{ number_format($item['montant'], 0, ',', ' ') }} CFA</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Aucune donnée disponible</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection