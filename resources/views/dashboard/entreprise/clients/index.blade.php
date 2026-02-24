@extends('layouts.app')

@section('title', 'Clients - Entreprise')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Gestion des Clients</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Clients</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Liste des Clients</h5>
                <a href="{{ route('dashboard.entreprise.clients.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nouveau client
                </a>
            </div>
            <div class="card-body">
                <p class="text-muted">Fonctionnalité en cours de développement.</p>
            </div>
        </div>
    </div>
</div>
@endsection