@extends('layouts.app')

@section('title')
    Activités du module {{ $moduleLabel ?? $moduleType }}
@endsection

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.superadmin.journal.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h2 class="mb-0">
                    <i class="bi bi-boxes text-info me-2"></i>
                    Module : {{ $moduleLabel ?? $moduleType ?? 'Tous' }}
                </h2>
            </div>
            <p class="text-muted mb-0">{{ $activites->total() }} activité(s)</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Activités du module</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date/Heure</th>
                            <th>Utilisateur</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activites as $activite)
                            <tr>
                                <td class="text-nowrap"><small>{{ $activite->created_at?->format('d/m/Y H:i:s') }}</small></td>
                                <td><small>{{ $activite->causer_name }}</small></td>
                                <td><span class="badge bg-{{ $activite->action_badge }}">{{ $activite->action_label }}</span></td>
                                <td><small>{{ $activite->description }}</small></td>
                                <td><code class="small">{{ $activite->ip_address ?? '—' }}</code></td>
                                <td class="text-end">
                                    <a href="{{ route('admin.superadmin.journal.show', $activite->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Aucune activité pour ce module.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($activites->hasPages())
            <div class="card-footer bg-white">{{ $activites->links() }}</div>
        @endif
    </div>
</div>
@endsection
