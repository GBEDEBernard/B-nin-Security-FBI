@extends('layouts.app')

@section('title', 'Gestion du Journal d\'Activité - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-clock-history text-secondary me-2"></i>
                    Journal d'Activité
                </h2>
                <p class="text-muted mb-0">Historique des actions et événements du système</p>
            </div>
            <div>
                <button class="btn btn-outline-secondary me-2">
                    <i class="bi bi-download me-1"></i> Exporter
                </button>
                <button class="btn btn-outline-primary">
                    <i class="bi bi-funnel me-1"></i> Filtrer
                </button>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Date début</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date fin</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Utilisateur</label>
                        <select class="form-select">
                            <option value="">Tous</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Action</label>
                        <select class="form-select">
                            <option value="">Toutes</option>
                            <option value="login">Connexion</option>
                            <option value="create">Création</option>
                            <option value="update">Modification</option>
                            <option value="delete">Suppression</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Module</label>
                        <select class="form-select">
                            <option value="">Tous</option>
                            <option value="entreprise">Entreprises</option>
                            <option value="utilisateur">Utilisateurs</option>
                            <option value="facture">Factures</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i> Rechercher
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-person-check fs-1 text-success mb-2"></i>
                        <h4>45</h4>
                        <p class="text-muted mb-0">Connexions aujourd'hui</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-plus-circle fs-1 text-primary mb-2"></i>
                        <h4>12</h4>
                        <p class="text-muted mb-0">Créations aujourd'hui</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-pencil fs-1 text-warning mb-2"></i>
                        <h4>28</h4>
                        <p class="text-muted mb-0">Modifications aujourd'hui</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-exclamation-triangle fs-1 text-danger mb-2"></i>
                        <h4>3</h4>
                        <p class="text-muted mb-0">Erreurs aujourd'hui</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau du journal -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Historique des Actions</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date/Heure</th>
                                <th>Utilisateur</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Description</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>20/02/2026 15:30:45</td>
                                <td>Admin Principal</td>
                                <td><span class="badge bg-success">Connexion</span></td>
                                <td>Auth</td>
                                <td>Connexion réussie</td>
                                <td>192.168.1.1</td>
                            </tr>
                            <tr>
                                <td>20/02/2026 15:25:12</td>
                                <td>Admin Principal</td>
                                <td><span class="badge bg-primary">Création</span></td>
                                <td>Entreprise</td>
                                <td>Nouvelle entreprise créée: Entreprise Test</td>
                                <td>192.168.1.1</td>
                            </tr>
                            <tr>
                                <td>20/02/2026 14:45:33</td>
                                <td>User_001</td>
                                <td><span class="badge bg-warning">Modification</span></td>
                                <td>Facture</td>
                                <td>Facture #FAC-2026-001 mise à jour</td>
                                <td>192.168.1.25</td>
                            </tr>
                            <tr>
                                <td>20/02/2026 14:30:00</td>
                                <td>Admin Principal</td>
                                <td><span class="badge bg-danger">Erreur</span></td>
                                <td>Auth</td>
                                <td>Echec de connexion - Mot de passe incorrect</td>
                                <td>192.168.1.50</td>
                            </tr>
                            <tr>
                                <td>20/02/2026 12:15:22</td>
                                <td>Superviseur_01</td>
                                <td><span class="badge bg-info">Export</span></td>
                                <td>Rapport</td>
                                <td>Export du rapport mensuel</td>
                                <td>192.168.1.30</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled"><a class="page-link" href="#">Précédent</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Suivant</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endSection