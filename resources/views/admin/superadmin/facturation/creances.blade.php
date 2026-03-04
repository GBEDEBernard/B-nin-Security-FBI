@extends('layouts.app')

@section('title', 'Créances - Facturation')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                Créances
            </h2>
            <p class="text-muted mb-0">Factures impayées ou en retard</p>
        </div>
        <a href="{{ route('admin.superadmin.facturation.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Retour aux factures
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Créances</h6>
                    <h3 class="mb-0 text-warning">{{ number_format($stats['total_creances'] ?? 0, 0, ',', ' ') }} CFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="card-title text-muted">Factures en Retard</h6>
                    <h3 class="mb-0 text-danger">{{ $stats['en_retard'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="card-title text-muted">Nombre de Factures</h6>
                    <h3 class="mb-0 text-primary">{{ $stats['nombre_factures'] ?? 0 }}</h3>
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
                            <th>N° Facture</th>
                            <th>Entreprise</th>
                            <th>Client</th>
                            <th>Date Échéance</th>
                            <th>Montant TTC</th>
                            <th>Montant Restant</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($creances as $creance)
                        <tr class="{{ $creance->date_echeance && $creance->date_echeance < now() ? 'table-danger' : '' }}">
                            <td>
                                <a href="{{ route('admin.superadmin.facturation.show', $creance->id) }}">
                                    {{ $creance->numero_facture }}
                                </a>
                            </td>
                            <td>{{ $creance->entreprise?->nom_entreprise ?? '-' }}</td>
                            <td>{{ $creance->client?->nomAffichage ?? '-' }}</td>
                            <td>{{ $creance->date_echeance?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ number_format($creance->montant_ttc ?? 0, 0, ',', ' ') }} CFA</td>
                            <td class="text-danger">{{ number_format($creance->montant_restant ?? 0, 0, ',', ' ') }} CFA</td>
                            <td>
                                @if($creance->date_echeance && $creance->date_echeance < now())
                                    <span class="badge bg-danger">En retard</span>
                                    @else
                                    <span class="badge bg-warning">En attente</span>
                                    @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Aucune créance trouvée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $creances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection