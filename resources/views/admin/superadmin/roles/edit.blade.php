@extends('layouts.app')

@section('title', 'Modifier les Rôles - Super Admin')

@push('styles')
<style>
    /* Avatar Styles */
    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 24px;
        flex-shrink: 0;
    }

    /* Role Badges */
    .role-badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 500;
        margin: 3px;
        display: inline-block;
    }

    .role-super_admin {
        background: #dc3545;
        color: white;
    }

    .role-general_director {
        background: #6f42c1;
        color: white;
    }

    .role-deputy_director {
        background: #e83e8c;
        color: white;
    }

    .role-supervisor {
        background: #0dcaf0;
        color: white;
    }

    .role-controller {
        background: #20c997;
        color: white;
    }

    .role-agent {
        background: #198754;
        color: white;
    }

    .role-client_individual {
        background: #ffc107;
        color: #000;
    }

    .role-client_company {
        background: #fd7e14;
        color: white;
    }

    /* Permission Cards */
    .permission-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.2s;
    }

    .permission-card:hover {
        border-color: #198754;
        box-shadow: 0 2px 8px rgba(25, 135, 84, 0.1);
    }

    .permission-category {
        font-weight: 600;
        color: #198754;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }

    .permission-category i {
        margin-right: 8px;
    }

    /* Checkbox custom */
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
    }

    .form-check-label {
        cursor: pointer;
    }

    .form-check-label:hover {
        color: #198754;
    }

    /* Role Selection Cards */
    .role-card {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }

    .role-card:hover {
        border-color: #198754;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .role-card.selected {
        border-color: #198754;
        background: rgba(25, 135, 84, 0.05);
    }

    .role-card.selected::after {
        content: '\2713';
        position: absolute;
        top: 10px;
        right: 10px;
        width: 24px;
        height: 24px;
        background: #198754;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .role-card-title {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .role-card-desc {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .role-super_admin .role-card-title {
        color: #dc3545;
    }

    .role-general_director .role-card-title {
        color: #6f42c1;
    }

    .role-deputy_director .role-card-title {
        color: #e83e8c;
    }

    .role-supervisor .role-card-title {
        color: #0dcaf0;
    }

    .role-controller .role-card-title {
        color: #20c997;
    }

    .role-agent .role-card-title {
        color: #198754;
    }

    /* Info Box */
    .info-box {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
        border: 1px solid rgba(25, 135, 84, 0.2);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .info-box i {
        color: #198754;
    }

    /* Back Button */
    .back-btn {
        transition: all 0.2s;
    }

    .back-btn:hover {
        transform: translateX(-3px);
    }

    /* Current Roles */
    .current-roles {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .current-roles h6 {
        color: #6c757d;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeIn 0.3s ease forwards;
    }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-shield-lock-fill me-2"></i>Modifier les Rôles
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.superadmin.roles.index') }}">Rôles</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        <!-- Messages de session -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Back Button -->
        <a href="{{ route('admin.superadmin.roles.index') }}" class="btn btn-outline-secondary mb-3 back-btn">
            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
        </a>

        <form method="POST" action="{{ route('admin.superadmin.roles.update', ['id' => $model->id, 'type' => $type]) }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="type" value="{{ $type }}">

            <div class="row">
                <!-- Informations de l'utilisateur -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-fill me-2"></i>Informations
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            @if($type === 'employe')
                            @if($model->photo)
                            <img src="{{ asset('storage/' . $model->photo) }}" alt="Photo"
                                class="user-avatar mb-3" style="object-fit: cover;">
                            @else
                            <div class="user-avatar bg-success text-white mb-3 mx-auto">
                                {{ strtoupper(substr($model->prenoms, 0, 1) . substr($model->nom, 0, 1)) }}
                            </div>
                            @endif
                            <h5>{{ $model->prenoms }} {{ $model->nom }}</h5>
                            <p class="text-muted mb-1">{{ $model->email ?? 'N/A' }}</p>
                            <p class="text-muted mb-2">{{ $model->telephone ?? 'N/A' }}</p>
                            <span class="badge bg-success">{{ ucfirst($model->categorie ?? 'Employé') }}</span>
                            @if($model->entreprise)
                            <br><span class="badge bg-secondary mt-2">{{ $model->entreprise->nom_entreprise ?? $model->entreprise->nom }}</span>
                            @endif
                            @else
                            @if($model->photo)
                            <img src="{{ asset('storage/' . $model->photo) }}" alt="Photo"
                                class="user-avatar mb-3" style="object-fit: cover;">
                            @else
                            <div class="user-avatar bg-primary text-white mb-3 mx-auto">
                                {{ strtoupper(substr($model->name, 0, 2)) }}
                            </div>
                            @endif
                            <h5>{{ $model->name }}</h5>
                            <p class="text-muted mb-1">{{ $model->email }}</p>
                            @if($model->is_superadmin)
                            <span class="badge bg-danger">Super Admin</span>
                            @else
                            <span class="badge bg-secondary">Utilisateur</span>
                            @endif
                            @if($model->entreprise)
                            <br><span class="badge bg-secondary mt-2">{{ $model->entreprise->nom_entreprise ?? $model->entreprise->nom }}</span>
                            @endif
                            @endif

                            <hr>

                            <div class="current-roles text-start">
                                <h6><i class="bi bi-diagram-3 me-1"></i>Rôles actuels</h6>
                                @forelse($model->roles as $role)
                                <span class="role-badge role-{{ $role->name }}">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </span>
                                @empty
                                <span class="text-muted">Aucun rôle assigné</span>
                                @endforelse
                            </div>

                            <div class="text-start">
                                <h6><i class="bi bi-clock-history me-1"></i>Créé le</h6>
                                <p class="text-muted mb-0">{{ $model->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sélection des rôles -->
                <div class="col-md-8">
                    <!-- Info Box -->
                    <div class="info-box d-flex align-items-start">
                        <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                        <div>
                            <strong>Assignation des rôles</strong>
                            <p class="mb-0 text-muted small">
                                Sélectionnez les rôles à assigner à cet utilisateur.
                                Un utilisateur peut avoir plusieurs rôles.
                                Les permissions sont cumulatives.
                            </p>
                        </div>
                    </div>

                    <!-- Rôles disponibles -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-diagram-3-fill me-2"></i>Rôles disponibles
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($allRoles as $role)
                                <div class="col-md-6 mb-3">
                                    <div class="role-card role-{{ $role->name }} {{ in_array($role->name, $currentRoles) ? 'selected' : '' }}"
                                        onclick="toggleRole('{{ $role->name }}')">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                name="roles[]"
                                                value="{{ $role->name }}"
                                                id="role_{{ $role->name }}"
                                                {{ in_array($role->name, $currentRoles) ? 'checked' : '' }}>
                                            <label class="form-check-label role-card-title" for="role_{{ $role->name }}">
                                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                            </label>
                                        </div>
                                        <p class="role-card-desc mb-0 mt-1">
                                            {{ $role->description ?? 'Rôle ' . str_replace('_', ' ', $role->name) }}
                                        </p>
                                        @if($role->permissions->count() > 0)
                                        <small class="text-muted">
                                            <i class="bi bi-key me-1"></i>{{ $role->permissions->count() }} permission(s)
                                        </small>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Permissions individuelles -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-key-fill me-2"></i>Permissions individuelles
                                <span class="text-muted fw-normal">(optionnel)</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                            $groupedPermissions = $allPermissions->groupBy(function($permission) {
                            return explode('_', $permission->name)[0];
                            });
                            @endphp

                            @forelse($groupedPermissions as $group => $permissions)
                            <div class="permission-card">
                                <div class="permission-category">
                                    <i class="bi bi-{{ $group === 'view' ? 'eye' : ($group === 'create' ? 'plus-circle' : ($group === 'edit' ? 'pencil' : ($group === 'delete' ? 'trash' : 'gear'))) }}"></i>
                                    {{ ucfirst($group) }}
                                </div>
                                <div class="row">
                                    @foreach($permissions as $permission)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                name="permissions[]"
                                                value="{{ $permission->name }}"
                                                id="perm_{{ $permission->id }}"
                                                {{ in_array($permission->name, $currentPermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
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

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.superadmin.roles.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!--end::App Content-->

<script>
    function toggleRole(roleName) {
        const checkbox = document.getElementById('role_' + roleName);
        const card = checkbox.closest('.role-card');

        checkbox.checked = !checkbox.checked;

        if (checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    }

    // Auto-select/deselect all when clicking on card
    document.querySelectorAll('.role-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox') {
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                this.classList.toggle('selected', checkbox.checked);
            }
        });
    });
</script>
@endsection