@extends('layouts.app')

@section('title', 'Rapport Financier - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-currency-dollar text-success me-2"></i>
                Rapport Financier
            </h2>
            <p class="text-muted mb-0">Statistiques financières consolidées</p>
        </div>
        <div>
            <a href="{{ route('admin.superadmin.rapports.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Imprimer
            </button>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ $dateDebut->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date de fin</label>
                    <input type="date" name="date_fin" class="form-control" value="{{ $dateFin->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Entreprise</label>
                    <select name="entreprise_id" class="form-select">
                        <option value="">Toutes les entreprises</option>
                        @foreach($entreprises as $entreprise)
                        <option value="{{ $entreprise->id }}">{{ $entreprise->nom_entreprise }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques financières -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-receipt text-primary fs-5"></i>
                        </div>
                        <small class="text-muted">Nombre de Factures</small>
                    </div>
                    <h3 class="mb-0">{{ number_format($stats['nombre'], 0, ',', ' ') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-warning bg-opacity-10 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-currency-dollar text-warning fs-5"></i>
                        </div>
                        <small class="text-muted">Montant Total TTC</small>
                    </div>
                    <h3 class="mb-0 text-warning">{{ number_format($stats['montant_total'], 0, ',', ' ') }}</h3>
                    <small class="text-muted">FCFA</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-success bg-opacity-10 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-check-circle text-success fs-5"></i>
                        </div>
                        <small class="text-muted">Montant Payé</small>
                    </div>
                    <h3 class="mb-0 text-success">{{ number_format($stats['montant_paye'], 0, ',', ' ') }}</h3>
                    <small class="text-muted">FCFA</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-danger bg-opacity-10 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-exclamation-circle text-danger fs-5"></i>
                        </div>
                        <small class="text-muted">Montant Restant</small>
                    </div>
                    <h3 class="mb-0 text-danger">{{ number_format($stats['montant_restant'], 0, ',', ' ') }}</h3>
                    <small class="text-muted">FCFA</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Répartition des Paiements</h5>
                </div>
                <div class="card-body">
                    <canvas id="paiementsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2 text-success"></i>Top 10 Entreprises par CA</h5>
                </div>
                <div class="card-body">
                    <canvas id="topEntreprisesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des factures -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0"><i class="bi bi-table me-2"></i>Liste des Factures</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Facture</th>
                            <th>Entreprise</th>
                            <th>Client</th>
                            <th>Date Emission</th>
                            <th>Date Échéance</th>
                            <th>Montant TTC</th>
                            <th>Payé</th>
                            <th>Restant</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($factures as $facture)
                        <tr>
                            <td><strong>{{ $facture->numero_facture ?? $facture->reference }}</strong></td>
                            <td>{{ $facture->entreprise->nom_entreprise ?? '-' }}</td>
                            <td>{{ $facture->client->nomAffichage ?? '-' }}</td>
                            <td>{{ $facture->date_emission?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $facture->date_echeance?->format('d/m/Y') ?? '-' }}</td>
                            <td><strong>{{ number_format($facture->montant_ttc, 0, ',', ' ') }}</strong></td>
                            <td class="text-success">{{ number_format($facture->montant_paye, 0, ',', ' ') }}</td>
                            <td class="text-danger">{{ number_format($facture->montant_restant, 0, ',', ' ') }}</td>
                            <td>
                                @switch($facture->statut)
                                @case('payee')
                                <span class="badge bg-success">Payée</span>
                                @break
                                @case('partiellement_payee')
                                <span class="badge bg-warning">Partielle</span>
                                @break
                                @case('en_retard')
                                <span class="badge bg-danger">En retard</span>
                                @break
                                @default
                                <span class="badge bg-secondary">{{ $facture->statut }}</span>
                                @endswitch
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Aucune facture trouvée
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Graphique répartition des paiements
    const ctx1 = document.getElementById('paiementsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Payé', 'Restant'],
            datasets: [{
                data: [{
                    {
                        $stats['montant_paye']
                    }
                }, {
                    {
                        $stats['montant_restant']
                    }
                }],
                backgroundColor: ['#198754', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Graphique top entreprises
    const ctx2 = document.getElementById('topEntreprisesChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: {
                !!json_encode($factures - > groupBy('entreprise_id') - > map(fn($f, $id) => $f - > first() - > entreprise - > nom_entreprise ?? 'Inconnu') - > take(10) - > values()) !!
            },
            datasets: [{
                label: 'Montant (FCFA)',
                data: {
                    !!json_encode($factures - > groupBy('entreprise_id') - > map(fn($f) => $f - > sum('montant_ttc')) - > take(10) - > values()) !!
                },
                backgroundColor: '#0d6efd',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection