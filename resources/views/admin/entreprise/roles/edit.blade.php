@extends('layouts.app')

@section('title', 'Gérer les Rôles - Entreprise')

@push('styles')
<style>
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .role-option {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 16px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .role-option:hover {
        border-color: #0d6efd;
        background: rgba(13,110,253,0.03);
    }
    .role-option.selected {
        border-color: #0d6efd;
        background: rgba(13,110,253,0.06);
    }
    .role-option input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Gérer les Rôles</h4>
            <p class="text-muted mb-0">{{ $employe->nomComplet }} — {{ $employe->poste }}</p>
        </div>
        <a href="{{ route('admin.entreprise.roles.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <form action="{{ route('admin.entreprise.roles.update', $employe->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="card card-custom mb-4">
            <div class="card-header bg-transparent fw-semibold">
                <i class="bi bi-shield-check me-1"></i> Rôles disponibles
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Sélectionnez les rôles à attribuer à <strong>{{ $employe->nomComplet }}</strong>.
                    Un employé peut avoir plusieurs rôles.
                </p>
                <div class="row g-3">
                    @foreach($roles as $role)
                    @php
                    $hasRole = $employe->hasRole($role->name);
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <label class="role-option d-flex align-items-center gap-3 {{ $hasRole ? 'selected' : '' }}">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                {{ $hasRole ? 'checked' : '' }}
                                onchange="this.closest('.role-option').classList.toggle('selected')">
                            <div>
                                <div class="fw-semibold">{{ $role->name }}</div>
                                <small class="text-muted">{{ $role->permissions->count() }} permissions</small>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i> Enregistrer
            </button>
            <a href="{{ route('admin.entreprise.roles.index') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
