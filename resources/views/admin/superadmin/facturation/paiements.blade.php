@extends('layouts.app')

@section('title', 'Paiements - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-credit-card text-success me-2"></i>
                    Paiements
                </h2>
                <p class="text-muted mb-0">Historique de tous les paiements enregistrés</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Paiements</h6>
                                <h3 class="mb-0 text-primary">{{ $stats['total_paiements'] ?? 0 }}</h3>
                            </div>
                            <i class="bi bi-credit-card fs-1 text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Montant Total</h6>
                                <h3 class="mb-0 text-success">{{ number_format($stats['montant_total'] ?? 0, 0, ',', ' ') }} CFA</h3>
                            </div>
                            <i class="bi bi-currency-dollar fs-1 text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des Paiements</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Entreprise</th>
                                <th>Date Paiement</th>
                                <th>Montant</th>
                                <th>Mode</th>
                                <th>Référence</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paiements as $paiement)
                            <tr>
                                <td><strong>{{ $paiement->facture?->numero_facture ?? '-' }}</strong></td>
                                <td>{{ $paiement->facture?->entreprise?->nom_entreprise ?? '-' }}</td>
                                <td>{{ $paiement->date_paiement ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') : '-' }}</td>
                                <td>{{ number_format($paiement->montant ?? 0, 0, ',', ' ') }} CFA</td>
                                <td>{{ $paiement->mode_paiement ?? '-' }}</td>
                                <td>{{ $paiement->reference ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Voir"><i class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Aucun paiement trouvé</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">{{ $paiements->links() ?? '' }}</div>
            </div>
        </div>
    </div>
@endsection
