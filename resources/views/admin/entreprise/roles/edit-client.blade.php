@extends('layouts.app')

@section('title', 'Gérer les Rôles Client - Entreprise')

@push('styles')
<style>
    /* ── Tokens adaptatifs light / dark ── */
    :root {
        --r-bg:          #f8f9fb;
        --r-surface:     #ffffff;
        --r-border:      #e5e7eb;
        --r-text:        #111827;
        --r-text-muted:  #6b7280;
        --r-primary:     #2563eb;
        --r-primary-bg:  #eff6ff;
        --r-primary-dim: rgba(37,99,235,.12);
        --r-shadow:      0 1px 4px rgba(0,0,0,.07), 0 4px 16px rgba(0,0,0,.05);
        --r-radius:      14px;
        --r-radius-sm:   8px;
    }
    :root[data-bs-theme="dark"] {
            --r-bg:          #0f1117;
            --r-surface:     #1a1d27;
            --r-border:      #2a2d3a;
            --r-text:        #f0f2f8;
            --r-text-muted:  #8b90a8;
            --r-primary:     #60a5fa;
            --r-primary-bg:  rgba(96,165,250,.1);
            --r-primary-dim: rgba(96,165,250,.15);
            --r-shadow:      0 1px 4px rgba(0,0,0,.4), 0 4px 16px rgba(0,0,0,.3);
    }

    .r-page { background: var(--r-bg); min-height: 100vh; }

    /* ── Carte ── */
    .r-card {
        background: var(--r-surface);
        border: 1px solid var(--r-border);
        border-radius: var(--r-radius);
        box-shadow: var(--r-shadow);
        overflow: hidden;
    }
    .r-card-header {
        padding: .875rem 1.25rem;
        border-bottom: 1px solid var(--r-border);
        display: flex; align-items: center; gap: .5rem;
    }
    .r-card-header-title { font-size: .875rem; font-weight: 600; color: var(--r-text); }
    .r-card-body { padding: 1.25rem; }

    /* ── Texte ── */
    .r-page-title { color: var(--r-text); font-size: 1.35rem; font-weight: 700; }
    .r-page-sub   { color: var(--r-text-muted); font-size: .875rem; }
    .r-hint       { color: var(--r-text-muted); font-size: .85rem; margin-bottom: 1.25rem; }
    .r-hint strong { color: var(--r-text); font-weight: 600; }

    /* ── Option de rôle ── */
    .role-option {
        display: flex; align-items: center; gap: .875rem;
        padding: .875rem 1rem;
        border: 1px solid var(--r-border);
        border-radius: var(--r-radius-sm);
        cursor: pointer;
        transition: border-color .15s, background .15s;
        background: var(--r-surface);
    }
    .role-option:hover {
        border-color: var(--r-primary);
        background: var(--r-primary-bg);
    }
    .role-option.selected {
        border-color: var(--r-primary);
        background: var(--r-primary-bg);
    }
    .role-checkbox {
        width: 17px; height: 17px;
        accent-color: var(--r-primary);
        cursor: pointer; flex-shrink: 0;
    }
    .role-name  { color: var(--r-text); font-size: .875rem; font-weight: 600; line-height: 1.3; }
    .role-count { color: var(--r-text-muted); font-size: .75rem; margin-top: 1px; }

    /* ── Boutons ── */
    .btn-r-primary {
        background: var(--r-primary); color: #fff; border: none;
        border-radius: var(--r-radius-sm); padding: .5rem 1.15rem;
        font-size: .875rem; font-weight: 500;
        display: inline-flex; align-items: center; gap: .4rem;
        cursor: pointer; transition: opacity .15s; text-decoration: none;
    }
    .btn-r-primary:hover { opacity: .88; color: #fff; }

    .btn-r-ghost {
        background: transparent; color: var(--r-text-muted);
        border: 1px solid var(--r-border);
        border-radius: var(--r-radius-sm); padding: .5rem 1rem;
        font-size: .875rem; font-weight: 500;
        display: inline-flex; align-items: center; gap: .4rem;
        transition: all .15s; text-decoration: none;
    }
    .btn-r-ghost:hover { color: var(--r-text); border-color: var(--r-text-muted); }
</style>
@endpush

@section('content')
<div class="r-page">
<div class="container-fluid px-4 py-4">

    {{-- En-tête --}}
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="r-page-title mb-1">Gérer les Rôles Client</h1>
            <p class="r-page-sub mb-0">
                <strong style="color:var(--r-text);">{{ $client->nomAffichage }}</strong>
                <span style="margin: 0 .35rem; color:var(--r-border);">·</span>
                {{ $client->typeLabel }}
            </p>
        </div>
        <a href="{{ route('admin.entreprise.roles.clients') }}" class="btn-r-ghost">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <form action="{{ route('admin.entreprise.roles.update-client', $client->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="r-card mb-4">
            <div class="r-card-header">
                <i class="bi bi-shield-check" style="color:var(--r-primary);"></i>
                <span class="r-card-header-title">Rôles disponibles</span>
            </div>
            <div class="r-card-body">
                <p class="r-hint">
                    Sélectionnez les rôles à attribuer à <strong>{{ $client->nomAffichage }}</strong>.
                </p>
                <div class="row g-2">
                    @foreach($roles as $role)
                    @php $hasRole = $client->hasRole($role->name); @endphp
                    <div class="col-12 col-sm-6 col-lg-4">
                        <label class="role-option {{ $hasRole ? 'selected' : '' }}">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                class="role-checkbox"
                                {{ $hasRole ? 'checked' : '' }}
                                onchange="this.closest('.role-option').classList.toggle('selected', this.checked)">
                            <div>
                                <div class="role-name">{{ $role->name }}</div>
                                <div class="role-count">{{ $role->permissions->count() }} permission{{ $role->permissions->count() > 1 ? 's' : '' }}</div>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button type="submit" class="btn-r-primary">
                <i class="bi bi-check-lg"></i> Enregistrer
            </button>
            <a href="{{ route('admin.entreprise.roles.clients') }}" class="btn-r-ghost">Annuler</a>
        </div>
    </form>

</div>
</div>
@endsection