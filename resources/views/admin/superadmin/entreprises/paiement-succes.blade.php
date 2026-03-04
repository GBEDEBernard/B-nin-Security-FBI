@extends('layouts.app')

@section('title', 'Paiement réussi')

@push('styles')
<style>
    .success-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .success-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        color: white;
        padding: 3rem;
        text-align: center;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .success-icon i {
        font-size: 2.5rem;
        color: #198754;
    }

    .details-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .detail-row:last-child {
        border-bottom: none;
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-check-circle me-2"></i>Paiement réussi</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.entreprises.index') }}">Entreprises</a></li>
                    <li class="breadcrumb-item active">Paiement réussi</li>
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
                <div class="success-card">
                    <div class="success-header">
                        <div class="success-icon">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <h2 class="mb-2">Paiement réussi !</h2>
                        <p class="mb-0">Votre abonnement a été activé avec succès</p>
                    </div>

                    <div class="card-body p-4">
                        <div class="details-card">
                            <h5 class="mb-3"><i class="bi bi-building me-2"></i>Détails de l'entreprise</h5>
                            <div class="detail-row">
                                <span class="text-muted">Entreprise</span>
                                <span><strong>{{ $entreprise->nom_entreprise }}</strong></span>
                            </div>
                            <div class="detail-row">
                                <span class="text-muted">Formule</span>
                                <span><strong>{{ ucfirst($entreprise->formule) }}</strong></span>
                            </div>
                            <div class="detail-row">
                                <span class="text-muted">Nombre d'agents</span>
                                <span>{{ $entreprise->nombre_agents_max }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="text-muted">Nombre de sites</span>
                                <span>{{ $entreprise->nombre_sites_max }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="text-muted">Date de début</span>
                                <span>{{ $entreprise->date_debut_contrat ? $entreprise->date_debut_contrat->format('d/m/Y') : now()->format('d/m/Y') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="text-muted">Date d'échéance</span>
                                <span>{{ $entreprise->date_fin_contrat ? $entreprise->date_fin_contrat->format('d/m/Y') : now()->addMonth()->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <div class="alert alert-success">
                            <i class="bi bi-info-circle me-2"></i>
                            Un reçu de paiement a été envoyé à votre adresse email. Vous pouvez également le télécharger depuis votre dashboard.
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.superadmin.entreprises.show', $entreprise->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Voir l'entreprise
                            </a>
                            <a href="{{ route('admin.superadmin.index') }}" class="btn btn-success">
                                <i class="bi bi-house me-1"></i> Retour au dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::App Content-->
@endsection