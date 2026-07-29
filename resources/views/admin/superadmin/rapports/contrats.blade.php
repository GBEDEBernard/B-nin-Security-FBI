@extends('layouts.app')

@section('title', 'Rapport Contrats - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-file-earmark-text text-warning me-2"></i>
                    Rapport Contrats
                </h2>
                <p class="text-muted mb-0">Statistiques consolidées des contrats</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-file-text fs-1 text-primary mb-2"></i>
                        <h4 class="mb-1">0</h4>
                        <p class="text-muted mb-0">Total Contrats</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-check-circle fs-1 text-success mb-2"></i>
                        <h4 class="mb-1">0</h4>
                        <p class="text-muted mb-0">Actifs</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-hourglass-split fs-1 text-warning mb-2"></i>
                        <h4 class="mb-1">0</h4>
                        <p class="text-muted mb-0">En Attente</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-x-circle fs-1 text-danger mb-2"></i>
                        <h4 class="mb-1">0</h4>
                        <p class="text-muted mb-0">Expirés</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Répartition par Entreprise</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Entreprise</th>
                                <th>Total</th>
                                <th>Actifs</th>
                                <th>En Attente</th>
                                <th>Expirés</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Données à afficher</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
