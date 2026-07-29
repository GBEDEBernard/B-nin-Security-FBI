@extends('layouts.app')

@section('title', 'Rapport Financier - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-currency-dollar text-success me-2"></i>
                    Rapport Financier
                </h2>
                <p class="text-muted mb-0">Analyse financière consolidée</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-arrow-up-circle fs-1 text-success mb-2"></i>
                        <h4 class="mb-1">{{ number_format(0, 0, ',', ' ') }} CFA</h4>
                        <p class="text-muted mb-0">Revenus Totaux</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-arrow-down-circle fs-1 text-danger mb-2"></i>
                        <h4 class="mb-1">{{ number_format(0, 0, ',', ' ') }} CFA</h4>
                        <p class="text-muted mb-0">Dépenses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100 border-success">
                    <div class="card-body">
                        <i class="bi bi-piggy-bank fs-1 text-success mb-2"></i>
                        <h4 class="mb-1 text-success">{{ number_format(0, 0, ',', ' ') }} CFA</h4>
                        <p class="text-muted mb-0">Bénéfice Net</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-receipt fs-1 text-info mb-2"></i>
                        <h4 class="mb-1">0</h4>
                        <p class="text-muted mb-0">Factures Impayées</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Évolution Financière</h5>
            </div>
            <div class="card-body">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-graph-up fs-1"></i>
                    <p class="mt-2">Graphiques financiers à intégrer</p>
                </div>
            </div>
        </div>
    </div>
@endsection
