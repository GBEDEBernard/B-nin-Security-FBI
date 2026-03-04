@extends('layouts.app')

@section('title', 'Détails de la Facture - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-receipt text-success me-2"></i>
                Facture {{ $facture->numero_facture }}
            </h2>
            <p class="text-muted mb-0">Détails de la facture</p>
        </div>
        <div>
            <a href="{{ route('admin.superadmin.facturation.pdf', $facture->id) }}" class="btn btn-success">
                <i class="bi bi-download me-1"></i> Télécharger PDF
            </a>
            <a href="{{ route('admin.superadmin.facturation.print', $facture->id) }}" class="btn btn-outline-secondary" target="_blank">
                <i class="bi bi-printer me-1"></i> Imprimer
            </a>
            <a href="{{ route('admin.superadmin.facturation.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informations de la Facture</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>N° Facture:</strong> {{ $facture->numero_facture }}</p>
                            <p><strong>Référence:</strong> {{ $facture->reference ?? '-' }}</p>
                            <p><strong>Période:</strong> {{ $facture->mois ? sprintf('%02d/%d', $facture->mois, $facture->annee) : '-' }}</p>
                            <p><strong>Date d'émission:</strong> {{ $facture->date_emission?->format('d/m/Y') ?? '-' }}</p>
                            <p><strong>Date d'échéance:</strong> {{ $facture->date_echeance?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Statut:</strong>
                                @if($facture->statut == 'payee')
                                <span class="badge bg-success">Payée</span>
                                @elseif($facture->statut == 'partiellement_payee')
                                <span class="badge bg-info">Partiellement payée</span>
                                @elseif($facture->statut == 'emise' || $facture->statut == 'envoyee')
                                <span class="badge bg-warning">En attente</span>
                                @elseif($facture->statut == 'impayee')
                                <span class="badge bg-danger">Impayée</span>
                                @else
                                <span class="badge bg-secondary">{{ $facture->statut }}</span>
                                @endif
                            </p>
                            <p><strong>Date de paiement:</strong> {{ $facture->date_paiement?->format('d/m/Y') ?? '-' }}</p>
                            <p><strong>Entreprise:</strong> {{ $facture->entreprise?->nom_entreprise ?? '-' }}</p>
                            <p><strong>Contrat:</strong> {{ $facture->contrat?->numero_contrat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Client -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Client</h5>
                </div>
                <div class="card-body">
                    @if($facture->client)
                    <p><strong>Nom:</strong> {{ $facture->client->nomAffichage }}</p>
                    @if($facture->client->type_client == 'entreprise' && $facture->client->raison_sociale)
                    <p><strong>Raison sociale:</strong> {{ $facture->client->raison_sociale }}</p>
                    @endif
                    <p><strong>Email:</strong> {{ $facture->client->email ?? '-' }}</p>
                    <p><strong>Téléphone:</strong> {{ $facture->client->telephone ?? '-' }}</p>
                    <p><strong>Adresse:</strong> {{ $facture->client->adresse ?? '-' }}, {{ $facture->client->ville ?? '' }}</p>
                    @else
                    <p class="text-muted">Informations client non disponibles</p>
                    @endif
                </div>
            </div>

            <!-- Détails de la prestation -->
            @if($facture->detail_prestation)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-task me-2"></i>Détail de la Prestation</h5>
                </div>
                <div class="card-body">
                    @php
                    $details = is_string($facture->detail_prestation) ? json_decode($facture->detail_prestation, true) : $facture->detail_prestation;
                    @endphp
                    @if($details && is_array($details))
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Agents</th>
                                <th>Heures</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details as $type => $detail)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                                <td>{{ $detail['agents'] ?? '-' }}</td>
                                <td>{{ $detail['heures'] ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($facture->notes)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-sticky me-2"></i>Notes</h5>
                </div>
                <div class="card-body">
                    <p>{{ $facture->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Montants -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-currency-dollar me-2"></i>Montants</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Montant HT:</span>
                        <strong>{{ number_format($facture->montant_ht ?? 0, 0, ',', ' ') }} CFA</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>TVA ({{ $facture->tva ?? 18 }}%):</span>
                        <span>{{ number_format(($facture->montant_ttc ?? 0) - ($facture->montant_ht ?? 0), 0, ',', ' ') }} CFA</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Montant TTC:</span>
                        <strong class="text-success">{{ number_format($facture->montant_ttc ?? 0, 0, ',', ' ') }} CFA</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Montant Payé:</span>
                        <span class="text-success">{{ number_format($facture->montant_paye ?? 0, 0, ',', ' ') }} CFA</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Montant Restant:</span>
                        <span class="{{ ($facture->montant_restant ?? 0) > 0 ? 'text-danger' : '' }}">
                            {{ number_format($facture->montant_restant ?? 0, 0, ',', ' ') }} CFA
                        </span>
                    </div>
                </div>
            </div>

            <!-- Paiements -->
            @if($facture->paiements && $facture->paiements->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Paiements</h5>
                </div>
                <div class="card-body">
                    @foreach($facture->paiements as $paiement)
                    <div class="border-bottom pb-2 mb-2">
                        <p class="mb-1"><strong>{{ number_format($paiement->montant, 0, ',', ' ') }} CFA</strong></p>
                        <p class="mb-0 text-muted small">
                            {{ $paiement->date_paiement ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') : '-' }}
                            @if($paiement->mode_paiement)
                            - {{ $paiement->mode_paiement }}
                            @endif
                        </p>
                        @if($paiement->reference)
                        <p class="mb-0 text-muted small">Réf: {{ $paiement->reference }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection