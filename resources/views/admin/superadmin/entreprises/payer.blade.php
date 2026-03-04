@extends('layouts.app')

@section('title', 'Paiement abonnement - ' . $entreprise->nom_entreprise)

@push('styles')
<style>
    .payment-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .payment-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        color: white;
        padding: 2rem;
    }

    .payment-methods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin: 1.5rem 0;
    }

    .payment-method {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-method:hover {
        border-color: #198754;
        background: #f8f9fa;
    }

    .payment-method.selected {
        border-color: #198754;
        background: rgba(25, 135, 84, 0.05);
    }

    .payment-method i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .fedapay-btn {
        background: #00b4d8;
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s ease;
    }

    .fedapay-btn:hover {
        background: #0096b4;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 180, 216, 0.3);
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .summary-item:last-child {
        border-bottom: none;
        font-weight: 700;
        font-size: 1.2rem;
        color: #198754;
    }

    .test-mode-badge {
        background: #ffc107;
        color: #000;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-credit-card me-2"></i>Paiement de l'abonnement</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.entreprises.index') }}">Entreprises</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.entreprises.show', $entreprise->id) }}">{{ $entreprise->nom_entreprise }}</a></li>
                    <li class="breadcrumb-item active">Paiement</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="payment-card">
                    <div class="payment-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">Paiement de l'abonnement</h4>
                                <p class="mb-0">{{ $entreprise->nom_entreprise }} - Formule {{ ucfirst($entreprise->formule) }}</p>
                            </div>
                            <div class="test-mode-badge">
                                <i class="bi bi-exclamation-triangle me-1"></i> Mode Test
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Messages d'alerte -->
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> Ce paiement est en mode test. Utilisez les coordonnées de carte suivantes:
                            <ul class="mb-0 mt-2">
                                <li>Numéro de carte: <code>4242424242424242</code></li>
                                <li>Date d'expiration: <code>12/28</code></li>
                                <li>CVV: <code>123</code></li>
                            </ul>
                        </div>

                        <!-- Récapitulatif -->
                        <h5 class="mb-3"><i class="bi bi-receipt me-2"></i>Récapitulatif</h5>
                        <div class="summary-item">
                            <span>Entreprise</span>
                            <span>{{ $entreprise->nom_entreprise }}</span>
                        </div>
                        <div class="summary-item">
                            <span>Formule</span>
                            <span>{{ ucfirst($entreprise->formule) }}</span>
                        </div>
                        <div class="summary-item">
                            <span>Nombre d'agents</span>
                            <span>{{ $entreprise->nombre_agents_max }}</span>
                        </div>
                        <div class="summary-item">
                            <span>Nombre de sites</span>
                            <span>{{ $entreprise->nombre_sites_max }}</span>
                        </div>
                        <div class="summary-item">
                            <span>Cycle de facturation</span>
                            <span>Mensuel</span>
                        </div>
                        <div class="summary-item">
                            <span>Total à payer</span>
                            <span>{{ number_format($montant, 0, ',', ' ') }} CFA</span>
                        </div>

                        <!-- Méthodes de paiement -->
                        <h5 class="mt-4 mb-3"><i class="bi bi-wallet2 me-2"></i>Méthode de paiement</h5>
                        <div class="payment-methods">
                            <div class="payment-method selected" onclick="selectMethod('card')" id="method-card">
                                <i class="bi bi-credit-card text-primary"></i>
                                <span>Carte bancaire</span>
                            </div>
                            <div class="payment-method" onclick="selectMethod('mobile')" id="method-mobile">
                                <i class="bi bi-phone text-success"></i>
                                <span>Mobile Money</span>
                            </div>
                            <div class="payment-method" onclick="selectMethod('bank')" id="method-bank">
                                <i class="bi bi-bank text-info"></i>
                                <span>Virement</span>
                            </div>
                        </div>

                        <!-- Formulaire de paiement -->
                        <form action="{{ route('admin.superadmin.entreprises.initier-paiement', $entreprise->id) }}" method="POST" id="paymentForm">
                            @csrf

                            <input type="hidden" name="montant" value="{{ $montant }}">
                            <input type="hidden" name="mode_paiement" value="card" id="mode_paiement">
                            <input type="hidden" name="description" value="Abonnement {{ ucfirst($entreprise->formule) }} - {{ $entreprise->nom_entreprise }}">

                            <div class="mt-4">
                                <button type="submit" class="fedapay-btn">
                                    <i class="bi bi-lock me-2"></i>
                                    Payer {{ number_format($montant, 0, ',', ' ') }} CFA
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <p class="text-muted small">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Paiement sécurisé par FEDAPAY
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Bouton retour -->
                <div class="text-center mt-3">
                    <a href="{{ route('admin.superadmin.entreprises.abonnement', $entreprise->id) }}" class="text-muted">
                        <i class="bi bi-arrow-left me-1"></i> Changer d'abonnement
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::App Content-->

@push('scripts')
<script>
    function selectMethod(method) {
        // Retirer la sélection précédente
        document.querySelectorAll('.payment-method').forEach(function(el) {
            el.classList.remove('selected');
        });

        // Ajouter la sélection
        document.getElementById('method-' + method).classList.add('selected');

        // Mettre à jour l'input caché
        document.getElementById('mode_paiement').value = method;
    }
</script>
@endpush
@endsection