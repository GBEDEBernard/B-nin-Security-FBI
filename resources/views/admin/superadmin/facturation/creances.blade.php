@extends('layouts.app')

@section('title', 'Créances - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                Créances
            </h2>
            <p class="text-muted mb-0">Factures impayées ou partiellement payées</p>
        </div>
        <div>
            <a href="{{ route('admin.superadmin.facturation.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-receipt me-1"></i> Factures
            </a>
            <a href="{{ route('admin.superadmin.facturation.paiements') }}" class="btn btn-outline-success ms-2">
                <i class="bi bi-credit-card me-1"></i> Paiements
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Total des créances</h6>
                            <h3 class="mb-0 text-warning">{{ number_format($stats['total_creances'], 0, ',', ' ') }} CFA</h3>
                        </div>
                        <i class="bi bi-currency-dollar fs-1 text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Factures en retard</h6>
                            <h3 class="mb-0 text-danger">{{ $stats['en_retard'] }}</h3>
                        </div>
                        <i class="bi bi-clock-history fs-1 text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Nombre de factures</h6>
                            <h3 class="mb-0 text-primary">{{ $stats['nombre_factures'] }}</h3>
                        </div>
                        <i class="bi bi-receipt fs-1 text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.superadmin.facturation.creances') }}">
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
                    <div class="col-md-6"></div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i> Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Factures impayées</h5>
            <span class="badge bg-secondary">{{ $creances->total() }} facture(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>N° Facture</th>
                            <th>Entreprise</th>
                            <th>Montant TTC</th>
                            <th>Payé</th>
                            <th>Restant</th>
                            <th>Échéance</th>
                            <th>Statut</th>
                            <th>Retard</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($creances as $facture)
                        @php
                        $estEnRetard = $facture->date_echeance && $facture->date_echeance->isPast();
                        $badges = ['emise' => 'secondary', 'envoyee' => 'info', 'payee' => 'success', 'partiellement_payee' => 'warning', 'impayee' => 'danger', 'annulee' => 'dark'];
                        $labels = ['emise' => 'Émise', 'envoyee' => 'Envoyée', 'payee' => 'Payée', 'partiellement_payee' => 'Partielle', 'impayee' => 'Impayée', 'annulee' => 'Annulée'];
                        @endphp
                        <tr class="{{ $estEnRetard ? 'table-danger' : '' }}">
                            <td><strong>{{ $facture->numero_facture }}</strong></td>
                            <td>{{ $facture->entreprise?->nom_entreprise ?? '-' }}</td>
                            <td class="fw-semibold">{{ number_format($facture->montant_ttc, 0, ',', ' ') }} CFA</td>
                            <td>{{ number_format($facture->montant_paye, 0, ',', ' ') }} CFA</td>
                            <td class="text-danger fw-bold">{{ number_format($facture->montant_restant, 0, ',', ' ') }} CFA</td>
                            <td>{{ $facture->date_echeance?->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $badges[$facture->statut] ?? 'secondary' }}">
                                    {{ $labels[$facture->statut] ?? $facture->statut }}
                                </span>
                            </td>
                            <td>
                                @if($estEnRetard)
                                <span class="badge bg-danger">
                                    {{ $facture->date_echeance->diffInDays(now()) }} jour(s)
                                </span>
                                @else
                                <span class="badge bg-success">À jour</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.superadmin.facturation.show', $facture->id) }}"
                                   class="btn btn-sm btn-outline-primary" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-check2-all fs-1 d-block mb-2 text-success"></i>
                                Aucune créance en cours
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($creances->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                {{ $creances->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection