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
                <p class="text-muted mb-0">Factures avec montant restant dû</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Créances</h6>
                                <h3 class="mb-0 text-warning">{{ number_format($stats['total_creances'] ?? 0, 0, ',', ' ') }} CFA</h3>
                            </div>
                            <i class="bi bi-currency-dollar fs-1 text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Factures Impayées</h6>
                                <h3 class="mb-0 text-info">{{ $stats['nombre_factures'] ?? 0 }}</h3>
                            </div>
                            <i class="bi bi-receipt fs-1 text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">En Retard</h6>
                                <h3 class="mb-0 text-danger">{{ $stats['en_retard'] ?? 0 }}</h3>
                            </div>
                            <i class="bi bi-clock-history fs-1 text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des Créances</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Entreprise</th>
                                <th>Client</th>
                                <th>Date Échéance</th>
                                <th>Montant TTC</th>
                                <th>Restant Dû</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($creances as $facture)
                            <tr>
                                <td><strong>{{ $facture->numero_facture }}</strong></td>
                                <td>{{ $facture->entreprise?->nom_entreprise ?? '-' }}</td>
                                <td>{{ $facture->client?->nomAffichage ?? '-' }}</td>
                                <td>{{ $facture->date_echeance ? \Carbon\Carbon::parse($facture->date_echeance)->format('d/m/Y') : '-' }}</td>
                                <td>{{ number_format($facture->montant_ttc ?? 0, 0, ',', ' ') }} CFA</td>
                                <td class="text-danger fw-bold">{{ number_format($facture->montant_restant ?? 0, 0, ',', ' ') }} CFA</td>
                                <td>
                                    @if($facture->date_echeance && \Carbon\Carbon::parse($facture->date_echeance)->isPast())
                                        <span class="badge bg-danger">En retard</span>
                                    @else
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Voir"><i class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Aucune créance trouvée</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">{{ $creances->links() ?? '' }}</div>
            </div>
        </div>
    </div>
@endsection
