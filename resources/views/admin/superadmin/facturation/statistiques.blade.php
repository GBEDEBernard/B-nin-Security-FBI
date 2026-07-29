@extends('layouts.app')

@section('title', 'Statistiques Facturation - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-graph-up text-success me-2"></i>
                    Statistiques de Facturation
                </h2>
                <p class="text-muted mb-0">Indicateurs clés de performance financière</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Factures du Mois</h6>
                                <h3 class="mb-0 text-primary">{{ $stats['nombre_mois'] ?? 0 }}</h3>
                            </div>
                            <i class="bi bi-receipt fs-1 text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Montant du Mois</h6>
                                <h3 class="mb-0 text-success">{{ number_format($stats['montant_mois'] ?? 0, 0, ',', ' ') }} CFA</h3>
                            </div>
                            <i class="bi bi-currency-dollar fs-1 text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Payé du Mois</h6>
                                <h3 class="mb-0 text-info">{{ number_format($stats['paye_mois'] ?? 0, 0, ',', ' ') }} CFA</h3>
                            </div>
                            <i class="bi bi-check2-all fs-1 text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-secondary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Entreprises</h6>
                                <h3 class="mb-0 text-secondary">{{ $stats['total_entreprises'] ?? 0 }}</h3>
                            </div>
                            <i class="bi bi-building fs-1 text-secondary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Évolution Mensuelle (12 mois)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mois</th>
                                <th>Nombre de Factures</th>
                                <th>Montant Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($evolution as $item)
                            <tr>
                                <td>{{ $item['mois'] }}</td>
                                <td>{{ $item['nombre'] }}</td>
                                <td>{{ number_format($item['montant'] ?? 0, 0, ',', ' ') }} CFA</td>
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
