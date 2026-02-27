@extends('layouts.app')

@section('title', 'Modifier le Super Administrateur - Super Admin')

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .required-indicator {
        color: #dc3545;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 0.7rem 1rem;
        border: 1.5px solid #e9ecef;
        transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }

    .input-group-text {
        border-radius: 10px 0 0 10px;
        border: 1.5px solid #e9ecef;
        background: #f8f9fa;
    }

    .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    .password-toggle {
        cursor: pointer;
    }

    .btn-save {
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
    }

    /* Avatar latéral — contient soit une <img> soit un <span> d'initiales */
    .avatar-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #198754, #20c997);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 600;
        color: white;
        border: 4px solid #e9ecef;
        overflow: hidden;
        position: relative;
    }

    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        display: block;
    }

    .info-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.25rem;
        border-left: 4px solid #198754;
    }

    .info-box-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(25, 135, 84, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #198754;
    }

    /* ===== Zone upload photo (petit cercle dans le formulaire) ===== */
    .preview-logo {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 3px dashed #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        overflow: hidden;
        background: #f8f9fa;
        flex-shrink: 0;
        transition: border-color 0.2s;
    }

    .preview-logo:hover {
        border-color: #198754;
    }

    .preview-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .preview-logo .placeholder-text {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #adb5bd;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-person-fill-gear me-2"></i>Modifier le Super Administrateur</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.utilisateurs.index') }}">Super Administrateurs</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Erreurs de validation :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- enctype obligatoire pour l'upload de fichier --}}
        <form action="{{ route('admin.superadmin.utilisateurs.update', $utilisateur->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-wizard">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- ===== Colonne principale ===== -->
                <div class="col-lg-8">
                    <div class="card form-card">
                        <div class="card-body p-4">
                            <h5 class="mb-4"><i class="bi bi-person-vcard me-2"></i>Informations du Super Administrateur</h5>

                            <div class="row g-4">

                                <!-- Nom complet -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label">
                                        Nom complet <span class="required-indicator">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name"
                                               value="{{ old('name', $utilisateur->name) }}"
                                               placeholder="Entrez le nom complet"
                                               required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        Adresse email <span class="required-indicator">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email"
                                               value="{{ old('email', $utilisateur->email) }}"
                                               placeholder="exemple@email.com"
                                               required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Téléphone -->
                                <div class="col-md-6">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="tel" class="form-control"
                                               id="telephone" name="telephone"
                                               value="{{ old('telephone', $utilisateur->telephone) }}"
                                               placeholder="+229 XX XX XX XX">
                                    </div>
                                </div>

                                <!-- Photo de profil (petit cercle cliquable) -->
                                <div class="col-md-6">
                                    <label class="form-label">Photo de profil</label>
                                    <div class="d-flex align-items-center gap-3">

                                        <div class="preview-logo"
                                             onclick="document.getElementById('photo').click()"
                                             title="Cliquer pour changer la photo">
                                            @if($utilisateur->photo)
                                                <img id="photoPreview"
                                                     src="{{ asset('storage/' . $utilisateur->photo) }}"
                                                     alt="Photo actuelle">
                                            @else
                                                <img id="photoPreview" src="" style="display:none;" alt="">
                                                <div id="photoPlaceholder" class="placeholder-text">
                                                    <i class="bi bi-camera fs-4"></i>
                                                    <span style="font-size:0.65rem; margin-top:2px;">Photo</span>
                                                </div>
                                            @endif
                                        </div>

                                        <input type="file"
                                               id="photo" name="photo"
                                               accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/bmp"
                                               style="display:none;"
                                               onchange="previewPhoto(event)">

                                        <div>
                                            <p class="mb-1 small text-muted"><i class="bi bi-info-circle me-1"></i>JPG, PNG, GIF, WEBP, BMP</p>
                                            <p class="mb-1 small text-muted">Taille max : 2 MB</p>
                                            <p class="mb-0 small text-muted fst-italic">Laisser vide pour conserver la photo actuelle</p>
                                        </div>
                                    </div>
                                    @error('photo')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Statut -->
                                <div class="col-md-6">
                                    <label class="form-label">Statut du compte</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox"
                                               id="is_active" name="is_active" value="1"
                                               {{ old('is_active', $utilisateur->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Compte actif</label>
                                    </div>
                                </div>

                                <!-- Séparateur mot de passe -->
                                <div class="col-12">
                                    <hr class="my-2">
                                    <h5 class="mb-2"><i class="bi bi-key me-2"></i>Changer le mot de passe</h5>
                                    <p class="text-muted small mb-3">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Laissez ces champs <strong>vides</strong> si vous ne souhaitez pas modifier le mot de passe.
                                    </p>
                                </div>

                                <!-- Nouveau mot de passe -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Nouveau mot de passe</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password"
                                               placeholder="Minimum 8 caractères"
                                               autocomplete="new-password">
                                        <button class="btn btn-outline-secondary password-toggle"
                                                type="button"
                                                onclick="togglePassword('password', 'icon-pwd')">
                                            <i class="bi bi-eye" id="icon-pwd"></i>
                                        </button>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirmation mot de passe -->
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password"
                                               class="form-control"
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               placeholder="Répétez le mot de passe"
                                               autocomplete="new-password">
                                        <button class="btn btn-outline-secondary password-toggle"
                                                type="button"
                                                onclick="togglePassword('password_confirmation', 'icon-confirm')">
                                            <i class="bi bi-eye" id="icon-confirm"></i>
                                        </button>
                                    </div>
                                    <small id="password-match" class="mt-1 d-block"></small>
                                </div>

                            </div>{{-- end row g-4 --}}
                        </div>
                    </div>
                </div>

                <!-- ===== Colonne latérale ===== -->
                <div class="col-lg-4">

                    <!-- Aperçu profil -->
                    <div class="card form-card mb-4">
                        <div class="card-body text-center">

                            {{--
                                Avatar latéral :
                                - S'il y a une photo  → on affiche l'<img>, on cache le <span> d'initiales
                                - S'il n'y a pas photo → on affiche le <span> d'initiales, l'<img> reste cachée
                                - Quand l'utilisateur choisit une nouvelle photo → le JS met à jour les deux
                            --}}
                            <div class="avatar-preview mx-auto mb-3">
                                @if($utilisateur->photo)
                                    <img id="avatar-photo"
                                         src="{{ asset('storage/' . $utilisateur->photo) }}"
                                         alt="Photo de {{ $utilisateur->name }}">
                                    <span id="avatar-initials" style="display:none;">
                                        {{ strtoupper(substr($utilisateur->name, 0, 2)) }}
                                    </span>
                                @else
                                    <img id="avatar-photo" src="" alt="" style="display:none;">
                                    <span id="avatar-initials">
                                        {{ strtoupper(substr($utilisateur->name, 0, 2)) }}
                                    </span>
                                @endif
                            </div>

                            <h6 class="text-muted mb-1">Profil</h6>
                            <p class="mb-0 fw-semibold" id="preview-name">{{ $utilisateur->name }}</p>
                            <p class="text-muted small mb-2">{{ $utilisateur->email }}</p>
                            @if($utilisateur->is_active)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-danger">Inactif</span>
                            @endif
                        </div>
                    </div>

                    <!-- Info box -->
                    <div class="info-box mb-4">
                        <div class="d-flex align-items-start">
                            <div class="info-box-icon me-3">
                                <i class="bi bi-shield-exclamation"></i>
                            </div>
                            <div>
                                <h6 class="mb-2">Attention</h6>
                                <p class="small text-muted mb-0">
                                    Les Super Administrateurs ont un accès complet au système.
                                    Toute modification ici affecte la sécurité du système.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons action -->
                    <div class="card form-card">
                        <div class="card-body">
                            <h6 class="mb-3">Actions</h6>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-save">
                                    <i class="bi bi-check-circle me-2"></i>Enregistrer les modifications
                                </button>
                                <a href="{{ route('admin.superadmin.utilisateurs.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Annuler
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
<!--end::App Content-->

@push('scripts')
<script>
    // Mise à jour aperçu nom + initiales en temps réel
    document.getElementById('name').addEventListener('input', function () {
        const name     = this.value.trim();
        const preview  = document.getElementById('preview-name');
        const initials = document.getElementById('avatar-initials');

        if (name) {
            preview.textContent = name;
            const words = name.split(' ');
            const txt = words.length >= 2
                ? (words[0][0] + words[1][0]).toUpperCase()
                : name.substring(0, 2).toUpperCase();
            // Mettre à jour le texte des initiales seulement si elles sont visibles
            if (initials && initials.style.display !== 'none') {
                initials.textContent = txt;
            }
        } else {
            preview.textContent = '{{ $utilisateur->name }}';
            if (initials && initials.style.display !== 'none') {
                initials.textContent = '{{ strtoupper(substr($utilisateur->name, 0, 2)) }}';
            }
        }
    });

    // Toggle affichage mot de passe
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // Correspondance mots de passe en temps réel
    document.getElementById('password_confirmation').addEventListener('input', checkMatch);
    document.getElementById('password').addEventListener('input', checkMatch);

    function checkMatch() {
        const pwd   = document.getElementById('password').value;
        const conf  = document.getElementById('password_confirmation').value;
        const label = document.getElementById('password-match');
        if (!pwd && !conf) { label.textContent = ''; return; }
        if (!conf)         { label.textContent = ''; return; }
        if (pwd === conf) {
            label.textContent = '✓ Les mots de passe correspondent';
            label.className   = 'mt-1 d-block text-success small';
        } else {
            label.textContent = '✗ Les mots de passe ne correspondent pas';
            label.className   = 'mt-1 d-block text-danger small';
        }
    }

    // Prévisualisation photo :
    // 1. Met à jour le petit cercle de saisie (colonne gauche)
    // 2. Met à jour l'avatar latéral (colonne droite) — photo remplace les initiales
    function previewPhoto(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            // --- Cercle de saisie (gauche) ---
            const inputImg    = document.getElementById('photoPreview');
            const placeholder = document.getElementById('photoPlaceholder');
            inputImg.src           = e.target.result;
            inputImg.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';

            // --- Avatar latéral (droite) ---
            const avatarPhoto    = document.getElementById('avatar-photo');
            const avatarInitials = document.getElementById('avatar-initials');
            avatarPhoto.src           = e.target.result;
            avatarPhoto.style.display = 'block';
            if (avatarInitials) avatarInitials.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
@endsection