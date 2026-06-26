@extends('layouts.app')

@section('title', 'Mes Notifications')

@push('styles')
<style>
    :root {
        --n-bg:          #f8f9fb;
        --n-surface:     #ffffff;
        --n-border:      #e5e7eb;
        --n-text:        #111827;
        --n-text-muted:  #6b7280;
        --n-primary:     #2563eb;
        --n-primary-bg:  #eff6ff;
        --n-primary-dim: rgba(37,99,235,.12);
        --n-radius:      14px;
        --n-radius-sm:   8px;
        --n-shadow:      0 1px 4px rgba(0,0,0,.07), 0 4px 16px rgba(0,0,0,.05);
    }
    :root[data-bs-theme="dark"] {
        --n-bg:          #0f1117;
        --n-surface:     #1a1d27;
        --n-border:      #2a2d3a;
        --n-text:        #f0f2f8;
        --n-text-muted:  #8b90a8;
        --n-primary:     #60a5fa;
        --n-primary-bg:  rgba(96,165,250,.1);
        --n-primary-dim: rgba(96,165,250,.15);
        --n-shadow:      0 1px 4px rgba(0,0,0,.4), 0 4px 16px rgba(0,0,0,.3);
    }
    .n-page { background: var(--n-bg); min-height: 100vh; }
    .n-card {
        background: var(--n-surface);
        border: 1px solid var(--n-border);
        border-radius: var(--n-radius);
        box-shadow: var(--n-shadow);
        overflow: hidden;
    }
    .n-page-title { color: var(--n-text); font-size: 1.35rem; font-weight: 700; }
    .n-page-sub   { color: var(--n-text-muted); font-size: .875rem; }
    .n-item {
        display: flex; align-items: flex-start; gap: .75rem;
        padding: .85rem 1rem;
        border-bottom: 1px solid var(--n-border);
        transition: background .12s;
        color: var(--n-text);
        text-decoration: none;
    }
    .n-item:last-child { border-bottom: none; }
    .n-item:hover { background: var(--n-primary-bg); }
    .n-item.unread { background: var(--n-primary-bg); border-left: 3px solid var(--n-primary); }
    .n-icon {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: .9rem;
    }
    .n-message { font-size: .875rem; line-height: 1.4; }
    .n-time { font-size: .75rem; color: var(--n-text-muted); margin-top: 2px; }
    .n-empty { color: var(--n-text-muted); font-size: .9rem; text-align: center; padding: 3rem 1rem; }
    .n-empty i { font-size: 2.5rem; display: block; margin-bottom: .75rem; opacity: .5; }
</style>
@endpush

@section('content')
<div class="n-page">
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="n-page-title mb-1">Mes Notifications</h1>
            <p class="n-page-sub mb-0">Toutes vos notifications système.</p>
        </div>
        @php
        $markAllRoute = '';
        if (Auth::user() instanceof \App\Models\Employe) {
            $markAllRoute = Auth::user()->estAgent() ? 'admin.agent.mes-notifications.markAllRead' : 'admin.entreprise.mes-notifications.markAllRead';
        } elseif (Auth::user() instanceof \App\Models\Client) {
            $markAllRoute = 'admin.client.mes-notifications.markAllRead';
        } else {
            $markAllRoute = 'admin.superadmin.mes-notifications.markAllRead';
        }
        @endphp
        <form method="POST" action="{{ route($markAllRoute) }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-check2-all me-1"></i> Tout marquer comme lu
            </button>
        </form>
    </div>

    <div class="n-card">
        @forelse($notifications as $notif)
        @php
        $data = is_string($notif->donnees) ? json_decode($notif->donnees, true) : ($notif->donnees ?? []);
        $icon = $data['icon'] ?? 'bell';
        $color = $data['color'] ?? 'primary';
        $message = $data['message'] ?? $data['titre'] ?? 'Notification';
        $colors = ['primary' => 'var(--n-primary)', 'success' => '#16a34a', 'warning' => '#d97706', 'danger' => '#dc2626', 'info' => '#0891b2'];
        $bgColors = ['primary' => 'var(--n-primary-bg)', 'success' => 'rgba(22,163,74,.1)', 'warning' => 'rgba(217,119,6,.1)', 'danger' => 'rgba(220,38,38,.1)', 'info' => 'rgba(8,145,178,.1)'];
        $iconColor = $colors[$color] ?? $colors['primary'];
        $iconBg = $bgColors[$color] ?? $bgColors['primary'];
        @endphp
        <div class="n-item {{ $notif->lu_le ? '' : 'unread' }}">
            <div class="n-icon" style="background:{{ $iconBg }};color:{{ $iconColor }};">
                <i class="bi bi-{{ $icon }}"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="n-message">{{ $message }}</div>
                <div class="n-time">{{ $notif->created_at->diffForHumans() }}</div>
            </div>
            @unless($notif->lu_le)
            <button class="btn btn-sm btn-link text-muted mark-read" data-id="{{ $notif->id }}" title="Marquer comme lu">
                <i class="bi bi-check-lg"></i>
            </button>
            @endunless
        </div>
        @empty
        <div class="n-empty">
            <i class="bi bi-bell-slash"></i>
            Aucune notification pour le moment.
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="mt-3">{{ $notifications->links() }}</div>
    @endif

</div>
</div>
@endsection

@push('scripts')
<script>
const markReadBase = '{{ route($markAllRoute) }}'.replace('/mark-all-read', '');
document.querySelectorAll('.mark-read').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        fetch(markReadBase + '/' + id + '/read', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
            .then(() => {
                this.closest('.n-item').classList.remove('unread');
                this.remove();
            });
    });
});
</script>
@endpush
