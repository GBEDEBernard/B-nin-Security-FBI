@extends('layouts.app')

@section('title', 'Rapport par Entreprise - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-building text-primary me-2"></i>
                    Rapport par Entreprise
                </h2>
                <p class="text-muted mb-0">Statistiques détaillées par entreprise</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>Sélectionner une entreprise</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <select class="form-select">
                            <option value="">Toutes les entreprises</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" placeholder="Date début">
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" placeholder="Date fin">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Filtrer</button>
                    </div>
                </div>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bar-chart fs-1"></i>
                    <p class="mt-2">Sélectionnez une entreprise pour afficher le rapport</p>
                </div>
            </div>
        </div>
    </div>
@endsection
