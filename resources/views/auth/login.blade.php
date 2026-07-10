@extends('layouts.guest')

@section('content')
<div class="card">
    <div class="card-header">{{ __('Login') }}</div>

    <div class="card-body">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-muted" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary py-2 fw-bold">
                    {{ __('Login') }}
                </button>
            </div>

            @if (Route::has('password.request'))
                <div class="text-center">
                    <a class="btn btn-link text-decoration-none" href="{{ route('password.request') }}" style="color: var(--ea-secondary); font-size: 0.875rem;">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </div>
            @endif
            
            @if (Route::has('register'))
                <div class="text-center mt-2">
                    <span class="text-muted small">Don't have an account?</span>
                    <a class="text-decoration-none" href="{{ route('register') }}" style="color: var(--ea-primary); font-size: 0.875rem;">
                        {{ __('Register') }}
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
