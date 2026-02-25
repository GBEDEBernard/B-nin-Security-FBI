@extends('layouts.app')

@section('title', 'Gestion des Rapports - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-bar-chart-fill text-info me-2"></i>
                    Rapports Globaux
                </h2>
                <p class="text-muted mb-0">Statistiques consolidées de toutes les entreprises</p>
            </div>
            <div>
                <button class="btn btn-outline-secondary me-2">
                    <i class="bi bi-download me-1"></i> Exporter PDF
                </button>
                <button class="btn btn-outline-primary">
                    <i class="bi bi-file-excel me-1"></i> Exporter Excel
                </button>
            </div>
        </div>

        <!-- Cartes de statistiques principales -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-building fs-1 text-primary mb-2"></i>
                        <h4 class="mb-1">{{ $stats['total_entreprises'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Entreprises</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-check-circle fs-1 text-success mb-2"></i>
                        <h4 class="mb-1">{{ $stats['entreprises_actives'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Actives</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-people fs-1 text-info mb-2"></i>
                        <h4 class="mb-1">{{ $stats['total_clients'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Clients</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-person-badge fs-1 text-warning mb-2"></i>
                        <h4 class="mb-1">{{ $stats['total_employes'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Employés</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-receipt fs-1 text-danger mb-2"></i>
                        <h4 class="mb-1">{{ $stats['total_factures'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Factures</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center h-100 border-success">
                    <div class="card-body">
                        <i class="bi bi-currency-dollar fs-1 text-success mb-2"></i>
                        <h4 class="mb-1">{{ number_format($stats['chiffre_affaires'] ?? 0, 0, ',', ' ') }}</h4>
                        <p class="text-muted mb-0">CA Total (CFA)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques et détails -->
        <div class="row mb-4">
            <!-- Répartition par entreprise -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Répartition des Entreprises</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-pie-chart fs-1"></i>
                            <p class="mt-2">Graphique à intégrer (Chart.js)</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Activité récente -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-activity me-2"></i>Activité Récente</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Entreprise</th>
                                        <th>Employés</th>
                                        <th>Clients</th>
                                        <th>Contrats</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Données à afficher</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau récapitulatif -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-table me-2"></i>Résumé par Entreprise</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Entreprise</th>
                                <th>Employés</th>
                                <th>Clients</th>
                                <th>Contrats</th>
                                <th>Factures</th>
                                <th>CA Estimé</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Données à afficher depuis la base</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection