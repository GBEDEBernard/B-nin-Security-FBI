@extends('layouts.app')

@section('title', 'Détails du Rôle - Super Admin')

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
        --r-warning:     #d97706;
        --r-danger:      #dc2626;
        --r-danger-bg:   #fef2f2;
        --r-shadow:      0 1px 4px rgba(0,0,0,.07), 0 4px 16px rgba(0,0,0,.05);
        --r-radius:      14px;
        --r-radius-sm:   8px;
        --r-input-bg:    #ffffff;
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
            --r-warning:     #fbbf24;
            --r-danger:      #f87171;
            --r-danger-bg:   rgba(248,113,113,.1);
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
        display: flex; align-items: center; gap: .5rem;
    }
    .r-card-header-title { font-size: .875rem; font-weight: 600; color: var(--r-text); flex-grow: 1; }
    .r-card-body { padding: 1.25rem; }

    /* ── Texte ── */
    .r-page-title { color: var(--r-text); font-size: 1.35rem; font-weight: 700; }
    .r-page-sub   { color: var(--r-text-muted); font-size: .875rem; }
    .r-text-muted { color: var(--r-text-muted); font-size: .8rem; }
    .r-label { color: var(--r-text); font-size: .875rem; font-weight: 600; display: block; margin-bottom: .4rem; }

    /* ── Badges permissions ── */
    .perm-badge {
        display: inline-block;
        font-size: .72rem; font-weight: 500;
        padding: 3px 10px; border-radius: 6px;
        margin: 2px 2px 2px 0;
        background: var(--r-primary-bg);
        color: var(--r-primary);
        border: 1px solid var(--r-primary-dim);
    }
    .perm-group-label {
        font-size: .7rem; font-weight: 700; letter-spacing: .06em;
        text-transform: uppercase; color: var(--r-text-muted);
        margin-bottom: .4rem;
    }
    .perm-divider { border-top: 1px solid var(--r-border); margin: .875rem 0; }

    /* ── Avatar utilisateur ── */
    .user-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .78rem;
        background: var(--r-primary-bg); color: var(--r-primary);
        flex-shrink: 0;
    }
    .user-name  { color: var(--r-text); font-size: .875rem; font-weight: 500; }
    .user-email { color: var(--r-text-muted); font-size: .75rem; }

    /* ── Séparateur ── */
    .r-hr { border-top: 1px solid var(--r-border); margin: 1rem 0; }

    /* ── Input / select ── */
    .r-input, .r-select {
        display: block; width: 100%;
        background: var(--r-input-bg);
        border: 1px solid var(--r-border);
        border-radius: var(--r-radius-sm);
        padding: .5rem .85rem;
        color: var(--r-text);
        font-size: .875rem;
        transition: border-color .15s, box-shadow .15s;
        outline: none;
    }
    .r-input:focus, .r-select:focus {
        border-color: var(--r-primary);
        box-shadow: 0 0 0 3px var(--r-primary-dim);
    }

    /* ── Boutons ── */
    .btn-r-primary {
        background: var(--r-primary); color: #fff; border: none;
        border-radius: var(--r-radius-sm); padding: .45rem 1rem;
        font-size: .85rem; font-weight: 500;
        display: inline-flex; align-items: center; gap: .35rem;
        cursor: pointer; transition: opacity .15s; text-decoration: none; width: 100%;
        justify-content: center;
    }
    .btn-r-primary:hover { opacity: .88; color: #fff; }

    .btn-r-ghost {
        background: transparent; color: var(--r-text-muted);
        border: 1px solid var(--r-border);
        border-radius: var(--r-radius-sm); padding: .45rem 1rem;
        font-size: .85rem; font-weight: 500;
        display: inline-flex; align-items: center; gap: .35rem;
        transition: all .15s; text-decoration: none;
    }
    .btn-r-ghost:hover { color: var(--r-text); border-color: var(--r-text-muted); }

    .btn-r-warn {
        background: transparent; color: var(--r-warning);
        border: 1px solid currentColor;
        border-radius: var(--r-radius-sm); padding: .45rem 1rem;
        font-size: .85rem; font-weight: 500;
        display: inline-flex; align-items: center; gap: .35rem;
        transition: all .15s; text-decoration: none;
    }
    .btn-r-warn:hover { background: rgba(217,119,6,.08); color: var(--r-warning); }

    .btn-r-danger-sm {
        background: transparent; color: var(--r-danger);
        border: 1px solid currentColor;
        border-radius: var(--r-radius-sm); padding: .25rem .55rem;
        font-size: .8rem; cursor: pointer;
        display: inline-flex; align-items: center;
        transition: all .15s;
    }
    .btn-r-danger-sm:hover { background: var(--r-danger-bg); color: var(--r-danger); }

    /* ── Alertes ── */
    .r-alert {
        border-radius: var(--r-radius-sm); padding: .75rem 1rem;
        font-size: .875rem; display: flex; align-items: flex-start; gap: .5rem;
        border-left: 3px solid; margin-bottom: 1rem;
    }
    .r-alert-success { background: rgba(22,163,74,.08); color: #15803d; border-color: #16a34a; }
    .r-alert-danger   { background: var(--r-danger-bg); color: var(--r-danger); border-color: var(--r-danger); }
    [data-bs-theme="dark"] .r-alert-success { color: #4ade80; border-color: #4ade80; }

    /* ── Texte vide ── */
    .r-empty { color: var(--r-text-muted); font-size: .875rem; padding: .25rem 0; }
</style>
@endpush

@section('content')
<div class="r-page">
<div class="container-fluid px-4 py-4">

    {{-- En-tête --}}
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div style="width:46px;height:46px;border-radius:12px;background:var(--r-primary-bg);color:var(--r-primary);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <h1 class="r-page-title mb-0">{{ $role->name }}</h1>
                <p class="r-page-sub mb-0">{{ $role->permissions->count() }} permission{{ $role->permissions->count() > 1 ? 's' : '' }} · {{ $role->users->count() }} utilisateur{{ $role->users->count() > 1 ? 's' : '' }}</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            @can('manage_user_roles')
            @if($role->name !== 'super_admin')
            <a href="{{ route('admin.superadmin.roles.edit', $role->id) }}" class="btn-r-warn">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            @endif
            @endcan
            <a href="{{ route('admin.superadmin.roles.index') }}" class="btn-r-ghost">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="r-alert r-alert-success"><i class="bi bi-check-circle-fill flex-shrink-0"></i><span>{{ session('success') }}</span></div>
    @endif
    @if(session('error'))
    <div class="r-alert r-alert-danger"><i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i><span>{{ session('error') }}</span></div>
    @endif

    <div class="row g-4">

        {{-- Colonne principale : permissions --}}
        <div class="col-12 col-lg-8">
            <div class="r-card">
                <div class="r-card-header">
                    <i class="bi bi-key" style="color:var(--r-primary);"></i>
                    <span class="r-card-header-title">Permissions</span>
                    <span class="r-text-muted">{{ $role->permissions->count() }} au total</span>
                </div>
                <div class="r-card-body">
                    @php
                        $grouped = $role->permissions->groupBy(fn($p) => explode('_', $p->name)[0] ?? 'autres');
                    @endphp
                    @foreach($grouped as $group => $perms)
                    <div class="mb-3">
                        <p class="perm-group-label">{{ $group }}</p>
                        <div>
                            @foreach($perms as $perm)
                            <span class="perm-badge">{{ $perm->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @if(!$loop->last)<div class="perm-divider"></div>@endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Colonne secondaire : utilisateurs + assigner --}}
        <div class="col-12 col-lg-4 d-flex flex-column gap-4">

            {{-- Utilisateurs --}}
            <div class="r-card">
                <div class="r-card-header">
                    <i class="bi bi-people" style="color:var(--r-primary);"></i>
                    <span class="r-card-header-title">Utilisateurs</span>
                    <span class="r-text-muted">{{ $role->users->count() }}</span>
                </div>
                <div class="r-card-body">
                    @if($role->users->count())
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                        @foreach($role->users as $user)
                        <li class="d-flex align-items-center gap-2">
                            <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="user-name text-truncate">{{ $user->name }}</div>
                                <div class="user-email text-truncate">{{ $user->email }}</div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="r-empty mb-0">Aucun utilisateur assigné.</p>
                    @endif
                </div>
            </div>

            {{-- Assigner / retirer --}}
            @can('manage_user_roles')
            <div class="r-card">
                <div class="r-card-header">
                    <i class="bi bi-person-plus" style="color:var(--r-primary);"></i>
                    <span class="r-card-header-title">Assigner à un utilisateur</span>
                </div>
                <div class="r-card-body">
                    <form action="{{ route('admin.superadmin.roles.assign') }}" method="POST">
                        @csrf
                        <input type="hidden" name="role_id" value="{{ $role->id }}">
                        <label class="r-label" for="user_id_assign">Utilisateur</label>
                        <select name="user_id" id="user_id_assign" class="r-select mb-3" required>
                            <option value="">— Sélectionner —</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $user->hasRole($role->name) ? 'disabled' : '' }}>
                                {{ $user->name }} ({{ $user->email }}){{ $user->hasRole($role->name) ? ' — déjà assigné' : '' }}
                            </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-r-primary">
                            <i class="bi bi-plus-lg"></i> Assigner ce rôle
                        </button>
                    </form>

                    @if($role->users->count())
                    <div class="r-hr"></div>
                    <p class="r-label mb-2">Retirer le rôle</p>
                    <div class="d-flex flex-column gap-2">
                        @foreach($role->users as $user)
                        <form action="{{ route('admin.superadmin.roles.remove') }}" method="POST"
                            class="d-flex justify-content-between align-items-center gap-2">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="role_id" value="{{ $role->id }}">
                            <span style="font-size:.85rem;color:var(--r-text);min-width:0;" class="text-truncate flex-grow-1">{{ $user->name }}</span>
                            <button type="submit" class="btn-r-danger-sm flex-shrink-0"
                                onclick="return confirm('Retirer le rôle de {{ $user->name }} ?')"
                                title="Retirer">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </form>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endcan

        </div>
    </div>
</div>
</div>
@endsection