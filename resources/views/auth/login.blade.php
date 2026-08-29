@extends('layouts.auth')

@section('title', setting('app_name', 'InnovaCRM') . ' - Login')

@section('left-panel')
    <h1 class="hero-title">
        Build stronger<br>
        <span class="text-gradient">relationships.</span>
    </h1>

    <p class="hero-subtitle">
        InnovaCRM helps you manage leads, close deals, and grow your business all in one place.
    </p>

    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-users"></i></div>
            <div class="feature-text">
                <h5>Manage Contacts</h5>
                <p>Organize and track your customer relationships</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-chart-line"></i></div>
            <div class="feature-text">
                <h5>Track Your Pipeline</h5>
                <p>Visualize deals and close more, faster</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-chart-pie"></i></div>
            <div class="feature-text">
                <h5>Real-time Insights</h5>
                <p>Make data-driven decisions with confidence</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <div class="feature-text">
                <h5>Secure & Reliable</h5>
                <p>Enterprise-grade security you can trust</p>
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
        <h3>Welcome back 👋</h3>
        <p class="subtitle">Sign in to your InnovaCRM account</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3" style="background-color: #ECFDF5; border-color: #A7F3D0; color: #065F46; border-radius: 10px; font-size: 0.875rem;">
            <i class="fa-solid fa-circle-check text-success fs-6"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label ps-2">Email address</label>
            <div class="light-input-group">
                <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    autofocus autocomplete="username" placeholder="Enter your email">
            </div>
            @error('email')
                <div class="text-danger small mt-1" style="color: #DC2626 !important;">{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label ps-2">Password</label>
            <div class="light-input-group">
                <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                <input type="password" id="password" name="password" autocomplete="current-password"
                    placeholder="Enter your password">
                <button type="button" class="toggle-password" id="togglePassword"
                    aria-label="Toggle password visibility"><i class="fa-regular fa-eye"></i></button>
            </div>
            @error('password')
                <div class="text-danger small mt-1" style="color: #DC2626 !important;">{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Options -->
        <div class="options-row mb-3 pb-1">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-gradient">Sign in</button>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('togglePassword');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const input = document.getElementById('password');
                    if (!input) return;
                    input.type = input.type === 'password' ? 'text' : 'password';

                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                });
            }
        });
    </script>
@endpush
