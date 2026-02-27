@extends('layouts.app')

@section('title', 'Créer un Super Administrateur - Super Admin')

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

    .password-strength {
        height: 4px;
        border-radius: 2px;
        margin-top: 0.5rem;
        transition: all 0.3s;
    }

    .strength-weak {
        background: #dc3545;
        width: 25%;
    }

    .strength-fair {
        background: #ffc107;
        width: 50%;
    }

    .strength-good {
        background: #20c997;
        width: 75%;
    }

    .strength-strong {
        background: #198754;
        width: 100%;
    }

    .password-requirements {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }

    .password-requirements li {
        margin-bottom: 0.25rem;
    }

    .password-requirements li.valid {
        color: #198754;
    }

    .password-requirements li.valid::before {
        content: "✓ ";
    }

    .password-requirements li:not(.valid)::before {
        content: "✗ ";
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

    .avatar-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 600;
        color: #6c757d;
        border: 3px dashed #dee2e6;
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
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>Nouveau Super Administrateur</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.utilisateurs.index') }}">Super Administrateurs</a></li>
                    <li class="breadcrumb-item active">Créer</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        <!-- Messages d'erreur de validation -->
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

        <form action="{{ route('admin.superadmin.utilisateurs.store') }}" method="POST" class="form-wizard">
            @csrf

            <div class="row">
                <!-- Colonne principale -->
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
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="Entrez le nom complet" required>
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
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="exemple@email.com" required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Photo de profil -->
                                <div class="col-12">
                                    <label class="form-label">Photo de profil</label>
                                    <div class="d-flex align-items-start gap-4">
                                        <div class="preview-logo" onclick="document.getElementById('photo').click()">
                                            <img id="photoPreview" src="" style="display: none;">
                                            <div id="photoPlaceholder" class="text-center text-muted">
                                                <i class="bi bi-image fs-3"></i>
                                                <p class="mb-0 small">Cliquez pour uploader</p>
                                            </div>
                                        </div>
                                        <input type="file" id="photo" name="photo" accept="image/*" style="display: none;"
                                            onchange="previewPhoto(event)">
                                        <div>
                                            <p class="mb-1 small text-muted">Formats acceptés: JPG, PNG, GIF</p>
                                            <p class="mb-0 small text-muted">Taille max: 2MB</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Téléphone -->
                                <div class="col-md-6">
                                    <label for="telephone" class="form-label">
                                        Téléphone
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="tel" class="form-control"
                                            id="telephone" name="telephone" value="{{ old('telephone') }}"
                                            placeholder="+229 XX XX XX XX">
                                    </div>
                                </div>

                                <!-- Statut -->
                                <div class="col-md-6">
                                    <label class="form-label">Statut du compte</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="is_active"
                                            name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Compte actif
                                        </label>
                                    </div>
                                </div>

                                <!-- Mot de passe -->
                                <div class="col-12">
                                    <hr class="my-4">
                                    <h5 class="mb-4"><i class="bi bi-key me-2"></i>Mot de passe</h5>
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label">
                                        Mot de passe <span class="required-indicator">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password"
                                            placeholder="Minimum 8 caractères" required
                                            onkeyup="checkPasswordStrength()">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="bi bi-eye" id="password-icon"></i>
                                        </button>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="password-strength" id="password-strength"></div>
                                </div>

                                <!-- Confirmation mot de passe -->
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">
                                        Confirmer le mot de passe <span class="required-indicator">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" class="form-control"
                                            id="password_confirmation" name="password_confirmation"
                                            placeholder="Répétez le mot de passe" required
                                            oninput="checkPasswordMatch()">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                            <i class="bi bi-eye" id="password_confirmation-icon"></i>
                                        </button>
                                    </div>
                                    <small class="text-success" id="password-match"></small>
                                </div>

                                <!-- Exigences mot de passe -->
                                <div class="col-12">
                                    <ul class="password-requirements">
                                        <li class="{{ strlen(old('password')) >= 8 ? 'valid' : '' }}" id="req-length">
                                            Au moins 8 caractères
                                        </li>
                                        <li class="{{ preg_match('/[A-Z]/', old('password')) ? 'valid' : '' }}" id="req-upper">
                                            Au moins une majuscule
                                        </li>
                                        <li class="{{ preg_match('/[0-9]/', old('password')) ? 'valid' : '' }}" id="req-number">
                                            Au moins un chiffre
                                        </li>
                                        <li class="{{ old('password') && old('password_confirmation') && old('password') === old('password_confirmation') ? 'valid' : '' }}" id="req-match">
                                            Les mots de passe correspondent
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne latérale -->
                <div class="col-lg-4">
                    <!-- Aperçu -->
                    <div class="card form-card mb-4">
                        <div class="card-body text-center">
                            <div class="avatar-preview mx-auto mb-3" id="avatar-preview">
                                <span id="avatar-initials">SA</span>
                            </div>
                            <h6 class="text-muted mb-1">Aperçu</h6>
                            <p class="mb-0 fw-semibold" id="preview-name">{{ old('name') ?: 'Nouveau Super Admin' }}</p>
                            <p class="text-muted small mb-0" id="preview-email">{{ old('email') ?: 'email@exemple.com' }}</p>
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
                                    Créez ce compte avec précaution et ne le partagez pas.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card form-card">
                        <div class="card-body">
                            <h6 class="mb-3">Actions</h6>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-save">
                                    <i class="bi bi-check-circle me-2"></i>Créer le Super Admin
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
    // Mettre à jour l'aperçu en temps réel
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value.trim();
        const preview = document.getElementById('preview-name');
        const initials = document.getElementById('avatar-initials');

        if (name) {
            preview.textContent = name;
            const words = name.split(' ');
            if (words.length >= 2) {
                initials.textContent = (words[0][0] + words[1][0]).toUpperCase();
            } else {
                initials.textContent = name.substring(0, 2).toUpperCase();
            }
        } else {
            preview.textContent = 'Nouveau Super Admin';
            initials.textContent = 'SA';
        }
    });

    document.getElementById('email').addEventListener('input', function() {
        const email = this.value.trim();
        document.getElementById('preview-email').textContent = email || 'email@exemple.com';
    });

    // Afficher/masquer le mot de passe
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '-icon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    // Vérifier la force du mot de passe
    function checkPasswordStrength() {
        const password = document.getElementById('password').value;
        const strengthBar = document.getElementById('password-strength');

        let strength = 0;

        if (password.length >= 8) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^A-Za-z0-9]/)) strength++;

        strengthBar.className = 'password-strength';

        if (password.length === 0) {
            // Nothing
        } else if (strength <= 1) {
            strengthBar.classList.add('strength-weak');
        } else if (strength === 2) {
            strengthBar.classList.add('strength-fair');
        } else if (strength === 3) {
            strengthBar.classList.add('strength-good');
        } else {
            strengthBar.classList.add('strength-strong');
        }

        // Update requirements display
        document.getElementById('req-length').classList.toggle('valid', password.length >= 8);
        document.getElementById('req-upper').classList.toggle('valid', /[A-Z]/.test(password));
        document.getElementById('req-number').classList.toggle('valid', /[0-9]/.test(password));

        checkPasswordMatch();
    }

    // Vérifier la correspondance des mots de passe
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;
        const matchText = document.getElementById('password-match');

        if (confirmation.length > 0) {
            if (password === confirmation) {
                matchText.textContent = '✓ Les mots de passe correspondent';
                matchText.className = 'text-success';
            } else {
                matchText.textContent = '✗ Les mots de passe ne correspondent pas';
                matchText.className = 'text-danger';
            }
        } else {
            matchText.textContent = '';
        }

        document.getElementById('req-match').classList.toggle('valid', password === confirmation && password.length > 0);
    }

    // Prévisualiser la photo
    function previewPhoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
                document.getElementById('photoPreview').style.display = 'block';
                document.getElementById('photoPlaceholder').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection