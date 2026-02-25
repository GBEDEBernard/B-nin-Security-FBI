@extends('layouts.app')

@section('title', 'Gestion des Notifications Push - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-bell-fill text-warning me-2"></i>
                    Notifications Push
                </h2>
                <p class="text-muted mb-0">Envoyez des notifications aux utilisateurs de l'application mobile</p>
            </div>
        </div>

        <div class="row">
            <!-- Formulaire d'envoi -->
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-send me-2"></i>Envoyer une Notification</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Titre</label>
                                <input type="text" class="form-control" placeholder="Titre de la notification">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message</label>
                                <textarea class="form-control" rows="4" placeholder="Contenu de la notification"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Type de notification</label>
                                <select class="form-select">
                                    <option value="info">Information</option>
                                    <option value="success">Succès</option>
                                    <option value="warning">Avertissement</option>
                                    <option value="error">Erreur</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Envoyer à</label>
                                <select class="form-select">
                                    <option value="all">Tous les utilisateurs</option>
                                    <option value="entreprise">Par entreprise</option>
                                    <option value="role">Par rôle</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">URL cible (optionnel)</label>
                                <input type="text" class="form-control" placeholder="https://...">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-1"></i> Envoyer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Historique -->
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Historique des Notifications</h5>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Actualiser
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Titre</th>
                                        <th>Destinataires</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>20/02/2026 14:30</td>
                                        <td>Mise à jour disponible</td>
                                        <td>Tous</td>
                                        <td><span class="badge bg-success">Envoyée</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>19/02/2026 10:15</td>
                                        <td>Nouveau contrat signé</td>
                                        <td>Direction</td>
                                        <td><span class="badge bg-success">Envoyée</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>18/02/2026 09:00</td>
                                        <td>Rappel pointage</td>
                                        <td>Agents</td>
                                        <td><span class="badge bg-success">Envoyée</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Statistiques</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h3 class="text-primary">3</h3>
                                <p class="text-muted mb-0">Envoyées aujourd'hui</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="text-success">156</h3>
                                <p class="text-muted mb-0">Total notifications</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="text-info">98%</h3>
                                <p class="text-muted mb-0">Taux de lecture</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endSection