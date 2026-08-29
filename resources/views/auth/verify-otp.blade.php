@extends('layouts.auth')

@section('title', setting('app_name', 'InnovaCRM') . ' - Verify OTP')

@section('left-panel')
    <h1 class="hero-title">
        Enter<br>
        <span class="text-gradient">Verification Code.</span>
    </h1>

    <p class="hero-subtitle">
        Check your email inbox. We've sent a 6-digit OTP code to complete your password reset request.
    </p>

    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <div class="feature-text">
                <h5>Two-Factor Security</h5>
                <p>Verify identity with a single-use secure code</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-clock"></i></div>
            <div class="feature-text">
                <h5>Time-Sensitive OTP</h5>
                <p>Codes expire automatically to ensure safety</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-paper-plane"></i></div>
            <div class="feature-text">
                <h5>Instant Delivery</h5>
                <p>Verification codes dispatched immediately</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-lock"></i></div>
            <div class="feature-text">
                <h5>Session Guard</h5>
                <p>Keeps unauthorized users out of your account</p>
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
        <h3 class="fw-bold mb-1" style="font-size: 1.65rem;">Enter OTP Code</h3>
        <p class="text-muted small mb-1">We sent a 6-digit code to</p>
        <p class="fw-semibold text-dark small mb-3">{{ $email }}</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3" style="background-color: #ECFDF5; border-color: #A7F3D0; color: #065F46; border-radius: 10px; font-size: 0.85rem;">
            <i class="fa-solid fa-circle-check text-success"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    @if ($errors->has('otp'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" style="background-color: #FEF2F2; border-color: #FECACA; color: #991B1B; border-radius: 10px; font-size: 0.875rem;">
            <i class="fa-solid fa-circle-exclamation text-danger"></i>
            <div>{{ $errors->first('otp') }}</div>
        </div>
    @endif

    @if ($errors->has('resend'))
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-3" style="background-color: #FFFBEB; border-color: #FDE68A; color: #92400E; border-radius: 10px; font-size: 0.85rem;">
            <i class="fa-solid fa-triangle-exclamation text-warning"></i>
            <div>{{ $errors->first('resend') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.verify.submit') }}" id="otpForm">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="otp-grid" id="otpBoxContainer">
            <input type="text" class="otp-field" name="otp[]" maxlength="1" pattern="[0-9]*" inputmode="numeric" autofocus required>
            <input type="text" class="otp-field" name="otp[]" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
            <input type="text" class="otp-field" name="otp[]" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
            <input type="text" class="otp-field" name="otp[]" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
            <input type="text" class="otp-field" name="otp[]" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
            <input type="text" class="otp-field" name="otp[]" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
        </div>

        <button type="submit" class="btn-gradient mb-3">
            <i class="fa-solid fa-check-double me-2"></i> Verify Code
        </button>
    </form>

    <!-- Resend Option with Cooldown -->
    <div class="text-center mt-3 pt-2 border-top">
        <p class="small text-muted mb-1">Didn't receive the code?</p>
        <form method="POST" action="{{ route('password.resend') }}" class="d-inline" id="resendForm">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <button type="submit" class="resend-btn" id="resendBtn" @if($cooldown > 0) disabled @endif>
                Resend Verification Code
            </button>
        </form>
        <div id="cooldownTimer" class="small text-secondary mt-1" style="font-size: 0.8rem;">
            @if($cooldown > 0)
                Resend available in <span id="timerSeconds">{{ $cooldown }}</span>s
            @endif
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('password.request') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Change Email
        </a>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.otp-field');
            const otpForm = document.getElementById('otpForm');

            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    const value = e.target.value;
                    if (value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
                    if (/^\d{6}$/.test(pasteData)) {
                        pasteData.split('').forEach((char, i) => {
                            if (inputs[i]) inputs[i].value = char;
                        });
                        inputs[inputs.length - 1].focus();
                    }
                });
            });

            // Cooldown Countdown Timer
            let cooldown = parseInt("{{ $cooldown }}") || 0;
            const resendBtn = document.getElementById('resendBtn');
            const timerContainer = document.getElementById('cooldownTimer');
            const timerSpan = document.getElementById('timerSeconds');

            if (cooldown > 0) {
                const interval = setInterval(() => {
                    cooldown--;
                    if (timerSpan) timerSpan.textContent = cooldown;

                    if (cooldown <= 0) {
                        clearInterval(interval);
                        if (resendBtn) resendBtn.disabled = false;
                        if (timerContainer) timerContainer.textContent = '';
                    }
                }, 1000);
            }
        });
    </script>
@endpush
