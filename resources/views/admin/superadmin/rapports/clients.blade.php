@extends('layouts.app')

@section('title', 'Rapport Clients - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-people-fill text-success me-2"></i>
                    Rapport Clients
                </h2>
                <p class="text-muted mb-0">Statistiques consolidées des clients</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-person fs-1 text-primary mb-2"></i>
                        <h4 class="mb-1">0</h4>
                        <p class="text-muted mb-0">Total Clients</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-file-text fs-1 text-info mb-2"></i>
                        <h4 class="mb-1">0</h4>
                        <p class="text-muted mb-0">Avec Contrat Actif</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-currency-dollar fs-1 text-success mb-2"></i>
                        <h4 class="mb-1">{{ number_format(0, 0, ',', ' ') }} CFA</h4>
                        <p class="text-muted mb-0">CA Généré</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Liste des Clients par Entreprise</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Entreprise</th>
                                <th>Total Clients</th>
                                <th>Actifs</th>
                                <th>Nouveaux (30j)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Données à afficher</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
