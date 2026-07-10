@extends('layouts.guest')

@section('content')
<div class="card">
    <div class="card-header">{{ __('Verify Your Email Address') }}</div>

    <div class="card-body text-center">
        @if (session('resent'))
            <div class="alert alert-success" role="alert" style="background-color: rgba(16, 185, 129, 0.1); border-color: var(--ea-success); color: var(--ea-success);">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif

        <p class="text-muted mb-3">
            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }},
        </p>
        
        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-decoration-none fw-bold" style="color: var(--ea-primary);">{{ __('click here to request another') }}</button>.
        </form>
    </div>
</div>
@endsection
