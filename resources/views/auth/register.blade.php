@extends('layouts.guest')

@section('content')
<div class="card">
    <div class="card-header">{{ __('Register') }}</div>

    <div class="card-body">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                    <select id="tipo_documento" class="form-select form-control @error('tipo_documento') is-invalid @enderror" name="tipo_documento" required>
                        <option value="">Seleccione...</option>
                        <option value="Cédula Ciudadanía" {{ old('tipo_documento') == 'Cédula Ciudadanía' ? 'selected' : '' }}>Cédula Ciudadanía</option>
                        <option value="Cédula Extranjería" {{ old('tipo_documento') == 'Cédula Extranjería' ? 'selected' : '' }}>Cédula Extranjería</option>
                        <option value="Pasaporte" {{ old('tipo_documento') == 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                    </select>
                    @error('tipo_documento')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="numero_documento" class="form-label">Número de Documento</label>
                    <input id="numero_documento" type="text" class="form-control @error('numero_documento') is-invalid @enderror" name="numero_documento" value="{{ old('numero_documento') }}" required>
                    @error('numero_documento')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary py-2 fw-bold">
                    {{ __('Register') }}
                </button>
            </div>
            
            @if (Route::has('login'))
                <div class="text-center mt-2">
                    <span class="text-muted small">Already have an account?</span>
                    <a class="text-decoration-none" href="{{ route('login') }}" style="color: var(--ea-primary); font-size: 0.875rem;">
                        {{ __('Login') }}
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
