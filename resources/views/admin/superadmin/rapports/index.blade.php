@extends('layouts.app')

@section('title', 'Rapports Globaux - Super Admin')

@push('styles')
<style>
    @keyframes countUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideRight {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .stat-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        animation: countUp 0.6s ease forwards;
        opacity: 0;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }
    .stat-card .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stat-card:nth-child(1) { animation-delay: 0.05s; }
    .stat-card:nth-child(2) { animation-delay: 0.1s; }
    .stat-card:nth-child(3) { animation-delay: 0.15s; }
    .stat-card:nth-child(4) { animation-delay: 0.2s; }
    .stat-card:nth-child(5) { animation-delay: 0.25s; }
    .stat-card:nth-child(6) { animation-delay: 0.3s; }
    .chart-card, .activity-card, .table-card {
        animation: fadeIn 0.8s ease forwards;
        opacity: 0;
    }
    .chart-card { animation-delay: 0.35s; }
    .activity-card { animation-delay: 0.4s; }
    .table-card { animation-delay: 0.45s; }
    .entreprise-row {
        animation: slideRight 0.5s ease forwards;
        opacity: 0;
    }
    .badge-statut {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold">
                <i class="bi bi-bar-chart-fill text-info me-2"></i>
                Rapports Globaux
            </h2>
            <p class="text-muted mb-0">Statistiques consolidées de toutes les entreprises</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="window.location.reload()">
                <i class="bi bi-arrow-clockwise me-1"></i> Actualiser
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-2 col-6 stat-card">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #eef2ff; color: #4f46e5;">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_entreprises'] }}</h3>
                        <small class="text-muted">Entreprises</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 stat-card">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $stats['entreprises_actives'] }}</h3>
                        <small class="text-muted">Actives</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 stat-card">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #f0f9ff; color: #0891b2;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_clients'] }}</h3>
                        <small class="text-muted">Clients</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 stat-card">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fffbeb; color: #d97706;">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_employes'] }}</h3>
                        <small class="text-muted">Employés</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 stat-card">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fef2f2; color: #dc2626;">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_factures'] }}</h3>
                        <small class="text-muted">Factures</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 stat-card">
            <div class="card h-100 border-0 shadow-sm border-start border-4 border-success">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold fs-5">{{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }}</h3>
                        <small class="text-muted">CA Total (CFA)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm chart-card">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-pie-chart me-2 text-primary"></i>
                        Répartition par Entreprise
                    </h5>
                    <span class="badge bg-light text-dark">{{ $stats['total_entreprises'] }} entreprises</span>
                </div>
                <div class="card-body">
                    <canvas id="repChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm activity-card">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-activity me-2 text-success"></i>
                        Top Entreprises (Employés)
                    </h5>
                    <span class="badge bg-light text-dark">{{ $stats['total_employes'] }} employés</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentEntreprises as $e)
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-start-0 border-end-0">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle"
                                     style="width: 40px; height: 40px; background: {{ $loop->index < 3 ? ['#fef2f2', '#fffbeb', '#f0fdf4'][$loop->index] : '#f8f9fa' }}; color: {{ $loop->index < 3 ? ['#dc2626', '#d97706', '#16a34a'][$loop->index] : '#6c757d' }};">
                                    <span class="fw-bold">{{ $loop->iteration }}</span>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $e->nom_entreprise }}</p>
                                    <small class="text-muted">{{ $e->abonnement?->formule_label ?? 'N/A' }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold">{{ $e->employes_count }}</span>
                                <small class="text-muted d-block">employés</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Aucune donnée
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm table-card">
        <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-table me-2 text-secondary"></i>
                Résumé par Entreprise
            </h5>
            <span class="badge bg-light text-dark">{{ $entreprises->count() }} entreprise(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Entreprise</th>
                            <th class="text-center">Employés</th>
                            <th class="text-center">Clients</th>
                            <th class="text-center">Contrats</th>
                            <th class="text-center">Factures</th>
                            <th class="text-end">CA Total</th>
                            <th class="text-end">CA Payé</th>
                            <th class="text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entreprises as $e)
                        <tr class="entreprise-row" style="animation-delay: {{ $loop->index * 0.04 }}s;">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 34px; height: 34px; background: {{ $e->est_active ? '#f0fdf4' : '#fef2f2' }};">
                                        <i class="bi bi-building {{ $e->est_active ? 'text-success' : 'text-danger' }}"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold">{{ $e->nom_entreprise }}</span>
                                        @if($e->abonnement?->formule_label)
                                        <br><small class="text-muted">{{ $e->abonnement->formule_label }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-semibold">{{ $e->employes_count }}</td>
                            <td class="text-center fw-semibold">{{ $e->clients_count }}</td>
                            <td class="text-center fw-semibold">{{ $e->contrats_prestation_count }}</td>
                            <td class="text-center fw-semibold">{{ $e->factures_count }}</td>
                            <td class="text-end fw-semibold">{{ number_format($e->ca_total, 0, ',', ' ') }} CFA</td>
                            <td class="text-end">
                                <span class="text-success">{{ number_format($e->ca_paye, 0, ',', ' ') }} CFA</span>
                            </td>
                            <td class="text-center">
                                @if($e->est_active)
                                <span class="badge-statut bg-success bg-opacity-10 text-success">Active</span>
                                @else
                                <span class="badge-statut bg-danger bg-opacity-10 text-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Aucune entreprise trouvée
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colors = ['#4f46e5', '#0891b2', '#16a34a', '#d97706', '#dc2626', '#7c3aed', '#db2777', '#ea580c'];
    const entreprises = @json($entreprises->map(fn($e) => ['nom' => $e->nom_entreprise, 'ca' => $e->ca_total, 'employes' => $e->employes_count]));

    new Chart(document.getElementById('repChart'), {
        type: 'doughnut',
        data: {
            labels: entreprises.map(e => e.nom),
            datasets: [{
                data: entreprises.map(e => e.ca > 0 ? e.ca : 1),
                backgroundColor: entreprises.map((_, i) => colors[i % colors.length]),
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 12,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { size: 11 },
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.parsed;
                            const pct = ((value / total) * 100).toFixed(1);
                            return ' ' + context.label + ': ' + value.toLocaleString() + ' CFA (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush