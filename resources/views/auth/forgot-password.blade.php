@extends('layouts.auth')

@section('title', setting('app_name', 'InnovaCRM') . ' - Forgot Password')

@section('left-panel')
    <h1 class="hero-title">
        Account<br>
        <span class="text-gradient">Recovery.</span>
    </h1>

    <p class="hero-subtitle">
        Enter your registered email address to receive a secure 6-digit verification code to reset your password.
    </p>

    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <div class="feature-text">
                <h5>Secure Verification</h5>
                <p>Instant 6-digit OTP code to protect your account</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
            <div class="feature-text">
                <h5>Quick Recovery</h5>
                <p>Reset password in just a few simple steps</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-lock"></i></div>
            <div class="feature-text">
                <h5>Encrypted Data</h5>
                <p>End-to-end security for all account actions</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-headset"></i></div>
            <div class="feature-text">
                <h5>24/7 Support</h5>
                <p>Get help anytime if you encounter issues</p>
            </div>
        </div>
    </div>

    <!-- Trust Stats Banner -->
    <div class="left-trust-banner">
        <div class="trust-stat">
            <span class="stat-number">99.9%</span>
            <span class="stat-label">Uptime SLA</span>
        </div>
        <div class="trust-divider"></div>
        <div class="trust-stat">
            <span class="stat-number">10k+</span>
            <span class="stat-label">Active Users</span>
        </div>
        <div class="trust-divider"></div>
        <div class="trust-stat">
            <span class="stat-number">4.9/5</span>
            <span class="stat-label">User Rating</span>
        </div>
    </div>
@endsection

@section('content')
    <div class="text-center">
        <h3 class="fw-bold mb-1" style="font-size: 1.65rem;">Forgot Password?</h3>
        <p class="text-muted small mb-4">No worries! Enter your email and we'll send you a 6-digit verification code.</p>
    </div>

    @if ($errors->has('email'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" style="background-color: #FEF2F2; border-color: #FECACA; color: #991B1B; border-radius: 10px; font-size: 0.875rem;">
            <i class="fa-solid fa-circle-exclamation text-danger fs-6"></i>
            <div>{{ $errors->first('email') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label ps-1 text-secondary font-weight-500" style="font-size: 0.875rem;">Email address</label>
            <div class="light-input-group">
                <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="name@company.com">
            </div>
        </div>

        <button type="submit" class="btn-gradient mb-3">
            <i class="fa-solid fa-paper-plane me-2"></i> Send Verification Code
        </button>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </form>
@endsection
