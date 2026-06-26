@extends('layouts.app')

@section('title', 'Mon Profil')

@push('styles')
<style>
    :root {
        --p-bg:          #f8f9fb;
        --p-surface:     #ffffff;
        --p-border:      #e5e7eb;
        --p-text:        #111827;
        --p-text-muted:  #6b7280;
        --p-primary:     #2563eb;
        --p-primary-bg:  #eff6ff;
        --p-primary-dim: rgba(37,99,235,.12);
        --p-shadow:      0 1px 4px rgba(0,0,0,.07), 0 4px 16px rgba(0,0,0,.05);
        --p-radius:      14px;
        --p-radius-sm:   8px;
        --p-input-bg:    #ffffff;
    }

    :root[data-bs-theme="dark"] {
        --p-bg:          #0f1117;
        --p-surface:     #1a1d27;
        --p-border:      #2a2d3a;
        --p-text:        #f0f2f8;
        --p-text-muted:  #8b90a8;
        --p-primary:     #60a5fa;
        --p-primary-bg:  rgba(96,165,250,.1);
        --p-primary-dim: rgba(96,165,250,.15);
        --p-shadow:      0 1px 4px rgba(0,0,0,.4), 0 4px 16px rgba(0,0,0,.3);
        --p-input-bg:    #0f1117;
    }

    .p-page { background: var(--p-bg); min-height: 100vh; }
    .p-card {
        background: var(--p-surface);
        border: 1px solid var(--p-border);
        border-radius: var(--p-radius);
        box-shadow: var(--p-shadow);
        overflow: hidden;
    }
    .p-card-header {
        padding: .875rem 1.25rem;
        border-bottom: 1px solid var(--p-border);
        display: flex; align-items: center; gap: .5rem;
    }
    .p-card-header-title { font-size: .875rem; font-weight: 600; color: var(--p-text); }
    .p-card-body { padding: 1.25rem; }

    .p-page-title { color: var(--p-text); font-size: 1.35rem; font-weight: 700; }
    .p-page-sub   { color: var(--p-text-muted); font-size: .875rem; }
    .p-label { color: var(--p-text); font-size: .875rem; font-weight: 600; display: block; margin-bottom: .4rem; }
    .p-text-muted { color: var(--p-text-muted); font-size: .8rem; }

    .p-input {
        display: block; width: 100%;
        background: var(--p-input-bg);
        border: 1px solid var(--p-border);
        border-radius: var(--p-radius-sm);
        padding: .55rem .85rem;
        color: var(--p-text);
        font-size: .9rem;
        transition: border-color .15s, box-shadow .15s;
        outline: none;
    }
    .p-input::placeholder { color: var(--p-text-muted); }
    .p-input:focus {
        border-color: var(--p-primary);
        box-shadow: 0 0 0 3px var(--p-primary-dim);
    }
    .p-input.is-invalid { border-color: #dc2626; }
    .p-feedback-invalid { color: #dc2626; font-size: .8rem; margin-top: .3rem; }

    .btn-p-primary {
        background: var(--p-primary); color: #fff; border: none;
        border-radius: var(--p-radius-sm); padding: .5rem 1.15rem;
        font-size: .875rem; font-weight: 500;
        display: inline-flex; align-items: center; gap: .4rem;
        cursor: pointer; transition: opacity .15s; text-decoration: none;
    }
    .btn-p-primary:hover { opacity: .88; color: #fff; }

    .p-avatar-lg {
        width: 100px; height: 100px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        color: white; font-weight: bold; font-size: 32px;
        border: 4px solid var(--p-border);
        object-fit: cover;
    }
    .p-alert {
        border-radius: var(--p-radius-sm); padding: .75rem 1rem;
        font-size: .875rem; display: flex; align-items: flex-start; gap: .5rem;
        border-left: 3px solid; margin-bottom: 1rem;
    }
    .p-alert-success { background: rgba(22,163,74,.08); color: #15803d; border-color: #16a34a; }
    :root[data-bs-theme="dark"] .p-alert-success { color: #4ade80; border-color: #4ade80; }

    .photo-upload-btn {
        width: 32px;
        height: 32px;
        border: 2px solid var(--p-surface);
    }

    :root[data-bs-theme="dark"] .photo-upload-btn {
        border-color: #1a1d27;
    }
</style>
@endpush

@section('content')
<div class="p-page">
<div class="container-fluid px-4 py-4">

    <div class="mb-4">
        <h1 class="p-page-title mb-1">Mon Profil</h1>
        <p class="p-page-sub mb-0">Gérez vos informations personnelles et votre mot de passe.</p>
    </div>

    @if(session('success'))
    <div class="p-alert p-alert-success">
        <i class="bi bi-check-circle-fill flex-shrink-0"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="row g-4">

        {{-- Colonne gauche : Avatar + infos --}}
        <div class="col-12 col-lg-4">
            <div class="p-card">
                <div class="p-card-body text-center">
                    @php
                    $photo = $user->photo ?? null;
                    $name = $user->name ?? $user->nomComplet ?? $user->nomAffichage ?? 'U';
                    $initial = strtoupper(substr($name, 0, 2));
                    @endphp
                    <div class="position-relative d-inline-block mb-3">
                        @if($photo)
                        <img src="{{ asset('storage/' . $photo) }}" alt="Photo" class="p-avatar-lg" id="profile-photo-preview">
                        @else
                        <div class="p-avatar-lg mx-auto" id="profile-photo-preview">{{ $initial }}</div>
                        @endif
                        <label for="photo-upload" class="position-absolute bottom-0 end-0 rounded-circle bg-success text-white d-flex align-items-center justify-content-center shadow photo-upload-btn">
                            <i class="bi bi-camera-fill" style="font-size:0.8rem;"></i>
                        </label>
                        <input type="file" id="photo-upload" name="photo" accept="image/jpg,image/jpeg,image/png,image/webp" class="d-none" form="profile-form">
                    </div>
                    <h5 class="mb-1" style="color:var(--p-text);">{{ $name }}</h5>
                    <p class="p-text-muted mb-0">{{ $user->email }}</p>
                    @if($user->roles->count())
                    <span class="badge bg-success mt-2">{{ $roleLabel ?? $user->roles->first()->name }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Colonne droite : formulaires --}}
        <div class="col-12 col-lg-8">
            {{-- Informations personnelles --}}
            <div class="p-card mb-4">
                <div class="p-card-header">
                    <i class="bi bi-person" style="color:var(--p-primary);"></i>
                    <span class="p-card-header-title">Informations personnelles</span>
                </div>
                <div class="p-card-body">
                    <form method="POST" action="{{ route($guard . '.profil.update') }}" enctype="multipart/form-data" id="profile-form">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            @if($user instanceof \App\Models\Employe || $user instanceof \App\Models\Client)
                            <div class="col-12 col-sm-6">
                                <label class="p-label">Prénom(s)</label>
                                <input type="text" name="prenoms" class="p-input" value="{{ old('prenoms', $user->prenoms ?? '') }}">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="p-label">Nom</label>
                                <input type="text" name="nom" class="p-input @error('nom') is-invalid @enderror" value="{{ old('nom', $user->nom ?? '') }}">
                                @error('nom')<div class="p-feedback-invalid">{{ $message }}</div>@enderror
                            </div>
                            @else
                            <div class="col-12">
                                <label class="p-label">Nom complet</label>
                                <input type="text" name="name" class="p-input @error('name') is-invalid @enderror" value="{{ old('name', $user->name ?? '') }}">
                                @error('name')<div class="p-feedback-invalid">{{ $message }}</div>@enderror
                            </div>
                            @endif

                            <div class="col-12 col-sm-6">
                                <label class="p-label">Email</label>
                                <input type="email" name="email" class="p-input @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                                @error('email')<div class="p-feedback-invalid">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="p-label">Téléphone</label>
                                <input type="text" name="telephone" class="p-input" value="{{ old('telephone', $user->telephone ?? '') }}">
                            </div>

                            @if($user instanceof \App\Models\Employe)
                            <div class="col-12">
                                <label class="p-label">Adresse</label>
                                <input type="text" name="adresse" class="p-input" value="{{ old('adresse', $user->adresse ?? '') }}">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="p-label">Téléphone urgence</label>
                                <input type="text" name="telephone_urgence" class="p-input" value="{{ old('telephone_urgence', $user->telephone_urgence ?? '') }}">
                            </div>
                            @endif

                            @if($user instanceof \App\Models\Client)
                            <div class="col-12">
                                <label class="p-label">Adresse</label>
                                <input type="text" name="adresse" class="p-input" value="{{ old('adresse', $user->adresse ?? '') }}">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="p-label">Ville</label>
                                <input type="text" name="ville" class="p-input" value="{{ old('ville', $user->ville ?? '') }}">
                            </div>
                            @endif
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn-p-primary">
                                <i class="bi bi-check-lg"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Mot de passe --}}
            <div class="p-card">
                <div class="p-card-header">
                    <i class="bi bi-lock" style="color:var(--p-primary);"></i>
                    <span class="p-card-header-title">Mot de passe</span>
                </div>
                <div class="p-card-body">
                    <form method="POST" action="{{ route($guard . '.profil.password') }}">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="p-label">Mot de passe actuel</label>
                                <input type="password" name="current_password" class="p-input @error('current_password') is-invalid @enderror" required>
                                @error('current_password')<div class="p-feedback-invalid">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="p-label">Nouveau mot de passe</label>
                                <input type="password" name="password" class="p-input @error('password') is-invalid @enderror" required>
                                @error('password')<div class="p-feedback-invalid">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="p-label">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation" class="p-input" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn-p-primary">
                                <i class="bi bi-key"></i> Modifier le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
    document.getElementById('photo-upload')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(ev) {
            const preview = document.getElementById('profile-photo-preview');
            if (preview.tagName === 'IMG') {
                preview.src = ev.target.result;
            } else {
                const img = document.createElement('img');
                img.src = ev.target.result;
                img.alt = 'Photo';
                img.className = preview.className;
                preview.parentNode.replaceChild(img, preview);
                img.id = 'profile-photo-preview';
            }
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
@endsection
