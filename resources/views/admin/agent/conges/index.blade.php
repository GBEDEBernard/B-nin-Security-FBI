@extends('layouts.app')

@section('title', 'Mes Congés - Agent')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Mes Congés</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.agent.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Congés</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <p class="text-muted">Gestion de vos congés en cours de développement.</p>
            </div>
        </div>
    </div>
</div>
@endsection