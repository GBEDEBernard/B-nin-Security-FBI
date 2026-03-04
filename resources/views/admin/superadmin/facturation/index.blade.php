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
            <button class="btn btn-outline-secondary me-2" onclick="window.location.href='{{ route('admin.superadmin.facturation.export') }}'">
                <i class="bi bi-download me-1"></i> Exporter
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#nouvelleFactureModal">
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
            <form method="GET" action="{{ route('admin.superadmin.facturation.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Entreprise</label>
                    <select name="entreprise_id" class="form-select">
                        <option value="">Toutes les entreprises</option>
                        @foreach($entreprises as $entreprise)
                        <option value="{{ $entreprise->id }}" {{ (isset($request) && $request->entreprise_id == $entreprise->id) ? 'selected' : '' }}>
                            {{ $entreprise->nom_entreprise }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous</option>
                        <option value="emise" {{ (isset($request) && $request->statut == 'emise') ? 'selected' : '' }}>Émise</option>
                        <option value="envoyee" {{ (isset($request) && $request->statut == 'envoyee') ? 'selected' : '' }}>Envoyée</option>
                        <option value="payee" {{ (isset($request) && $request->statut == 'payee') ? 'selected' : '' }}>Payée</option>
                        <option value="partiellement_payee" {{ (isset($request) && $request->statut == 'partiellement_payee') ? 'selected' : '' }}>Partiellement payée</option>
                        <option value="impayee" {{ (isset($request) && $request->statut == 'impayee') ? 'selected' : '' }}>Impayée</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date début</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ $request->date_debut ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="date_fin" class="form-control" value="{{ $request->date_fin ?? '' }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.superadmin.facturation.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Réinitialiser
                    </a>
                </div>
            </form>
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
                            <th>Montant HT</th>
                            <th>TVA</th>
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
                            <td>
                                @php
                                // Get client without global scope
                                $client = $facture->client;
                                @endphp
                                {{ $client?->nomAffichage ?? '-' }}
                            </td>
                            <td>{{ $facture->date_emission?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ number_format($facture->montant_ht ?? 0, 0, ',', ' ') }} CFA</td>
                            <td>
                                <span class="text-muted">{{ $facture->tva ?? 18 }}%</span>
                                <br>
                                <small>{{ number_format(($facture->montant_ttc ?? 0) - ($facture->montant_ht ?? 0), 0, ',', ' ') }} CFA</small>
                            </td>
                            <td><strong>{{ number_format($facture->montant_ttc ?? 0, 0, ',', ' ') }} CFA</strong></td>
                            <td class="text-success">{{ number_format($facture->montant_paye ?? 0, 0, ',', ' ') }} CFA</td>
                            <td class="{{ ($facture->montant_restant ?? 0) > 0 ? 'text-danger' : 'text-muted' }}">
                                {{ number_format($facture->montant_restant ?? 0, 0, ',', ' ') }} CFA
                            </td>
                            <td>
                                @if($facture->statut == 'payee')
                                <span class="badge bg-success">Payée</span>
                                @elseif($facture->statut == 'partiellement_payee')
                                <span class="badge bg-info">Partiel</span>
                                @elseif($facture->statut == 'emise' || $facture->statut == 'envoyee')
                                <span class="badge bg-warning">En attente</span>
                                @elseif($facture->statut == 'impayee' || $facture->statut == 'en_retard')
                                <span class="badge bg-danger">En retard</span>
                                @else
                                <span class="badge bg-secondary">{{ $facture->statut ?? 'Inconnu' }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.superadmin.facturation.show', $facture->id) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.superadmin.facturation.pdf', $facture->id) }}" class="btn btn-sm btn-outline-success" title="Télécharger PDF">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a href="{{ route('admin.superadmin.facturation.print', $facture->id) }}" class="btn btn-sm btn-outline-secondary" title="Imprimer" target="_blank">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted">Aucune facture trouvée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $factures->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Facture (à implémenter) -->
<div class="modal fade" id="nouvelleFactureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Facture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">La création de factures sera bientôt disponible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection