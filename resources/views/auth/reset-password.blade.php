@extends('layouts.auth')

@section('title', setting('app_name', 'InnovaCRM') . ' - Reset Password')

@section('left-panel')
    <h1 class="hero-title">
        Set New<br>
        <span class="text-gradient">Password.</span>
    </h1>

    <p class="hero-subtitle">
        Create a strong, unique password for your account to ensure optimal security.
    </p>

    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <div class="feature-text">
                <h5>Strong Encryption</h5>
                <p>Advanced hashing algorithms protect your credentials</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-mobile-screen-button"></i></div>
            <div class="feature-text">
                <h5>Multi-Factor Ready</h5>
                <p>Seamless compatibility with 2FA protection</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-key"></i></div>
            <div class="feature-text">
                <h5>Real-Time Security</h5>
                <p>Instant credentials update across all sessions</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-user-shield"></i></div>
            <div class="feature-text">
                <h5>Account Protection</h5>
                <p>Proactive prevention against unauthorized access</p>
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
        <h3 class="fw-bold mb-1" style="font-size: 1.65rem;">Reset Password</h3>
        <p class="text-muted small mb-4">Setting new password for <strong>{{ $email }}</strong></p>
    </div>

    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3" style="background-color: #ECFDF5; border-color: #A7F3D0; color: #065F46; border-radius: 10px; font-size: 0.85rem;">
            <i class="fa-solid fa-circle-check text-success"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-3" style="background-color: #FEF2F2; border-color: #FECACA; color: #991B1B; border-radius: 10px; font-size: 0.85rem;">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <!-- New Password -->
        <div class="mb-3">
            <label for="password" class="form-label ps-1 text-secondary font-weight-500" style="font-size: 0.875rem;">New Password</label>
            <div class="light-input-group">
                <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                <input type="password" id="password" name="password" required autofocus placeholder="At least 8 characters">
                <button type="button" class="toggle-password" id="togglePass1" aria-label="Toggle password visibility">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            <div class="strength-meter-bar">
                <div class="strength-fill" id="strengthFill"></div>
            </div>
            <span class="small text-muted" id="strengthLabel" style="font-size: 0.75rem;">Password must be at least 8 characters long</span>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label ps-1 text-secondary font-weight-500" style="font-size: 0.875rem;">Confirm New Password</label>
            <div class="light-input-group">
                <span class="input-icon"><i class="fa-solid fa-shield-halved"></i></span>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Re-enter your new password">
                <button type="button" class="toggle-password" id="togglePass2" aria-label="Toggle password visibility">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-gradient mb-3">
            <i class="fa-solid fa-rotate me-2"></i> Update Password & Sign In
        </button>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle
            function setupToggle(buttonId, inputId) {
                const btn = document.getElementById(buttonId);
                if (!btn) return;
                btn.addEventListener('click', function() {
                    const input = document.getElementById(inputId);
                    if (!input) return;
                    input.type = input.type === 'password' ? 'text' : 'password';
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                });
            }
            setupToggle('togglePass1', 'password');
            setupToggle('togglePass2', 'password_confirmation');

            // Password strength meter
            const passInput = document.getElementById('password');
            const fill = document.getElementById('strengthFill');
            const label = document.getElementById('strengthLabel');

            if (passInput && fill && label) {
                passInput.addEventListener('input', function() {
                    const val = this.value;
                    let score = 0;
                    if (val.length >= 8) score += 25;
                    if (/[A-Z]/.test(val)) score += 25;
                    if (/[0-9]/.test(val)) score += 25;
                    if (/[^A-Za-z0-9]/.test(val)) score += 25;

                    fill.style.width = score + '%';

                    if (val.length === 0) {
                        fill.style.backgroundColor = '#E2E8F0';
                        label.textContent = 'Password must be at least 8 characters long';
                    } else if (score <= 25) {
                        fill.style.backgroundColor = '#EF4444';
                        label.textContent = 'Weak password';
                    } else if (score <= 75) {
                        fill.style.backgroundColor = '#F59E0B';
                        label.textContent = 'Moderate password';
                    } else {
                        fill.style.backgroundColor = '#10B981';
                        label.textContent = 'Strong password';
                    }
                });
            }
        });
    </script>
@endpush
