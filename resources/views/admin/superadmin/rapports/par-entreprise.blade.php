@extends('layouts.app')

@section('title', 'Rapport par Entreprise - Super Admin')

@push('styles')
<style>
    .entreprise-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        animation: fadeIn 0.5s ease forwards;
        opacity: 0;
    }
    .entreprise-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .entreprise-card:nth-child(1) { animation-delay: 0.05s; }
    .entreprise-card:nth-child(2) { animation-delay: 0.1s; }
    .entreprise-card:nth-child(3) { animation-delay: 0.15s; }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold"><i class="bi bi-building me-2 text-primary"></i>Rapport par Entreprise</h2>
            <p class="text-muted mb-0">Sélectionnez une entreprise pour voir le détail</p>
        </div>
        <a href="{{ route('admin.superadmin.rapports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="row g-4">
        @forelse($entreprises as $e)
        <div class="col-md-4 entreprise-card">
            <a href="{{ route('admin.superadmin.rapports.par-entreprise', ['entreprise_id' => $e->id]) }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 48px; height: 48px; background: {{ $e->est_active ? '#f0fdf4' : '#fef2f2' }};">
                                <i class="bi bi-building fs-4 {{ $e->est_active ? 'text-success' : 'text-danger' }}"></i>
                            </div>
                            <div class="min-w-0">
                                <h5 class="mb-0 fw-semibold text-dark">{{ $e->nom_entreprise }}</h5>
                                <small class="text-muted">{{ $e->abonnement?->formule_label ?? 'Sans abonnement' }}</small>
                            </div>
                        </div>
                        <div class="row g-2 text-center">
                            <div class="col-4 border-end">
                                <div class="fw-bold fs-5 text-primary">{{ $e->employes_count }}</div>
                                <small class="text-muted">Employés</small>
                            </div>
                            <div class="col-4 border-end">
                                <div class="fw-bold fs-5 text-info">{{ $e->clients_count }}</div>
                                <small class="text-muted">Clients</small>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold fs-5 text-success">{{ $e->contrats_prestation_count }}</div>
                                <small class="text-muted">Contrats</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            Aucune entreprise trouvée
        </div>
        @endforelse
    </div>
</div>
@endsection