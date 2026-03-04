@extends('layouts.app')

@section('title', 'Facture - ' . $facture['numero'])

@push('styles')
<style>
    .invoice-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        background: white;
        max-width: 800px;
        margin: 0 auto;
    }

    .invoice-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        color: white;
        padding: 2rem;
        border-radius: 16px 16px 0 0;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-table th {
        background: #f8f9fa;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .invoice-table td {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .invoice-total {
        font-size: 1.5rem;
        font-weight: 700;
        color: #198754;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .invoice-card,
        .invoice-card * {
            visibility: visible;
        }

        .invoice-card {
            position: absolute;
            left: 0;
            top: 0;
            box-shadow: none;
        }

        .no-print {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-receipt me-2"></i>Facture</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.entreprises.index') }}">Entreprises</a></li>
                    <li class="breadcrumb-item active">Facture</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="invoice-card">
                    <div class="invoice-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="mb-1">BENIN SECURITY</h2>
                                <p class="mb-0">01 BP 1234 Cotonou, Benin</p>
                                <p class="mb-0">Tél: +229 21 30 00 00</p>
                                <p class="mb-0">Email: contact@benin-security.bj</p>
                            </div>
                            <div class="text-end">
                                <h4 class="mb-1">FACTURE</h4>
                                <p class="mb-0">N°: {{ $facture['numero'] }}</p>
                                <p class="mb-0">Date: {{ $facture['date'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Informations client -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Facturé à:</h6>
                                <h5>{{ $entreprise->nom_entreprise }}</h5>
                                <p class="mb-0">{{ $entreprise->adresse ?? 'Adresse non spécifiée' }}</p>
                                <p class="mb-0">{{ $entreprise->ville ?? '' }}, {{ $entreprise->pays ?? 'Bénin' }}</p>
                                <p class="mb-0">NIF: {{ $entreprise->numeroIdentificationFiscale ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h6 class="text-muted mb-2">Détails du paiement:</h6>
                                <p class="mb-0"><strong>Formule:</strong> {{ ucfirst($entreprise->formule) }}</p>
                                <p class="mb-0"><strong>Agents max:</strong> {{ $entreprise->nombre_agents_max }}</p>
                                <p class="mb-0"><strong>Sites max:</strong> {{ $entreprise->nombre_sites_max }}</p>
                                <p class="mb-0"><strong>Cycle:</strong> Mensuel</p>
                            </div>
                        </div>

                        <!-- Tableau des articles -->
                        <table class="invoice-table mb-4">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th class="text-end">Quantité</th>
                                    <th class="text-end">Prix unitaire</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Abonnement {{ ucfirst($entreprise->formule) }}</strong><br>
                                        <small class="text-muted">Accès à la plateforme Benin-Security - Gestion des agents et sites</small>
                                    </td>
                                    <td class="text-end">1</td>
                                    <td class="text-end">{{ number_format($entreprise->montant_mensuel, 0, ',', ' ') }} CFA</td>
                                    <td class="text-end">{{ number_format($entreprise->montant_mensuel, 0, ',', ' ') }} CFA</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Sous-total</strong></td>
                                    <td class="text-end">{{ number_format($entreprise->montant_mensuel, 0, ',', ' ') }} CFA</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end">TVA (0%)</td>
                                    <td class="text-end">0 CFA</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end invoice-total">{{ number_format($entreprise->montant_mensuel, 0, ',', ' ') }} CFA</td>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Notes -->
                        <div class="alert alert-light">
                            <h6><i class="bi bi-info-circle me-2"></i>Notes:</h6>
                            <p class="mb-0 small">
                                - Cette facture est relative à l'abonnement mensuel à la plateforme Benin-Security.<br>
                                - Le paiement a été effectué avec succès.<br>
                                - Pour toute question, contactez-nous à contact@benin-security.bj
                            </p>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between mt-4 no-print">
                            <a href="{{ route('admin.superadmin.entreprises.show', $entreprise->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Retour à l'entreprise
                            </a>
                            <div>
                                <button onclick="window.print()" class="btn btn-primary me-2">
                                    <i class="bi bi-printer me-1"></i> Imprimer
                                </button>
                                <a href="{{ route('admin.superadmin.index') }}" class="btn btn-success">
                                    <i class="bi bi-house me-1"></i> Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::App Content-->
@endsection