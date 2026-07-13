@extends('layouts.app')

@section('title', 'Statistiques du Journal - Super Admin')

@push('styles')
<style>
    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.superadmin.journal.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h2 class="mb-0">
                    <i class="bi bi-bar-chart text-success me-2"></i>
                    Statistiques du Journal
                </h2>
            </div>
            <p class="text-muted mb-0">Activité des 7 derniers jours</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-activity fs-3 text-primary"></i>
                    </div>
                    <h2 class="fw-bold mb-0">{{ number_format($stats['total_7_jours']) }}</h2>
                    <p class="text-muted mb-0 small">Actions (7 jours)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-person-check fs-3 text-success"></i>
                    </div>
                    <h2 class="fw-bold mb-0">{{ number_format($stats['connexions_7_jours']) }}</h2>
                    <p class="text-muted mb-0 small">Connexions (7 jours)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-plus-circle fs-3 text-info"></i>
                    </div>
                    <h2 class="fw-bold mb-0">{{ number_format($stats['creations_7_jours']) }}</h2>
                    <p class="text-muted mb-0 small">Créations (7 jours)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-pencil fs-3 text-warning"></i>
                    </div>
                    <h2 class="fw-bold mb-0">{{ number_format($stats['modifications_7_jours']) }}</h2>
                    <p class="text-muted mb-0 small">Modifications (7 jours)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Évolution des actions (7 jours)</h5>
                </div>
                <div class="card-body">
                    <canvas id="evolutionChart" height="280"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-trophy me-2"></i>Top 10 utilisateurs</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($topUsers as $index => $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <div>
                                    <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }} me-2">
                                        #{{ $index + 1 }}
                                    </span>
                                    <small>{{ $user->user_name }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $user->total }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Aucune donnée
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('evolutionChart');
    if (!ctx) return;

    const semaine = @json($semaine);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: semaine.map(s => s.date),
            datasets: [
                {
                    label: 'Total',
                    data: semaine.map(s => s.total),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.3,
                },
                {
                    label: 'Connexions',
                    data: semaine.map(s => s.connexions),
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    fill: true,
                    tension: 0.3,
                },
                {
                    label: 'Créations',
                    data: semaine.map(s => s.creations),
                    borderColor: '#0dcaf0',
                    backgroundColor: 'rgba(13, 202, 240, 0.1)',
                    fill: true,
                    tension: 0.3,
                },
                {
                    label: 'Modifications',
                    data: semaine.map(s => s.modifications),
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    fill: true,
                    tension: 0.3,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
});
</script>
@endpush
