@extends('layouts.app')

@section('title', 'Gestion des Applications Mobiles - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-phone-fill text-dark me-2"></i>
                    Gestion de l'Application Mobile
                </h2>
                <p class="text-muted mb-0">Gérez les versions et configurations de l'application APK</p>
            </div>
            <button class="btn btn-primary">
                <i class="bi bi-upload me-1"></i> Uploader une nouvelle version
            </button>
        </div>

        <!-- Statut de l'APK actuelle -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Version Actuelle</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p class="mb-1 text-muted">Version</p>
                                <h4>1.0.0</h4>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted">Date de publication</p>
                                <h4>20/02/2026</h4>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted">Téléchargements</p>
                                <h4>145</h4>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted">Statut</p>
                                <span class="badge bg-success">Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-download fs-1 text-primary mb-3"></i>
                        <h5>Télécharger APK</h5>
                        <p class="text-muted">Télécharger la dernière version de l'application</p>
                        <button class="btn btn-outline-primary">
                            <i class="bi bi-download me-1"></i> Télécharger
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-qr-code fs-1 text-success mb-3"></i>
                        <h5>QR Code</h5>
                        <p class="text-muted">Générer un QR code pour le téléchargement</p>
                        <button class="btn btn-outline-success">
                            <i class="bi bi-qr-code me-1"></i> Générer
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-gear fs-1 text-warning mb-3"></i>
                        <h5>Configurations</h5>
                        <p class="text-muted">Configurer les paramètres de l'application</p>
                        <button class="btn btn-outline-warning">
                            <i class="bi bi-gear me-1"></i> Configurer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des versions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Historique des Versions</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Version</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Téléchargements</th>
                                <th>Statut</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>1.0.0</strong></td>
                                <td>20/02/2026</td>
                                <td><span class="badge bg-success">Stable</span></td>
                                <td>145</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>Version initiale</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Désactiver">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>0.9.0</strong></td>
                                <td>15/02/2026</td>
                                <td><span class="badge bg-warning text-dark">Beta</span></td>
                                <td>32</td>
                                <td><span class="badge bg-secondary">Inactive</span></td>
                                <td>Version beta</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" title="Activer">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection