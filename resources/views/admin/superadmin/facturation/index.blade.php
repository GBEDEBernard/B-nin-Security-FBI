@extends('layouts.app')

@section('title', 'Gestion de la Facturation - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-receipt-cutoff text-success me-2"></i>
                    Facturation Globale
                </h2>
                <p class="text-muted mb-0">Vue consolidée de toutes les factures</p>
            </div>
            <div>
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#genererFacturesModal">
                    <i class="bi bi-gear me-1"></i> Générer les factures du mois
                </button>
                <a href="{{ route('admin.superadmin.facturation.export') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-download me-1"></i> Exporter
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Factures</h6>
                                <h3 class="mb-0 text-primary">{{ $stats['total_factures'] }}</h3>
                            </div>
                            <i class="bi bi-receipt fs-1 text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Montant Total</h6>
                                <h3 class="mb-0 text-success">{{ number_format($stats['montant_total'], 0, ',', ' ') }} CFA</h3>
                            </div>
                            <i class="bi bi-currency-dollar fs-1 text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-info h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Montant Payé</h6>
                                <h3 class="mb-0 text-info">{{ number_format($stats['montant_paye'], 0, ',', ' ') }} CFA</h3>
                            </div>
                            <i class="bi bi-check2-all fs-1 text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Créances</h6>
                                <h3 class="mb-0 text-warning">{{ number_format($stats['montant_restant'], 0, ',', ' ') }} CFA</h3>
                            </div>
                            <i class="bi bi-exclamation-triangle fs-1 text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.superadmin.facturation.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Entreprise</label>
                            <select name="entreprise_id" class="form-select">
                                <option value="">Toutes les entreprises</option>
                                @foreach($entreprises as $entreprise)
                                <option value="{{ $entreprise->id }}" {{ request('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                                    {{ $entreprise->nom_entreprise }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select">
                                <option value="">Tous</option>
                                <option value="emise" {{ request('statut') == 'emise' ? 'selected' : '' }}>Émise</option>
                                <option value="envoyee" {{ request('statut') == 'envoyee' ? 'selected' : '' }}>Envoyée</option>
                                <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>Payée</option>
                                <option value="partiellement_payee" {{ request('statut') == 'partiellement_payee' ? 'selected' : '' }}>Partiellement payée</option>
                                <option value="impayee" {{ request('statut') == 'impayee' ? 'selected' : '' }}>Impayée</option>
                                <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date début</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date fin</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-funnel me-1"></i> Filtrer
                            </button>
                            <a href="{{ route('admin.superadmin.facturation.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Historique des Factures</h5>
                <span class="badge bg-secondary">{{ $factures->total() }} facture(s)</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Entreprise</th>
                                <th>Abonnement</th>
                                <th>Date</th>
                                <th>Période</th>
                                <th>Montant TTC</th>
                                <th>Payé</th>
                                <th>Restant</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($factures as $facture)
                            <tr>
                                <td><strong>{{ $facture->numero_facture }}</strong></td>
                                <td>{{ $facture->entreprise?->nom_entreprise ?? '-' }}</td>
                                <td>
                                    @if($facture->abonnement)
                                    <span class="badge bg-info">{{ $facture->abonnement->formule_label }}</span>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{ $facture->date_emission?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $facture->mois ? str_pad($facture->mois, 2, '0', STR_PAD_LEFT) . '/' . $facture->annee : '-' }}</td>
                                <td class="fw-semibold">{{ number_format($facture->montant_ttc, 0, ',', ' ') }} CFA</td>
                                <td>{{ number_format($facture->montant_paye, 0, ',', ' ') }} CFA</td>
                                <td>{{ number_format($facture->montant_restant, 0, ',', ' ') }} CFA</td>
                                <td>
                                    @php
                                    $badges = [
                                        'emise' => 'secondary',
                                        'envoyee' => 'info',
                                        'payee' => 'success',
                                        'partiellement_payee' => 'warning',
                                        'impayee' => 'danger',
                                        'annulee' => 'dark',
                                    ];
                                    $labels = [
                                        'emise' => 'Émise',
                                        'envoyee' => 'Envoyée',
                                        'payee' => 'Payée',
                                        'partiellement_payee' => 'Partielle',
                                        'impayee' => 'Impayée',
                                        'annulee' => 'Annulée',
                                    ];
                                    @endphp
                                    <span class="badge bg-{{ $badges[$facture->statut] ?? 'secondary' }}">
                                        {{ $labels[$facture->statut] ?? $facture->statut }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.superadmin.facturation.show', $facture->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.superadmin.facturation.download', $facture->id) }}"
                                       class="btn btn-sm btn-outline-success" title="Télécharger PDF">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Aucune facture trouvée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($factures->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $factures->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Modal de confirmation --}}
    <div class="modal fade" id="genererFacturesModal" tabindex="-1" aria-labelledby="genererFacturesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="genererFacturesModalLabel">
                        <i class="bi bi-gear text-success me-2"></i>Générer les factures
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-receipt-cutoff" style="font-size: 3.5rem; color: var(--bs-success); opacity: 0.7;"></i>
                    </div>
                    <h6 class="fw-semibold mb-2">Générer les factures du mois</h6>
                    <p class="text-muted mb-0">
                        Cette action va générer les factures pour tous les abonnements actifs selon leur cycle de facturation (mensuel, trimestriel, semestriel, annuel).
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-success px-4" id="confirmGenererFactures">
                        <i class="bi bi-check-lg me-1"></i> Confirmer la génération
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de résultat --}}
    <div class="modal fade" id="resultatFacturesModal" tabindex="-1" aria-labelledby="resultatFacturesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="resultatFacturesModalLabel">
                        <i class="bi bi-check-circle text-success me-2"></i>Résultat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body text-center py-4" id="resultatFacturesBody">
                    <div class="mb-3">
                        <div id="resultatLoading" class="py-3">
                            <div class="spinner-border text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Génération en cours...</span>
                            </div>
                            <h6 class="fw-semibold">Génération en cours...</h6>
                            <p class="text-muted mb-0 small">Veuillez patienter pendant la génération des factures.</p>
                        </div>
                        <div id="resultatSuccess" class="py-3" style="display:none;">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3.5rem;"></i>
                            <h6 class="fw-semibold mt-3">Factures générées avec succès !</h6>
                            <p class="text-muted mb-0" id="resultatMessage"></p>
                        </div>
                        <div id="resultatError" class="py-3" style="display:none;">
                            <i class="bi bi-x-circle-fill text-danger" style="font-size: 3.5rem;"></i>
                            <h6 class="fw-semibold mt-3">Erreur lors de la génération</h6>
                            <p class="text-muted mb-0" id="resultatErrorMessage"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal" id="resultatFermerBtn">Fermer</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('confirmGenererFactures')?.addEventListener('click', function() {
    const confirmModal = bootstrap.Modal.getInstance(document.getElementById('genererFacturesModal'));
    confirmModal.hide();

    const resultModal = new bootstrap.Modal(document.getElementById('resultatFacturesModal'));
    resultModal.show();

    document.getElementById('resultatLoading').style.display = '';
    document.getElementById('resultatSuccess').style.display = 'none';
    document.getElementById('resultatError').style.display = 'none';

    fetch('{{ route("admin.superadmin.facturation.generer") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('resultatLoading').style.display = 'none';
        if (data.success) {
            document.getElementById('resultatSuccess').style.display = '';
            document.getElementById('resultatMessage').textContent = data.message;
        } else {
            document.getElementById('resultatError').style.display = '';
            document.getElementById('resultatErrorMessage').textContent = data.message;
        }
    })
    .catch(error => {
        document.getElementById('resultatLoading').style.display = 'none';
        document.getElementById('resultatError').style.display = '';
        document.getElementById('resultatErrorMessage').textContent = 'Une erreur est survenue lors de la génération des factures.';
    });
});

document.getElementById('resultatFermerBtn')?.addEventListener('click', function() {
    window.location.reload();
});
</script>
@endpush
