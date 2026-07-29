@extends('layouts.app')

@section('title', 'Rapport Employés - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-people text-info me-2"></i>
                    Rapport Employés
                </h2>
                <p class="text-muted mb-0">Statistiques consolidées des employés</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-person-badge fs-1 text-primary mb-2"></i>
                        <h4 class="mb-1">0</h4>
                        <p class="text-muted mb-0">Total Employés</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-person-check fs-1 text-success mb-2"></i>
                        <h4 class="mb-1">0</h4>
                        <p class="text-muted mb-0">En Poste</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-person-up fs-1 text-warning mb-2"></i>
                        <h4 class="mb-1">0</h4>
                        <p class="text-muted mb-0">En Congé</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-person-x fs-1 text-danger mb-2"></i>
                        <h4 class="mb-1">0</h4>
                        <p class="text-muted mb-0">Inactifs</p>
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
                                <th>En Poste</th>
                                <th>En Congé</th>
                                <th>Inactifs</th>
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
