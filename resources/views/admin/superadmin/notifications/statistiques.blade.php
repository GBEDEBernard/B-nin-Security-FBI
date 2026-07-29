@extends('layouts.app')

@section('title', 'Statistiques Notifications - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-graph-up text-info me-2"></i>
                    Statistiques des Notifications
                </h2>
                <p class="text-muted mb-0">Indicateurs de performance des notifications push</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-send fs-1 text-primary mb-2"></i>
                        <h4 class="mb-1">0</h4>
                        <p class="text-muted mb-0">Total Envoyées</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-eye fs-1 text-success mb-2"></i>
                        <h4 class="mb-1">0%</h4>
                        <p class="text-muted mb-0">Taux de Lecture</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-calendar fs-1 text-warning mb-2"></i>
                        <h4 class="mb-1">0</h4>
                        <p class="text-muted mb-0">Ce Mois</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Évolution des Envois</h5>
            </div>
            <div class="card-body">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bar-chart-line fs-1"></i>
                    <p class="mt-2">Graphique à intégrer</p>
                </div>
            </div>
        </div>
    </div>
@endsection
