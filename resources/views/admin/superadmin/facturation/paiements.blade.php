@extends('layouts.app')

@section('title', 'Historique des Paiements - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-credit-card text-success me-2"></i>
                Historique des Paiements
            </h2>
            <p class="text-muted mb-0">Tous les paiements enregistrés</p>
        </div>
        <div>
            <a href="{{ route('admin.superadmin.facturation.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-receipt me-1"></i> Factures
            </a>
            <a href="{{ route('admin.superadmin.facturation.creances') }}" class="btn btn-outline-warning ms-2">
                <i class="bi bi-exclamation-triangle me-1"></i> Créances
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Total des paiements</h6>
                            <h3 class="mb-0 text-success">{{ $stats['total_paiements'] }}</h3>
                        </div>
                        <i class="bi bi-credit-card fs-1 text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Montant total encaissé</h6>
                            <h3 class="mb-0 text-info">{{ number_format($stats['montant_total'], 0, ',', ' ') }} CFA</h3>
                        </div>
                        <i class="bi bi-currency-dollar fs-1 text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.superadmin.facturation.paiements') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Entreprise</label>
                        <select name="entreprise_id" class="form-select">
                            <option value="">Toutes les entreprises</option>
                            @foreach(\App\Models\Entreprise::orderBy('nom_entreprise')->get() as $entreprise)
                            <option value="{{ $entreprise->id }}" {{ request('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                                {{ $entreprise->nom_entreprise }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date début</label>
                        <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date fin</label>
                        <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-funnel me-1"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.superadmin.facturation.paiements') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Paiements</h5>
            <span class="badge bg-secondary">{{ $paiements->total() }} paiement(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Facture</th>
                            <th>Entreprise</th>
                            <th>Montant</th>
                            <th>Mode</th>
                            <th>Date</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paiements as $paiement)
                        <tr>
                            <td><strong>{{ $paiement->reference ?? '-' }}</strong></td>
                            <td>
                                <a href="{{ route('admin.superadmin.facturation.show', $paiement->facture_id) }}">
                                    {{ $paiement->facture?->numero_facture ?? 'N/A' }}
                                </a>
                            </td>
                            <td>{{ $paiement->facture?->entreprise?->nom_entreprise ?? '-' }}</td>
                            <td class="fw-semibold">{{ number_format($paiement->montant, 0, ',', ' ') }} CFA</td>
                            <td>
                                @php
                                $modeLabels = ['mobile_money' => 'Mobile Money', 'carte' => 'Carte Bancaire', 'virement' => 'Virement', 'especes' => 'Espèces', 'cheque' => 'Chèque'];
                                $modeColors = ['mobile_money' => 'info', 'carte' => 'primary', 'virement' => 'success', 'especes' => 'warning', 'cheque' => 'secondary'];
                                @endphp
                                <span class="badge bg-{{ $modeColors[$paiement->mode_paiement] ?? 'secondary' }}">
                                    {{ $modeLabels[$paiement->mode_paiement] ?? $paiement->mode_paiement }}
                                </span>
                            </td>
                            <td>{{ $paiement->date_paiement?->format('d/m/Y') ?? '-' }}</td>
                            <td><small class="text-muted">{{ Str::limit($paiement->notes, 30) ?: '-' }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Aucun paiement trouvé
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($paiements->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                {{ $paiements->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection