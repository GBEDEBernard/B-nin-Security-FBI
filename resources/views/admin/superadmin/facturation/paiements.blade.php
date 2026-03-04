@extends('layouts.app')

@section('title', 'Paiements - Facturation')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-credit-card text-success me-2"></i>
                Historique des Paiements
            </h2>
            <p class="text-muted mb-0">Liste de tous les paiements reçus</p>
        </div>
        <a href="{{ route('admin.superadmin.facturation.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Retour aux factures
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Paiements</h6>
                    <h3 class="mb-0 text-primary">{{ $stats['total_paiements'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="card-title text-muted">Montant Total Reçu</h6>
                    <h3 class="mb-0 text-success">{{ number_format($stats['montant_total'] ?? 0, 0, ',', ' ') }} CFA</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Facture</th>
                            <th>Entreprise</th>
                            <th>Mode</th>
                            <th>Référence</th>
                            <th>Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paiements as $paiement)
                        <tr>
                            <td>{{ $paiement->date_paiement ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $paiement->facture?->numero_facture ?? '-' }}</td>
                            <td>{{ $paiement->facture?->entreprise?->nom_entreprise ?? '-' }}</td>
                            <td>{{ $paiement->mode_paiement ?? '-' }}</td>
                            <td>{{ $paiement->reference ?? '-' }}</td>
                            <td class="text-success">{{ number_format($paiement->montant, 0, ',', ' ') }} CFA</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucun paiement enregistré</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $paiements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection