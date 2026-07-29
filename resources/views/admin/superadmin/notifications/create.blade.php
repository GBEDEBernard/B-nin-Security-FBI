@extends('layouts.app')

@section('title', 'Créer une Notification - Super Admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-send text-primary me-2"></i>
                    Créer une Notification
                </h2>
                <p class="text-muted mb-0">Envoyer une notification push aux utilisateurs</p>
            </div>
            <a href="{{ route('admin.superadmin.notifications.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Nouvelle Notification</h5>
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
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select class="form-select">
                                <option value="info">Information</option>
                                <option value="success">Succès</option>
                                <option value="warning">Avertissement</option>
                                <option value="error">Erreur</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cible</label>
                            <select class="form-select">
                                <option value="all">Tous les utilisateurs</option>
                                <option value="entreprise">Par entreprise</option>
                                <option value="role">Par rôle</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL cible (optionnel)</label>
                        <input type="text" class="form-control" placeholder="https://...">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i> Envoyer
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
