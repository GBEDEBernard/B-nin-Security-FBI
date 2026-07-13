@extends('layouts.app')

@section('title', $entreprise->nom_entreprise . ' - Rapport')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold"><i class="bi bi-building me-2 text-primary"></i>{{ $entreprise->nom_entreprise }}</h2>
            <p class="text-muted mb-0">Rapport détaillé de l'entreprise</p>
        </div>
        <a href="{{ route('admin.superadmin.rapports.par-entreprise') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary fs-1 mb-2"><i class="bi bi-people"></i></div>
                    <h3 class="fw-bold mb-0">{{ $entreprise->employes->count() }}</h3>
                    <small class="text-muted">Employés</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info fs-1 mb-2"><i class="bi bi-person-badge"></i></div>
                    <h3 class="fw-bold mb-0">{{ $entreprise->clients->count() }}</h3>
                    <small class="text-muted">Clients</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success fs-1 mb-2"><i class="bi bi-file-earmark-text"></i></div>
                    <h3 class="fw-bold mb-0">{{ $entreprise->contratsPrestation->count() }}</h3>
                    <small class="text-muted">Contrats</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning fs-1 mb-2"><i class="bi bi-receipt"></i></div>
                    <h3 class="fw-bold mb-0">{{ $entreprise->factures->count() }}</h3>
                    <small class="text-muted">Factures</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-receipt me-2 text-warning"></i>Factures</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>N°</th>
                            <th>Période</th>
                            <th>Montant TTC</th>
                            <th>Payé</th>
                            <th>Restant</th>
                            <th>Échéance</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entreprise->factures as $f)
                        <tr>
                            <td class="fw-semibold">{{ $f->numero_facture }}</td>
                            <td>{{ str_pad($f->mois, 2, '0', STR_PAD_LEFT) . '/' . $f->annee }}</td>
                            <td>{{ number_format($f->montant_ttc, 0, ',', ' ') }} CFA</td>
                            <td class="text-success">{{ number_format($f->montant_paye, 0, ',', ' ') }} CFA</td>
                            <td class="text-danger">{{ number_format($f->montant_restant, 0, ',', ' ') }} CFA</td>
                            <td>{{ $f->date_echeance?->format('d/m/Y') ?? '-' }}</td>
                            <td><span class="badge bg-{{ ['emise'=>'secondary','envoyee'=>'info','payee'=>'success','partiellement_payee'=>'warning','impayee'=>'danger','annulee'=>'dark'][$f->statut] ?? 'secondary' }}">{{ $f->statut_label }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Aucune facture</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection