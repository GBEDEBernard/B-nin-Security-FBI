@extends('layouts.app')

@section('title', 'Affectations - Entreprise')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Gestion des Affectations</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.entreprise.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Affectations</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <p class="text-muted">Liste des affectations en cours de développement.</p>
            </div>
        </div>
    </div>
</div>
@endsection