@extends('layouts.app')

@section('title', 'Envoyer une Notification - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-send text-success me-2"></i>
                Envoyer une Notification
            </h2>
            <p class="text-muted mb-0">Envoyez des notifications push aux utilisateurs</p>
        </div>
        <a href="{{ route('admin.superadmin.notifications.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="row">
        <!-- Formulaire -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Nouvelle Notification
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.superadmin.notifications.store') }}" id="notificationForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Titre de la notification</label>
                            <input type="text" name="titre" class="form-control" placeholder="Ex: Mise à jour disponible" required maxlength="255">
                            <small class="text-muted">Titre qui apparaîtra dans la notification</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Message</label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Contenu de votre notification..." required></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Type de notification</label>
                                <select name="type" class="form-select" required>
                                    <option value="info">Information</option>
                                    <option value="success">Succès</option>
                                    <option value="warning">Avertissement</option>
                                    <option value="error">Erreur</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">URL cible (optionnel)</label>
                                <input type="text" name="url" class="form-control" placeholder="https://exemple.com/page">
                                <small class="text-muted">Lien vers lequel l'utilisateur sera dirigé</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">
                            <i class="bi bi-people me-2"></i>Destinataires
                        </h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Type d'envoi</label>
                            <select name="type_envoi" class="form-select" id="typeEnvoi" required>
                                <option value="all">Tous les utilisateurs</option>
                                <option value="entreprise">Par entreprise</option>
                                <option value="role">Par rôle</option>
                                <option value="utilisateur">Utilisateur(s) spécifique(s)</option>
                            </select>
                        </div>

                        <!-- Sélection par entreprise -->
                        <div class="mb-3" id="entrepriseSection" style="display: none;">
                            <label class="form-label fw-bold">Entreprise</label>
                            <select name="entreprise_id" class="form-select select2">
                                <option value="">Sélectionner une entreprise</option>
                                @foreach($entreprises as $entreprise)
                                <option value="{{ $entreprise->id }}">{{ $entreprise->nom_entreprise }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sélection par rôle -->
                        <div class="mb-3" id="roleSection" style="display: none;">
                            <label class="form-label fw-bold">Rôle</label>
                            <select name="role" class="form-select">
                                <option value="">Sélectionner un rôle</option>
                                @foreach($roles as $role)
                                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sélection d'utilisateurs spécifiques -->
                        <div class="mb-3" id="utilisateurSection" style="display: none;">
                            <label class="form-label fw-bold">Utilisateur(s)</label>
                            <select name="utilisateur_ids[]" class="form-select select2" multiple>
                            </select>
                            <small class="text-muted">Recherchez et sélectionnez les utilisateurs</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send me-1"></i> Envoyer la notification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Aperçu -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-phone me-2"></i>Aperçu
                    </h5>
                </div>
                <div class="card-body">
                    <div class="notification-preview p-3 border rounded" style="background: #f8f9fa;">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2" id="previewIcon">
                                    <i class="bi bi-bell-fill fs-5 text-primary" id="previewIconInner"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1" id="previewTitle">Titre de la notification</h6>
                                <p class="text-muted small mb-0" id="previewMessage">Le message de votre notification apparaîtra ici...</p>
                                <small class="text-muted d-block mt-2">Il y a 1 minute</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Types disponibles -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Types de notification
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-info me-2"><i class="bi bi-info-circle"></i></span>
                        <small>Information - Messages généraux</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-success me-2"><i class="bi bi-check-circle"></i></span>
                        <small>Succès - Opérations réussies</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-warning me-2"><i class="bi bi-exclamation-triangle"></i></span>
                        <small>Avertissement - Alertes importantes</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger me-2"><i class="bi bi-x-circle"></i></span>
                        <small>Erreur - Problèmes et erreurs</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var typeEnvoi = document.getElementById('typeEnvoi');
        var entrepriseSection = document.getElementById('entrepriseSection');
        var roleSection = document.getElementById('roleSection');
        var utilisateurSection = document.getElementById('utilisateurSection');

        // Gestion de l'affichage des sections
        typeEnvoi.addEventListener('change', function() {
            entrepriseSection.style.display = 'none';
            roleSection.style.display = 'none';
            utilisateurSection.style.display = 'none';

            switch (this.value) {
                case 'entreprise':
                    entrepriseSection.style.display = 'block';
                    break;
                case 'role':
                    roleSection.style.display = 'block';
                    break;
                case 'utilisateur':
                    utilisateurSection.style.display = 'block';
                    break;
            }
        });

        // Aperçu en temps réel
        var titreInput = document.querySelector('input[name="titre"]');
        var messageInput = document.querySelector('textarea[name="message"]');
        var typeInput = document.querySelector('select[name="type"]');

        var previewTitle = document.getElementById('previewTitle');
        var previewMessage = document.getElementById('previewMessage');
        var previewIconInner = document.getElementById('previewIconInner');
        var previewIcon = document.getElementById('previewIcon');

        function updatePreview() {
            previewTitle.textContent = titreInput.value || 'Titre de la notification';
            previewMessage.textContent = messageInput.value || 'Le message de votre notification apparaîtra ici...';

            var type = typeInput.value;
            var colors = {
                'info': 'text-primary bg-primary',
                'success': 'text-success bg-success',
                'warning': 'text-warning bg-warning',
                'error': 'text-danger bg-danger'
            };
            var icons = {
                'info': 'bi-info-circle-fill',
                'success': 'bi-check-circle-fill',
                'warning': 'bi-exclamation-triangle-fill',
                'error': 'bi-x-circle-fill'
            };

            previewIconInner.className = 'fs-5 ' + icons[type];
            previewIcon.className = 'bg-' + type.split('-')[0] + ' bg-opacity-10 rounded-circle p-2';
        }

        titreInput.addEventListener('input', updatePreview);
        messageInput.addEventListener('input', updatePreview);
        typeInput.addEventListener('change', updatePreview);
    });
</script>
@endpush

@endsection