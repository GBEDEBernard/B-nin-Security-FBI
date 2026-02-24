@extends('layouts.app')

@section('title', 'Mes Incidents - Client')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Mes Incidents</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.client.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Incidents</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Liste des Incidents</h5>
                <a href="{{ route('admin.client.incidents.create') }}" class="btn btn-danger">
                    <i class="bi bi-plus-circle me-1"></i> Signaler un incident
                </a>
            </div>
            <div class="card-body">
                <p class="text-muted">Liste de vos incidents en cours de développement.</p>
            </div>
        </div>
    </div>
</div>
@endsection