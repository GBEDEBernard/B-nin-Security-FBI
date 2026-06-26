@extends('layouts.app')

@section('title', 'Gestion des Rôles - Super Admin')

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
    }

    /* ── Layout ── */
    .r-page { background: var(--r-bg); min-height: 100vh; }

    /* ── Cards ── */
    .role-card {
        background: var(--r-surface);
        border: 1px solid var(--r-border);
        border-radius: var(--r-radius);
        box-shadow: var(--r-shadow);
        transition: transform .18s ease, box-shadow .18s ease;
        height: 100%;
    }
    .role-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 20px rgba(0,0,0,.12);
    }

    /* ── Role icon ── */
    .role-icon {
        width: 46px; height: 46px;
        border-radius: var(--r-radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        background: var(--r-primary-bg);
        color: var(--r-primary);
        flex-shrink: 0;
    }

    /* ── Texte ── */
    .r-title { color: var(--r-text); font-size: 1rem; font-weight: 600; line-height: 1.3; }
    .r-meta  { color: var(--r-text-muted); font-size: .8rem; margin-top: 2px; }
    .r-page-title { color: var(--r-text); font-size: 1.35rem; font-weight: 700; }
    .r-page-sub   { color: var(--r-text-muted); font-size: .875rem; }

    /* ── Badges de permissions ── */
    .perm-badge {
        display: inline-block;
        font-size: .7rem;
        font-weight: 500;
        padding: 2px 9px;
        border-radius: 5px;
        margin: 2px 2px 2px 0;
        background: var(--r-primary-bg);
        color: var(--r-primary);
        border: 1px solid var(--r-primary-dim);
        white-space: nowrap;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .perm-badge-more {
        background: var(--r-border);
        color: var(--r-text-muted);
        border-color: transparent;
    }

    /* ── Séparateur card ── */
    .r-divider { border-top: 1px solid var(--r-border); margin: .875rem 0 .75rem; }

    /* ── Boutons ── */
    .btn-r-primary {
        background: var(--r-primary);
        color: #fff;
        border: none;
        border-radius: var(--r-radius-sm);
        padding: .45rem 1rem;
        font-size: .85rem;
        font-weight: 500;
        display: inline-flex; align-items: center; gap: .35rem;
        transition: opacity .15s;
        text-decoration: none;
    }
    .btn-r-primary:hover { opacity: .88; color: #fff; }

    .btn-r-ghost {
        background: transparent;
        color: var(--r-text-muted);
        border: 1px solid var(--r-border);
        border-radius: var(--r-radius-sm);
        padding: .35rem .75rem;
        font-size: .8rem;
        font-weight: 500;
        display: inline-flex; align-items: center; gap: .3rem;
        transition: all .15s;
        text-decoration: none;
    }
    .btn-r-ghost:hover { color: var(--r-text); border-color: var(--r-text-muted); }

    .btn-r-warn {
        background: transparent;
        color: var(--r-warning);
        border: 1px solid currentColor;
        border-radius: var(--r-radius-sm);
        padding: .3rem .6rem;
        font-size: .8rem;
        display: inline-flex; align-items: center;
        transition: all .15s;
        text-decoration: none;
    }
    .btn-r-warn:hover { background: rgba(217,119,6,.08); color: var(--r-warning); }

    .btn-r-danger {
        background: transparent;
        color: var(--r-danger);
        border: 1px solid currentColor;
        border-radius: var(--r-radius-sm);
        padding: .3rem .6rem;
        font-size: .8rem;
        display: inline-flex; align-items: center;
        transition: all .15s;
    }
    .btn-r-danger:hover { background: var(--r-danger-bg); color: var(--r-danger); }

    /* ── Système badge ── */
    .badge-system {
        background: var(--r-danger-bg);
        color: var(--r-danger);
        border: 1px solid rgba(220,38,38,.2);
        font-size: .7rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: .03em;
    }

    /* ── Alertes ── */
    .r-alert {
        border-radius: var(--r-radius-sm);
        padding: .75rem 1rem;
        font-size: .875rem;
        display: flex; align-items: flex-start; gap: .5rem;
        border-left: 3px solid;
        margin-bottom: 1rem;
    }
    .r-alert-success { background: rgba(22,163,74,.08); color: #15803d; border-color: #16a34a; }
    .r-alert-danger   { background: var(--r-danger-bg); color: var(--r-danger); border-color: var(--r-danger); }
    [data-bs-theme="dark"] .r-alert-success { color: #4ade80; border-color: #4ade80; }
    [data-bs-theme="dark"] .r-alert-danger  { color: #f87171; }
</style>
@endpush

@section('content')
<div class="r-page">
<div class="container-fluid px-4 py-4">

    {{-- En-tête --}}
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="r-page-title mb-1">Rôles &amp; Permissions</h1>
            <p class="r-page-sub mb-0">Gérez les rôles et leurs accès dans le système.</p>
        </div>
        @can('manage_user_roles')
        <a href="{{ route('admin.superadmin.roles.create') }}" class="btn-r-primary">
            <i class="bi bi-plus-lg"></i> Nouveau rôle
        </a>
        @endcan
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="r-alert r-alert-success">
        <i class="bi bi-check-circle-fill flex-shrink-0"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="r-alert r-alert-danger">
        <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Grille de rôles --}}
    <div class="row g-3">
        @foreach($roles as $role)
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="role-card p-3 d-flex flex-column">

                {{-- Titre rôle --}}
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="role-icon"><i class="bi bi-shield-check"></i></div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="r-title text-truncate">{{ $role->name }}</div>
                        <div class="r-meta">{{ $role->permissions->count() }} permission{{ $role->permissions->count() > 1 ? 's' : '' }}</div>
                    </div>
                </div>

                {{-- Permissions preview --}}
                <div class="mb-2 flex-grow-1">
                    @foreach($role->permissions->take(5) as $perm)
                    <span class="perm-badge">{{ $perm->name }}</span>
                    @endforeach
                    @if($role->permissions->count() > 5)
                    <span class="perm-badge perm-badge-more">+{{ $role->permissions->count() - 5 }}</span>
                    @endif
                </div>

                <div class="r-divider"></div>

                {{-- Actions --}}
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <a href="{{ route('admin.superadmin.roles.show', $role->id) }}" class="btn-r-ghost">
                        <i class="bi bi-eye"></i> Détails
                    </a>
                    <div class="d-flex align-items-center gap-1">
                        @can('manage_user_roles')
                            @if($role->name !== 'super_admin')
                            <a href="{{ route('admin.superadmin.roles.edit', $role->id) }}" class="btn-r-warn" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.superadmin.roles.destroy', $role->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Supprimer le rôle « {{ $role->name }} » ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-r-danger" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @else
                            <span class="badge-system">Système</span>
                            @endif
                        @endcan
                    </div>
                </div>

            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($roles->hasPages())
    <div class="mt-4">{{ $roles->links() }}</div>
    @endif

</div>
</div>
@endsection