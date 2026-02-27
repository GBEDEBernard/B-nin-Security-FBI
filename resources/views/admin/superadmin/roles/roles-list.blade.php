@extends('layouts.app')

@section('title', 'Liste des Rôles - Super Admin')

@push('styles')
<style>
    .role-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .role-card:hover {
        border-color: #198754;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .role-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .permission-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        margin: 2px;
        border-radius: 4px;
        background: #f8f9fa;
        color: #495057;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-diagram-3-fill me-2"></i>Liste des Rôles</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.roles.index') }}">Rôles</a></li>
                    <li class="breadcrumb-item active">Liste</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            @forelse($roles as $role)
            <div class="col-md-6">
                <div class="role-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="role-icon bg-{{ $loop->index % 2 == 0 ? 'primary' : 'success' }} text-white me-3">
                                <i class="bi bi-shield-fill"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</h5>
                                <small class="text-muted">{{ $role->permissions->count() }} permission(s)</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-2">
                        @forelse($role->permissions->take(10) as $permission)
                        <span class="permission-badge">{{ str_replace('_', ' ', $permission->name) }}</span>
                        @empty
                        <span class="text-muted">Aucune permission</span>
                        @endforelse
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">Aucun rôle disponible.</div>
            </div>
            @endforelse
        </div>

        <a href="{{ route('admin.superadmin.roles.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>
</div>
@endsection