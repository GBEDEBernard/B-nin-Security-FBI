@extends('layouts.app')

@section('title', 'Gestion des Modèles - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-file-earmark-ruled-fill text-info me-2"></i>
                    Modèles de Documents
                </h2>
                <p class="text-muted mb-0">Gérez les modèles de contrats, factures et autres documents</p>
            </div>
            <button class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Nouveau Modèle
            </button>
        </div>

        <!-- Catégories -->
        <div class="row mb-4">
            <div class="col-md-12">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="bi bi-folder me-1"></i> Tous les modèles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-file-text me-1"></i> Contrats
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-receipt me-1"></i> Factures
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-person-vcard me-1"></i> Bulletins de paie
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-file-check me-1"></i> Autres
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Cartes de modèles -->
        <div class="row mb-4">
            <!-- Modèle Contrat Prestations -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-file-text me-2"></i>Contrat de Prestation</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Modèle de contrat de prestation de services de sécurité</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-secondary">Système</span>
                            <span class="text-muted">Mis à jour: 15/02/2026</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i> Prévisualiser
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil me-1"></i> Modifier
                        </button>
                        <button class="btn btn-sm btn-outline-success">
                            <i class="bi bi-download me-1"></i> Télécharger
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modèle Facture -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-receipt me-2"></i>Facture Standard</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Modèle de facture avec-logo et coordonnées</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-secondary">Système</span>
                            <span class="text-muted">Mis à jour: 10/02/2026</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i> Prévisualiser
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil me-1"></i> Modifier
                        </button>
                        <button class="btn btn-sm btn-outline-success">
                            <i class="bi bi-download me-1"></i> Télécharger
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modèle Bulletin de Paie -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="bi bi-person-vcard me-2"></i>Bulletin de Paie</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Modèle de bulletin de paie mensuel</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-secondary">Système</span>
                            <span class="text-muted">Mis à jour: 01/02/2026</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i> Prévisualiser
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil me-1"></i> Modifier
                        </button>
                        <button class="btn btn-sm btn-outline-success">
                            <i class="bi bi-download me-1"></i> Télécharger
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deuxième ligne -->
        <div class="row mb-4">
            <!-- Modèle Convention -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="bi bi-file-earmark-check me-2"></i>Convention de Stage</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Modèle de convention de stage professionnel</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-secondary">Système</span>
                            <span class="text-muted">Mis à jour: 20/01/2026</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i> Prévisualiser
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil me-1"></i> Modifier
                        </button>
                        <button class="btn btn-sm btn-outline-success">
                            <i class="bi bi-download me-1"></i> Télécharger
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modèle Bon de Commande -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="bi bi-cart me-2"></i>Bon de Commande</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Modèle de bon de commande fournitures</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-secondary">Système</span>
                            <span class="text-muted">Mis à jour: 15/01/2026</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i> Prévisualiser
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil me-1"></i> Modifier
                        </button>
                        <button class="btn btn-sm btn-outline-success">
                            <i class="bi bi-download me-1"></i> Télécharger
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modèle Attestation -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0"><i class="bi bi-award me-2"></i>Attestation de Travail</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Modèle d'attestation de travail</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-secondary">Système</span>
                            <span class="text-muted">Mis à jour: 10/01/2026</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i> Prévisualiser
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil me-1"></i> Modifier
                        </button>
                        <button class="btn btn-sm btn-outline-success">
                            <i class="bi bi-download me-1"></i> Télécharger
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des modèles personnalisés -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-folder-plus me-2"></i>Modèles Personnalisés</h5>
                <button class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Ajouter
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Entreprise</th>
                                <th>Créé le</th>
                                <th>Modifié le</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-folder2-open fs-1 d-block mb-2"></i>
                                    Aucun modèle personnalisé pour le moment
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection