@extends('layouts.app')

@section('title', 'Nouveau Rôle - Super Admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-plus-circle text-primary me-2"></i>
                Nouveau Rôle
            </h2>
            <p class="text-muted mb-0">Créez un nouveau rôle avec ses permissions</p>
        </div>
        <a href="{{ route('admin.superadmin.roles.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <form action="{{ route('admin.superadmin.roles.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Informations</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nom du rôle <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="ex: gestionnaire" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                rows="3" placeholder="Description optionnelle du rôle">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Permissions</h5>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-success" id="selectAllPerms">
                                <i class="bi bi-check-all me-1"></i> Tout sélectionner
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="deselectAllPerms">
                                <i class="bi bi-x-lg me-1"></i> Tout désélectionner
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                        @forelse($permissions as $group => $groupPerms)
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted fw-bold border-bottom pb-2 mb-3">
                                <i class="bi bi-folder me-1"></i> {{ ucfirst($group) }}
                                <button type="button" class="btn btn-sm btn-link text-decoration-none select-group" data-group="{{ $group }}">
                                    <small>Tout</small>
                                </button>
                            </h6>
                            <div class="row">
                                @foreach($groupPerms as $permission)
                                <div class="col-md-6 mb-1">
                                    <div class="form-check">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                            class="form-check-input perm-checkbox perm-group-{{ $group }}"
                                            id="perm-{{ $permission->id }}"
                                            {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm-{{ $permission->id }}">
                                            <code>{{ $permission->name }}</code>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">Aucune permission disponible</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-check-lg me-1"></i> Créer le rôle
            </button>
            <a href="{{ route('admin.superadmin.roles.index') }}" class="btn btn-outline-secondary btn-lg ms-2">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('selectAllPerms')?.addEventListener('click', function() {
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = true);
    });
    document.getElementById('deselectAllPerms')?.addEventListener('click', function() {
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = false);
    });
    document.querySelectorAll('.select-group').forEach(btn => {
        btn.addEventListener('click', function() {
            const group = this.dataset.group;
            const checkboxes = document.querySelectorAll('.perm-group-' + group);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
        });
    });
});
</script>
@endpush
