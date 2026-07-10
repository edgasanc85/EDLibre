@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-dark fw-bold">Dashboard</h1>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2 shadow-sm" style="border-left: 4px solid var(--ea-primary);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-uppercase mb-1" style="color: var(--ea-primary);">
                                Estado</div>
                            <div class="h5 mb-0 fw-bold text-white">{{ __('You are logged in!') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-check fs-2 text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-9 col-md-12 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header pt-4 pb-0 text-white fw-bold border-0">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2" style="color: var(--ea-primary);"></i>Bienvenido, {{ Auth::user()->name }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Este es tu panel de control principal. Aquí podrás acceder a todas las funcionalidades del sistema a través del menú lateral.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
