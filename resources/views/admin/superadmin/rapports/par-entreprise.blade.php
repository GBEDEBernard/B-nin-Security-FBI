@extends('layouts.app')

@section('title', 'Rapports par Entreprise - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-building text-primary me-2"></i>
                Rapports par Entreprise
            </h2>
            <p class="text-muted mb-0">Statistiques détaillées pour chaque entreprise</p>
        </div>
        <div>
            <a href="{{ route('admin.superadmin.rapports.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
            <button class="btn btn-primary" onclick="exporterExcel()">
                <i class="bi bi-file-excel me-1"></i> Exporter Excel
            </button>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Sélectionner une entreprise</label>
                    <select name="entreprise_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Toutes les entreprises</option>
                        @foreach($entreprises as $entreprise)
                        <option value="{{ $entreprise->id }}" {{ request('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                            {{ $entreprise->nom_entreprise }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-building text-primary fs-5"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $entreprises->count() }}</h4>
                            <small class="text-muted">Total Entreprises</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-people text-success fs-5"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $entreprises->sum(fn($e) => $e->employes_count ?? 0) }}</h4>
                            <small class="text-muted">Total Employés</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person-check text-info fs-5"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $entreprises->sum(fn($e) => $e->clients_count ?? 0) }}</h4>
                            <small class="text-muted">Total Clients</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-currency-dollar text-warning fs-5"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ number_format($entreprises->sum(fn($e) => $e->factures->sum('montant_ttc') ?? 0), 0, ',', ' ') }}</h4>
                            <small class="text-muted">CA Total (FCFA)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des entreprises -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0"><i class="bi bi-table me-2"></i>Liste des Entreprises</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Formule</th>
                            <th>Employés</th>
                            <th>Clients</th>
                            <th>Contrats</th>
                            <th>Factures</th>
                            <th>CA Total</th>
                            <th>CA Payé</th>
                            <th>Restant</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entreprises as $entreprise)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2">
                                        {{ substr($entreprise->nom_entreprise ?? 'E', 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $entreprise->nom_entreprise }}</strong>
                                        @if($entreprise->nom_commercial)
                                        <br><small class="text-muted">{{ $entreprise->nom_commercial }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($entreprise->formule)
                                <span class="badge bg-info">{{ $entreprise->formule }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ $entreprise->employes_count ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $entreprise->clients_count ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning bg-opacity-10 text-warning">
                                    {{ $entreprise->contrats_prestation_count ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger bg-opacity-10 text-danger">
                                    {{ $entreprise->factures_count ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ number_format($entreprise->factures->sum('montant_ttc') ?? 0, 0, ',', ' ') }}</strong>
                            </td>
                            <td>
                                <span class="text-success">{{ number_format($entreprise->factures->sum('montant_paye') ?? 0, 0, ',', ' ') }}</span>
                            </td>
                            <td>
                                <span class="text-danger">{{ number_format($entreprise->factures->sum('montant_restant') ?? 0, 0, ',', ' ') }}</span>
                            </td>
                            <td>
                                @if($entreprise->est_active)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.superadmin.entreprises.show', $entreprise->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
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

<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }
</style>

@push('scripts')
<script>
    function exporterExcel() {
        // Fonctionnalité d'export en cours de développement
        alert('Export Excel en cours de développement');
    }
</script>
@endpush
@endsection