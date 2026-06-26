@extends('layouts.app')

@section('title', 'Nouveau Rôle - Super Admin')

@push('styles')
<style>
    /* ── Tokens adaptatifs light / dark ── */
    :root {
        --r-bg:          #f8f9fb;
        --r-surface:     #ffffff;
        --r-surface-alt: #f3f4f6;
        --r-border:      #e5e7eb;
        --r-text:        #111827;
        --r-text-muted:  #6b7280;
        --r-primary:     #2563eb;
        --r-primary-bg:  #eff6ff;
        --r-primary-dim: rgba(37,99,235,.12);
        --r-shadow:      0 1px 4px rgba(0,0,0,.07), 0 4px 16px rgba(0,0,0,.05);
        --r-radius:      14px;
        --r-radius-sm:   8px;
        --r-input-bg:    #ffffff;
    }

    :root[data-bs-theme="dark"] {
            --r-bg:          #0f1117;
            --r-surface:     #1a1d27;
            --r-surface-alt: #22263a;
            --r-border:      #2a2d3a;
            --r-text:        #f0f2f8;
            --r-text-muted:  #8b90a8;
            --r-primary:     #60a5fa;
            --r-primary-bg:  rgba(96,165,250,.1);
            --r-primary-dim: rgba(96,165,250,.15);
            --r-shadow:      0 1px 4px rgba(0,0,0,.4), 0 4px 16px rgba(0,0,0,.3);
            --r-input-bg:    #0f1117;
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
        display: flex; align-items: center; justify-content: space-between;
        gap: .75rem;
    }
    .r-card-header-title {
        font-size: .875rem;
        font-weight: 600;
        color: var(--r-text);
    }
    .r-card-body { padding: 1.25rem; }

    /* ── Texte ── */
    .r-page-title { color: var(--r-text); font-size: 1.35rem; font-weight: 700; }
    .r-page-sub   { color: var(--r-text-muted); font-size: .875rem; }
    .r-label      { color: var(--r-text); font-size: .875rem; font-weight: 600; display: block; margin-bottom: .4rem; }
    .r-text-muted { color: var(--r-text-muted); }

    /* ── Input ── */
    .r-input {
        display: block; width: 100%;
        background: var(--r-input-bg);
        border: 1px solid var(--r-border);
        border-radius: var(--r-radius-sm);
        padding: .55rem .85rem;
        color: var(--r-text);
        font-size: .9rem;
        transition: border-color .15s, box-shadow .15s;
        outline: none;
    }
    .r-input::placeholder { color: var(--r-text-muted); }
    .r-input:focus {
        border-color: var(--r-primary);
        box-shadow: 0 0 0 3px var(--r-primary-dim);
    }
    .r-input.is-invalid { border-color: #dc2626; }
    .r-feedback-invalid { color: #dc2626; font-size: .8rem; margin-top: .3rem; }

    /* ── Groupes de permissions ── */
    .perm-group-label {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--r-text-muted);
        margin-bottom: .5rem;
    }
    .perm-divider { border-top: 1px solid var(--r-border); margin: 1rem 0; }

    .perm-item {
        display: flex; align-items: center; gap: .5rem;
        padding: .35rem .6rem;
        border-radius: 6px;
        cursor: pointer;
        transition: background .12s;
        color: var(--r-text);
        font-size: .82rem;
        line-height: 1.4;
    }
    .perm-item:hover { background: var(--r-primary-bg); }

    .perm-checkbox {
        width: 16px; height: 16px;
        accent-color: var(--r-primary);
        cursor: pointer;
        flex-shrink: 0;
    }

    /* ── Lien "tout sélectionner" ── */
    .r-select-all {
        font-size: .78rem;
        font-weight: 500;
        color: var(--r-primary);
        text-decoration: none;
        display: inline-flex; align-items: center; gap: .25rem;
        opacity: .85;
        transition: opacity .12s;
    }
    .r-select-all:hover { opacity: 1; color: var(--r-primary); }

    /* ── Boutons ── */
    .btn-r-primary {
        background: var(--r-primary);
        color: #fff;
        border: none;
        border-radius: var(--r-radius-sm);
        padding: .5rem 1.15rem;
        font-size: .875rem;
        font-weight: 500;
        display: inline-flex; align-items: center; gap: .4rem;
        cursor: pointer;
        transition: opacity .15s;
        text-decoration: none;
    }
    .btn-r-primary:hover { opacity: .88; color: #fff; }

    .btn-r-ghost {
        background: transparent;
        color: var(--r-text-muted);
        border: 1px solid var(--r-border);
        border-radius: var(--r-radius-sm);
        padding: .5rem 1rem;
        font-size: .875rem;
        font-weight: 500;
        display: inline-flex; align-items: center; gap: .4rem;
        transition: all .15s;
        text-decoration: none;
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
            <h1 class="r-page-title mb-1">Nouveau rôle</h1>
            <p class="r-page-sub mb-0">Créez un rôle et définissez ses permissions d'accès.</p>
        </div>
        <a href="{{ route('admin.superadmin.roles.index') }}" class="btn-r-ghost">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <form action="{{ route('admin.superadmin.roles.store') }}" method="POST">
        @csrf

        {{-- Nom du rôle --}}
        <div class="r-card mb-4">
            <div class="r-card-body">
                <label for="name" class="r-label">Nom du rôle</label>
                <input type="text" name="name" id="name"
                    class="r-input @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    placeholder="ex : manager, chef_equipe, …"
                    autocomplete="off"
                    required>
                @error('name')
                <p class="r-feedback-invalid">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Permissions --}}
        <div class="r-card mb-4">
            <div class="r-card-header">
                <span class="r-card-header-title"><i class="bi bi-key me-1"></i>Permissions</span>
                <a href="#" class="r-select-all" onclick="toggleAllPermissions(event)">
                    <i class="bi bi-check2-square"></i> Tout sélectionner
                </a>
            </div>
            <div class="r-card-body">
                @foreach($permissions as $group => $groupPermissions)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="perm-group-label">{{ $group }}</span>
                        <a href="#" class="r-select-all" onclick="toggleGroup(event, '{{ $group }}')">
                            <i class="bi bi-check2-square"></i> Tout
                        </a>
                    </div>
                    <div class="row g-1">
                        @foreach($groupPermissions as $permission)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <label class="perm-item">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                    class="perm-checkbox group-{{ $group }}"
                                    {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                <span>{{ $permission->name }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @if(!$loop->last)
                <div class="perm-divider"></div>
                @endif
                @endforeach
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex flex-wrap gap-2">
            <button type="submit" class="btn-r-primary">
                <i class="bi bi-check-lg"></i> Créer le rôle
            </button>
            <a href="{{ route('admin.superadmin.roles.index') }}" class="btn-r-ghost">Annuler</a>
        </div>

    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
function toggleGroup(event, group) {
    event.preventDefault();
    const boxes = document.querySelectorAll('.group-' + CSS.escape(group));
    const allChecked = [...boxes].every(c => c.checked);
    boxes.forEach(c => c.checked = !allChecked);
}
function toggleAllPermissions(event) {
    event.preventDefault();
    const boxes = document.querySelectorAll('.perm-checkbox');
    const allChecked = [...boxes].every(c => c.checked);
    boxes.forEach(c => c.checked = !allChecked);
}
</script>
@endpush