@extends('layouts.app')

@section('title', 'Gestion de la Facturation - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-receipt-cutoff text-success me-2"></i>
                    Facturation Globale
                </h2>
                <p class="text-muted mb-0">Vue consolidée de toutes les factures</p>
            </div>
            <div>
                <button class="btn btn-outline-secondary me-2">
                    <i class="bi bi-download me-1"></i> Exporter
                </button>
                <button class="btn btn-success">
                    <i class="bi bi-plus-lg me-1"></i> Nouvelle Facture
                </button>
            </div>
        </div>

        <!-- Cartes de statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Factures</h6>
                                <h3 class="mb-0 text-primary">{{ $stats['total_factures'] ?? 0 }}</h3>
                            </div>
                            <i class="bi bi-receipt fs-1 text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-3">
                <div class="card border-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Montant Payé</h6>
                                <h3 class="mb-0 text-info">{{ number_format($stats['montant_paye'] ?? 0, 0, ',', ' ') }} CFA</h3>
                            </div>
                            <i class="bi bi-check2-all fs-1 text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Créances</h6>
                                <h3 class="mb-0 text-warning">{{ number_format($stats['montant_restant'] ?? 0, 0, ',', ' ') }} CFA</h3>
                            </div>
                            <i class="bi bi-exclamation-triangle fs-1 text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Entreprise</label>
                        <select class="form-select">
                            <option value="">Toutes les entreprises</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select class="form-select">
                            <option value="">Tous</option>
                            <option value="payee">Payée</option>
                            <option value="en_attente">En attente</option>
                            <option value="en_retard">En retard</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date début</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date fin</label>
                        <input type="date" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des factures -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Historique des Factures</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Entreprise</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Montant TTC</th>
                                <th>Payé</th>
                                <th>Restant</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($factures as $facture)
                            <tr>
                                <td><strong>{{ $facture->numero_facture }}</strong></td>
                                <td>{{ $facture->entreprise?->nom_entreprise ?? '-' }}</td>
                                <td>{{ $facture->client?->nomAffichage ?? '-' }}</td>
                                <td>{{ $facture->date_emission?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ number_format($facture->montant_ttc ?? 0, 0, ',', ' ') }} CFA</td>
                                <td>{{ number_format($facture->montant_paye ?? 0, 0, ',', ' ') }} CFA</td>
                                <td>{{ number_format($facture->montant_restant ?? 0, 0, ',', ' ') }} CFA</td>
                                <td>
                                    @if($facture->statut == 'payee')
                                    <span class="badge bg-success">Payée</span>
                                    @elseif($facture->statut == 'en_attente')
                                    <span class="badge bg-warning">En attente</span>
                                    @elseif($facture->statut == 'en_retard')
                                    <span class="badge bg-danger">En retard</span>
                                    @else
                                    <span class="badge bg-secondary">{{ $facture->statut ?? 'Inconnu' }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" title="Télécharger">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Aucune facture trouvée</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $factures->links() ?? '' }}
                </div>
            </div>
        </div>
    </div>
@endsection