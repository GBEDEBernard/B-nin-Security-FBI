@extends('layouts.app')

@section('title', 'Statistiques de Facturation - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-graph-up text-success me-2"></i>
                Statistiques de Facturation
            </h2>
            <p class="text-muted mb-0">Analyse des factures et paiements</p>
        </div>
        <div>
            <a href="{{ route('admin.superadmin.facturation.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-receipt me-1"></i> Factures
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Factures ce mois</h6>
                            <h3 class="mb-0 text-primary">{{ $stats['nombre_mois'] }}</h3>
                        </div>
                        <i class="bi bi-receipt fs-1 text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Montant du mois</h6>
                            <h3 class="mb-0 text-success">{{ number_format($stats['montant_mois'], 0, ',', ' ') }} CFA</h3>
                        </div>
                        <i class="bi bi-currency-dollar fs-1 text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Payé ce mois</h6>
                            <h3 class="mb-0 text-info">{{ number_format($stats['paye_mois'], 0, ',', ' ') }} CFA</h3>
                        </div>
                        <i class="bi bi-check2-all fs-1 text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Impayé ce mois</h6>
                            <h3 class="mb-0 text-warning">{{ number_format($stats['montant_mois'] - $stats['paye_mois'], 0, ',', ' ') }} CFA</h3>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-secondary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Entreprises facturées</h6>
                            <h3 class="mb-0 text-secondary">{{ $stats['total_entreprises'] }}</h3>
                        </div>
                        <i class="bi bi-building fs-1 text-secondary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-bar-chart-line me-2"></i>Évolution sur 12 mois</h5>
        </div>
        <div class="card-body">
            <canvas id="evolutionChart" height="100"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('evolutionChart').getContext('2d');
    const evolution = @json($evolution);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: evolution.map(e => e.mois),
            datasets: [{
                label: 'Nombre de factures',
                data: evolution.map(e => e.nombre),
                borderColor: '#6b7280',
                backgroundColor: 'rgba(107, 114, 128, 0.06)',
                borderWidth: 2,
                borderDash: [5, 5],
                fill: false,
                tension: 0.4,
                pointRadius: 3,
                pointHoverRadius: 6,
                pointBackgroundColor: '#6b7280',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                yAxisID: 'y',
            }, {
                label: 'Montant total (CFA)',
                data: evolution.map(e => e.montant),
                borderColor: '#0d6efd',
                yAxisID: 'y1',
                backgroundColor: 'rgba(13, 110, 253, 0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 7,
                pointBackgroundColor: '#0d6efd',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                yAxisID: 'y1',
            }, {
                label: 'Payé (CFA)',
                data: evolution.map(e => e.paye),
                borderColor: '#16a34a',
                yAxisID: 'y1',
                backgroundColor: 'rgba(22, 163, 74, 0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 7,
                pointBackgroundColor: '#16a34a',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }, {
                label: 'Impayé (CFA)',
                data: evolution.map(e => e.impaye),
                borderColor: '#dc2626',
                backgroundColor: 'rgba(220, 38, 38, 0.06)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 7,
                pointBackgroundColor: '#dc2626',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                yAxisID: 'y1',
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 1200,
                easing: 'easeInOutQuart',
            },
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 13 },
                    }
                },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.85)',
                        padding: 14,
                        cornerRadius: 10,
                        titleFont: { size: 13, weight: '600' },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                const labels = {
                                    'Nombre de factures': ' Factures: ',
                                    'Montant total (CFA)': ' Total: ',
                                    'Payé (CFA)': ' Payé: ',
                                    'Impayé (CFA)': ' Impayé: ',
                                };
                                let prefix = labels[context.dataset.label] || context.dataset.label + ': ';
                                let value = context.parsed.y.toLocaleString();
                                if (context.dataset.yAxisID === 'y1') {
                                    value += ' CFA';
                                }
                                return prefix + value;
                            }
                        }
                    }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    ticks: { stepSize: 1 },
                    title: {
                        display: true,
                        text: 'Nombre de factures',
                        color: '#6c757d',
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' },
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    title: {
                        display: true,
                        text: 'Montant (CFA)',
                        color: '#6c757d',
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' CFA';
                        }
                    }
                },
                x: {
                    grid: { display: false },
                }
            }
        }
    });
});
</script>
@endpush