@extends('layouts.app')

@section('title', 'Gestion des Rôles - Entreprise')

@push('styles')
<style>
    /* ── Tokens adaptatifs light / dark ── */
    :root {
        --r-bg:         #f8f9fb;
        --r-surface:    #ffffff;
        --r-border:     #e5e7eb;
        --r-text:       #111827;
        --r-text-muted: #6b7280;
        --r-primary:    #2563eb;
        --r-primary-bg: #eff6ff;
        --r-primary-dim:rgba(37,99,235,.12);
        --r-info:       #0891b2;
        --r-info-bg:    rgba(8,145,178,.1);
        --r-shadow:     0 1px 4px rgba(0,0,0,.07), 0 4px 16px rgba(0,0,0,.05);
        --r-radius:     14px;
        --r-radius-sm:  8px;
    }
    @media (prefers-color-scheme: dark) {
        :root {
            --r-bg:         #0f1117;
            --r-surface:    #1a1d27;
            --r-border:     #2a2d3a;
            --r-text:       #f0f2f8;
            --r-text-muted: #8b90a8;
            --r-primary:    #60a5fa;
            --r-primary-bg: rgba(96,165,250,.1);
            --r-primary-dim:rgba(96,165,250,.15);
            --r-info:       #22d3ee;
            --r-info-bg:    rgba(34,211,238,.1);
            --r-shadow:     0 1px 4px rgba(0,0,0,.4), 0 4px 16px rgba(0,0,0,.3);
        }
    }

    .r-page    { background: var(--r-bg); min-height: 100vh; }

    /* ── Tabs ── */
    .r-tabs { display: flex; gap: 0; border-bottom: 1px solid var(--r-border); margin-bottom: 1.5rem; }
    .r-tab {
        padding: .65rem 1.1rem;
        font-size: .875rem; font-weight: 500;
        color: var(--r-text-muted);
        text-decoration: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        display: inline-flex; align-items: center; gap: .35rem;
        transition: color .15s, border-color .15s;
    }
    .r-tab:hover  { color: var(--r-primary); }
    .r-tab.active { color: var(--r-primary); border-bottom-color: var(--r-primary); }

    /* ── Carte tableau ── */
    .r-card {
        background: var(--r-surface);
        border: 1px solid var(--r-border);
        border-radius: var(--r-radius);
        box-shadow: var(--r-shadow);
        overflow: hidden;
    }

    /* ── Table ── */
    .r-table { width: 100%; border-collapse: collapse; }
    .r-table thead tr {
        background: var(--r-bg);
        border-bottom: 1px solid var(--r-border);
    }
    .r-table th {
        padding: .7rem 1rem;
        font-size: .72rem; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase;
        color: var(--r-text-muted);
        white-space: nowrap;
    }
    .r-table td {
        padding: .75rem 1rem;
        font-size: .875rem;
        color: var(--r-text);
        border-bottom: 1px solid var(--r-border);
        vertical-align: middle;
    }
    .r-table tbody tr:last-child td { border-bottom: none; }
    .r-table tbody tr { transition: background .12s; }
    .r-table tbody tr:hover { background: var(--r-primary-bg); }

    /* ── Avatar ── */
    .r-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .78rem; flex-shrink: 0;
        background: var(--r-primary-bg); color: var(--r-primary);
    }

    /* ── Texte ── */
    .r-page-title { color: var(--r-text); font-size: 1.35rem; font-weight: 700; }
    .r-page-sub   { color: var(--r-text-muted); font-size: .875rem; }
    .r-name       { color: var(--r-text); font-weight: 600; font-size: .875rem; }
    .r-meta       { color: var(--r-text-muted); font-size: .75rem; }
    .r-empty      { color: var(--r-text-muted); font-size: .85rem; }

    /* ── Badges ── */
    .r-badge {
        display: inline-block; font-size: .7rem; font-weight: 500;
        padding: 2px 9px; border-radius: 5px; margin: 1px;
        background: var(--r-primary-bg); color: var(--r-primary);
        border: 1px solid var(--r-primary-dim);
    }
    .r-badge-info {
        background: var(--r-info-bg); color: var(--r-info);
        border-color: transparent;
    }

    /* ── Bouton action ── */
    .btn-r-outline {
        background: transparent; color: var(--r-primary);
        border: 1px solid var(--r-primary);
        border-radius: var(--r-radius-sm); padding: .3rem .75rem;
        font-size: .8rem; font-weight: 500;
        display: inline-flex; align-items: center; gap: .3rem;
        text-decoration: none; transition: all .15s; white-space: nowrap;
    }
    .btn-r-outline:hover { background: var(--r-primary-bg); color: var(--r-primary); }

    /* ── Alerte flash ── */
    .r-alert {
        border-radius: var(--r-radius-sm); padding: .75rem 1rem;
        font-size: .875rem; display: flex; align-items: flex-start; gap: .5rem;
        border-left: 3px solid; margin-bottom: 1rem;
    }
    .r-alert-success { background: rgba(22,163,74,.08); color: #15803d; border-color: #16a34a; }
    @media (prefers-color-scheme: dark) {
        .r-alert-success { color: #4ade80; }
    }

    /* ── État vide ── */
    .r-empty-state {
        padding: 2.5rem 1rem; text-align: center; color: var(--r-text-muted);
    }
    .r-empty-state i { font-size: 2rem; display: block; margin-bottom: .5rem; opacity: .5; }
</style>
@endpush

@section('content')
<div class="r-page">
<div class="container-fluid px-4 py-4">

    {{-- En-tête --}}
    <div class="mb-4">
        <h1 class="r-page-title mb-1">Gestion des Rôles</h1>
        <p class="r-page-sub mb-0">Assignez et gérez les rôles de vos employés.</p>
    </div>

    {{-- Tabs --}}
    <nav class="r-tabs">
        <a href="{{ route('admin.entreprise.roles.index') }}" class="r-tab active">
            <i class="bi bi-people"></i> Employés
        </a>
        <a href="{{ route('admin.entreprise.roles.clients') }}" class="r-tab">
            <i class="bi bi-person-badge"></i> Clients
        </a>
    </nav>

    {{-- Flash --}}
    @if(session('success'))
    <div class="r-alert r-alert-success">
        <i class="bi bi-check-circle-fill flex-shrink-0"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Tableau --}}
    <div class="r-card">
        <div class="table-responsive">
            <table class="r-table">
                <thead>
                    <tr>
                        <th>Employé</th>
                        <th>Email</th>
                        <th>Catégorie</th>
                        <th>Poste</th>
                        <th>Rôles</th>
                        @can('manage_user_roles')
                        <th>Action</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse($employes as $employe)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="r-avatar">{{ $employe->initiales }}</div>
                                <div>
                                    <div class="r-name">{{ $employe->nomComplet }}</div>
                                    <div class="r-meta">{{ $employe->matricule }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="r-meta" style="font-size:.85rem;color:var(--r-text);">{{ $employe->email }}</td>
                        <td><span class="r-badge r-badge-info">{{ $employe->categorie }}</span></td>
                        <td style="color:var(--r-text-muted);font-size:.85rem;">{{ $employe->poste }}</td>
                        <td>
                            @forelse($employe->roles as $role)
                            <span class="r-badge">{{ $role->name }}</span>
                            @empty
                            <span class="r-empty">—</span>
                            @endforelse
                        </td>
                        @can('manage_user_roles')
                        <td>
                            <a href="{{ route('admin.entreprise.roles.edit', $employe->id) }}" class="btn-r-outline">
                                <i class="bi bi-shield"></i> Gérer
                            </a>
                        </td>
                        @endcan
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="r-empty-state">
                                <i class="bi bi-people"></i>
                                Aucun employé trouvé.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($employes->hasPages())
    <div class="mt-3">{{ $employes->links() }}</div>
    @endif

</div>
</div>
@endsection