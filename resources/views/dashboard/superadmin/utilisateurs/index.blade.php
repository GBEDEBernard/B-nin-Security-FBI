@extends('layouts.app')

@section('title', 'Utilisateurs - Super Admin')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Gestion des Utilisateurs</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Utilisateurs</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Liste des Utilisateurs</h5>
                <a href="{{ route('dashboard.superadmin.utilisateurs.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nouvel utilisateur
                </a>
            </div>
            <div class="card-body">
                <p class="text-muted">Fonctionnalité en cours de développement.</p>
            </div>
        </div>
    </div>
</div>
@endsection