@extends('layouts.app')

@section('title', 'Facture ' . $facture->numero_facture . ' - Super Admin')

@push('styles')
<style>
    .facture-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
    }
    .facture-header {
        background: linear-gradient(135deg, #19875422, #19875411);
        border-bottom: 2px solid #19875433;
    }
    .info-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-value {
        font-size: 1rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-receipt text-success me-2"></i>
                Facture {{ $facture->numero_facture }}
            </h2>
            <p class="text-muted mb-0">Détails de la facture</p>
        </div>
        <div>
            <a href="{{ route('admin.superadmin.facturation.download', $facture->id) }}"
               class="btn btn-success me-2">
                <i class="bi bi-download me-1"></i> Télécharger PDF
            </a>
            <a href="{{ route('admin.superadmin.facturation.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card facture-card">
                <div class="card-header facture-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="bi bi-receipt me-2"></i>
                                {{ $facture->numero_facture }}
                            </h5>
                            <small class="text-muted">Émise le {{ $facture->date_emission?->format('d/m/Y') }}</small>
                        </div>
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
                        <span class="badge bg-{{ $badges[$facture->statut] ?? 'secondary' }} fs-6">
                            {{ $labels[$facture->statut] ?? $facture->statut }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-success mb-3"><i class="bi bi-building me-1"></i> Bénin Security Services</h6>
                            <p class="mb-1">Contact: contact@benin-security.bj</p>
                            <p class="mb-1">Tél: +229 21 30 00 01</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-primary mb-3"><i class="bi bi-shop me-1"></i> Client</h6>
                            @if($facture->entreprise)
                            <p class="mb-1 fw-semibold">{{ $facture->entreprise->nom_entreprise }}</p>
                            <p class="mb-1">{{ $facture->entreprise->email }}</p>
                            <p class="mb-1">{{ $facture->entreprise->telephone }}</p>
                            @if($facture->entreprise->adresse)
                            <p class="mb-1">{{ $facture->entreprise->adresse }}, {{ $facture->entreprise->ville }}</p>
                            @endif
                            @else
                            <p class="text-muted">Non spécifiée</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3"><i class="bi bi-list-check me-1"></i> Détail de la prestation</h6>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th>Période</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    @if($facture->abonnement)
                                    Abonnement {{ $facture->abonnement->formule_label }}
                                    @else
                                    Prestation de sécurité
                                    @endif
                                </td>
                                <td>{{ str_pad($facture->mois, 2, '0', STR_PAD_LEFT) . '/' . $facture->annee }}</td>
                                <td class="text-end fw-semibold">{{ number_format($facture->montant_ht, 0, ',', ' ') }} CFA</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">Montant HT</th>
                                <th class="text-end">{{ number_format($facture->montant_ht, 0, ',', ' ') }} CFA</th>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end">TVA ({{ $facture->tva }}%)</td>
                                <td class="text-end">{{ number_format($facture->montant_ht * $facture->tva / 100, 0, ',', ' ') }} CFA</td>
                            </tr>
                            <tr class="table-success">
                                <th colspan="2" class="text-end">TOTAL TTC</th>
                                <th class="text-end fs-5">{{ number_format($facture->montant_ttc, 0, ',', ' ') }} CFA</th>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p class="mb-1"><span class="text-muted">Payé :</span> <strong>{{ number_format($facture->montant_paye, 0, ',', ' ') }} CFA</strong></p>
                            <p class="mb-1"><span class="text-muted">Restant :</span> <strong class="text-{{ $facture->montant_restant > 0 ? 'danger' : 'success' }}">{{ number_format($facture->montant_restant, 0, ',', ' ') }} CFA</strong></p>
                            <p class="mb-1"><span class="text-muted">Échéance :</span> {{ $facture->date_echeance?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                    </div>

                    @if($facture->notes)
                    <hr>
                    <h6 class="mb-2">Notes</h6>
                    <p class="text-muted mb-0">{{ $facture->notes }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card facture-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i> Paiements</h5>
                </div>
                <div class="card-body">
                    @forelse($facture->paiements as $paiement)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <div>
                            <p class="mb-0 fw-semibold">{{ number_format($paiement->montant, 0, ',', ' ') }} CFA</p>
                            <small class="text-muted">{{ $paiement->date_paiement?->format('d/m/Y') }}</small>
                        </div>
                        <span class="badge bg-info">{{ $paiement->mode_paiement }}</span>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-credit-card-2-back fs-2 d-block mb-2"></i>
                        <p class="mb-0">Aucun paiement enregistré</p>
                    </div>
                    @endforelse

                    @if($facture->montant_restant > 0)
                    <hr>
                    <div class="d-grid">
                        <button class="btn btn-success" disabled>
                            <i class="bi bi-check2-circle me-1"></i> Enregistrer un paiement
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card facture-card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> Informations</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="info-label">Numéro</span>
                        <p class="info-value mb-0">{{ $facture->numero_facture }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Date d'émission</span>
                        <p class="info-value mb-0">{{ $facture->date_emission?->format('d/m/Y') ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Échéance</span>
                        <p class="info-value mb-0">{{ $facture->date_echeance?->format('d/m/Y') ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="info-label">Créé par</span>
                        <p class="info-value mb-0">{{ $facture->cree_par ?? 'Système' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
