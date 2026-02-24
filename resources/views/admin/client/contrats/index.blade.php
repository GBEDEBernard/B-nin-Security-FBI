@extends('layouts.app')

@section('title', 'Mes Contrats - Client')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Mes Contrats</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.client.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Contrats</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <p class="text-muted">Liste de vos contrats en cours de développement.</p>
            </div>
        </div>
    </div>
</div>
@endsection