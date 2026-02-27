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

    .current-password-section {
        background: #fff3cd;
        border-radius: 12px;
        padding: 1.25rem;
        border: 1px solid #ffc107;
    }

    .current-password-section .input-group-text {
        background: #fff8e1;
        border-color: #ffc107;
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

        <form action="{{ route('admin.superadmin.utilisateurs.update', $utilisateur->id) }}" method="POST" class="form-wizard">
            @csrf
            @method('PUT')

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
                                            id="name" name="name" value="{{ old('name', $utilisateur->name) }}"
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
                                            id="email" name="email" value="{{ old('email', $utilisateur->email) }}"
                                            placeholder="exemple@email.com" required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                            id="telephone" name="telephone" value="{{ old('telephone', $utilisateur->telephone) }}"
                                            placeholder="+229 XX XX XX XX">
                                    </div>
                                </div>

                                <!-- Statut -->
                                <div class="col-md-6">
                                    <label class="form-label">Statut du compte</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="is_active"
                                            name="is_active" value="1" {{ old('is_active', $utilisateur->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Compte actif
                                        </label>
                                    </div>
                                </div>

                                <!-- Section mot de passe -->
                                <div class="col-12">
                                    <hr class="my-4">
                                    <h5 class="mb-4"><i class="bi bi-key me-2"></i>Changer le mot de passe</h5>
                                    <p class="text-muted small mb-3">
                                        Laissez les champs de mot de passe vides si vous ne souhaitez pas le modifier.
                                    </p>
                                </div>

                                <!-- Nouveau mot de passe -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label">
                                        Nouveau mot de passe
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password"
                                            placeholder="Minimum 8 caractères">
                                        <button class="btn btn-outline-secondary password-toggle" type="button" onclick="togglePassword('password')">
                                            <i class="bi bi-eye" id="password-icon"></i>
                                        </button>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirmation mot de passe -->
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">
                                        Confirmer le mot de passe
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" class="form-control"
                                            id="password_confirmation" name="password_confirmation"
                                            placeholder="Répétez le mot de passe">
                                        <button class="btn btn-outline-secondary password-toggle" type="button" onclick="togglePassword('password_confirmation')">
                                            <i class="bi bi-eye" id="password_confirmation-icon"></i>
                                        </button>
                                    </div>
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
                            <div class="avatar-preview mx-auto mb-3">
                                <span id="avatar-initials">{{ strtoupper(substr($utilisateur->name, 0, 2)) }}</span>
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

                    <!-- Actions -->
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
            preview.textContent = '{{ $utilisateur->name }}';
            initials.textContent = '{{ strtoupper(substr($utilisateur->name, 0, 2)) }}';
        }
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
</script>
@endpush
@endsection