@extends('layouts.app')

@section('title', 'Facture ' . $facture->numero_facture)

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
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-receipt me-2"></i>Facture {{ $facture->numero_facture }}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.entreprise.factures.index') }}">Factures</a></li>
                    <li class="breadcrumb-item active">{{ $facture->numero_facture }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card facture-card">
                    <div class="card-header facture-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">{{ $facture->numero_facture }}</h5>
                                <small class="text-muted">Émise le {{ $facture->date_emission?->format('d/m/Y') }}</small>
                            </div>
                            @php
                            $badges = ['emise'=>'secondary','envoyee'=>'info','payee'=>'success','partiellement_payee'=>'warning','impayee'=>'danger','annulee'=>'dark'];
                            $labels = ['emise'=>'Émise','envoyee'=>'Envoyée','payee'=>'Payée','partiellement_payee'=>'Partielle','impayee'=>'Impayée','annulee'=>'Annulée'];
                            @endphp
                            <span class="badge bg-{{ $badges[$facture->statut] ?? 'secondary' }} fs-6">
                                {{ $labels[$facture->statut] ?? $facture->statut }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-success mb-3">Bénin Security Services</h6>
                                <p class="mb-1">contact@benin-security.bj</p>
                                <p class="mb-1">+229 21 30 00 01</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h6 class="text-primary mb-3">{{ $facture->entreprise?->nom_entreprise ?? 'Client' }}</h6>
                                @if($facture->entreprise)
                                <p class="mb-1">{{ $facture->entreprise->email }}</p>
                                <p class="mb-1">{{ $facture->entreprise->telephone }}</p>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Détail de la prestation</h6>
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

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p class="mb-1"><span class="text-muted">Payé :</span> <strong>{{ number_format($facture->montant_paye, 0, ',', ' ') }} CFA</strong></p>
                                <p class="mb-1"><span class="text-muted">Restant :</span>
                                    <strong class="text-{{ $facture->montant_restant > 0 ? 'danger' : 'success' }}">
                                        {{ number_format($facture->montant_restant, 0, ',', ' ') }} CFA
                                    </strong>
                                </p>
                                <p class="mb-1"><span class="text-muted">Échéance :</span> {{ $facture->date_echeance?->format('d/m/Y') ?? '-' }}</p>
                            </div>
                        </div>
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
                    </div>
                </div>

                <div class="card facture-card mt-4">
                    <div class="card-body text-center">
                        <a href="{{ route('admin.entreprise.factures.download', $facture->id) }}"
                           class="btn btn-success w-100 mb-2">
                            <i class="bi bi-download me-1"></i> Télécharger PDF
                        </a>
                        @if($facture->montant_restant > 0)
                        <a href="{{ route('admin.entreprise.factures.payer', $facture->id) }}"
                           class="btn btn-primary w-100">
                            <i class="bi bi-credit-card me-1"></i> Payer la facture
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
