@extends('layouts.app')

@section('title', 'Entreprises - Super Admin')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Gestion des Entreprises</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.superadmin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Entreprises</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Liste des Entreprises</h5>
                <a href="{{ route('dashboard.superadmin.entreprises.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nouvelle entreprise
                </a>
            </div>
            <div class="card-body">
                <p class="text-muted">Fonctionnalité en cours de développement.</p>
            </div>
        </div>
    </div>
</div>
@endsection