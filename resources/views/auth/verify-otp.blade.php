<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ setting('app_name', 'InnovaCRM') }} - Verify OTP</title>

    @if (setting('favicon'))
        <link rel="icon" href="{{ asset(setting('favicon')) }}">
    @endif

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Theme SCSS -->
    @vite(['resources/scss/theme.scss'])

    <style>
        :root {
            --brand-purple: #5a40f8;
            --light-bg-right: #F8FAFC;
            --light-text-main: #0F172A;
            --light-text-muted: #64748B;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg-right);
            color: var(--light-text-main);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .split-layout {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }

        .left-panel {
            width: 40%;
            background-color: #06060F;
            background-image:
                radial-gradient(circle at 10% 10%, rgba(99, 102, 241, 0.28) 0%, transparent 45%),
                radial-gradient(circle at 90% 55%, rgba(168, 85, 247, 0.22) 0%, transparent 45%),
                linear-gradient(145deg, #05050D 0%, #0C0C1B 50%, #05050A 100%);
            position: relative;
            overflow: hidden;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .left-panel-bg {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .left-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(75px);
            opacity: 0.65;
        }

        .left-orb-1 {
            top: -12%;
            left: -12%;
            width: 450px;
            height: 450px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.35), rgba(168, 85, 247, 0.25));
        }

        .left-grid-pattern {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.14) 1.2px, transparent 1.2px);
            background-size: 32px 32px;
            opacity: 0.65;
        }

        .left-content {
            position: relative;
            z-index: 1;
            max-width: 450px;
            width: 100%;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1.25rem;
            color: #ffffff;
        }

        .text-gradient {
            background: linear-gradient(135deg, #A5B4FC 0%, #C084FC 50%, #818CF8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            color: #94A3B8;
            font-size: 1.05rem;
            line-height: 1.6;
        }

        .right-panel {
            width: 60%;
            background-color: #F8FAFC;
            background-image:
                radial-gradient(circle at 90% 10%, rgba(99, 102, 241, 0.14) 0%, transparent 45%),
                radial-gradient(circle at 10% 90%, rgba(168, 85, 247, 0.12) 0%, transparent 45%),
                linear-gradient(180deg, #F8FAFC 0%, #F1F5F9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        .auth-card {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            border-radius: 22px;
            padding: 2.75rem;
            width: 100%;
            max-width: 480px;
            box-shadow:
                0 25px 60px -15px rgba(99, 102, 241, 0.14),
                0 10px 25px -5px rgba(0, 0, 0, 0.03);
        }

        .auth-icon-badge {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.12), rgba(168, 85, 247, 0.12));
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--brand-purple);
            margin: 0 auto 1.25rem;
        }

        /* 6-Digit OTP Box Grid */
        .otp-grid {
            display: flex;
            gap: 0.6rem;
            justify-content: center;
            margin: 1.75rem 0;
        }

        .otp-field {
            width: 52px;
            height: 60px;
            border-radius: 12px;
            border: 2px solid #CBD5E1;
            background-color: #F8FAFC;
            font-size: 1.6rem;
            font-weight: 700;
            text-align: center;
            color: #1E293B;
            outline: none;
            transition: all 0.2s ease;
        }

        .otp-field:focus {
            border-color: var(--brand-purple);
            background-color: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(90, 64, 248, 0.15);
            transform: translateY(-2px);
        }

        .btn-gradient {
            background: linear-gradient(to right, #6366F1, var(--brand-purple));
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.875rem;
            border-radius: 10px;
            width: 100%;
            min-height: 46px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(90, 64, 248, 0.25);
            font-size: 0.95rem;
            cursor: pointer;
        }

        .btn-gradient:hover {
            opacity: 0.95;
            box-shadow: 0 6px 16px rgba(90, 64, 248, 0.35);
            transform: translateY(-1px);
        }

        .resend-btn {
            background: none;
            border: none;
            color: var(--brand-purple);
            font-weight: 600;
            cursor: pointer;
            padding: 0;
            font-size: 0.875rem;
            text-decoration: underline;
        }

        .resend-btn:disabled {
            color: #94A3B8;
            cursor: not-allowed;
            text-decoration: none;
        }

        .back-link {
            color: var(--light-text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-link:hover {
            color: var(--brand-purple);
        }

        @media (max-width: 991.98px) {
            .split-layout {
                flex-direction: column;
            }

            .left-panel {
                display: none;
            }

            .right-panel {
                width: 100%;
                min-height: 100vh;
                padding: 2rem 1.25rem;
            }

            .otp-field {
                width: 44px;
                height: 52px;
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>
    <div class="split-layout">
        <!-- LEFT PANEL -->
        <div class="left-panel d-none d-lg-flex">
            <div class="left-panel-bg">
                <div class="left-orb left-orb-1"></div>
                <div class="left-grid-pattern"></div>
            </div>

            <div class="left-content">
                <div class="brand-logo mb-4">
                    @if (setting('system_logo'))
                        <img src="{{ asset(setting('system_logo')) }}" alt="{{ setting('app_name', 'InnovaCRM') }}"
                            style="max-height: 48px; max-width: 220px; object-fit: contain;">
                    @else
                        <div class="d-inline-flex align-items-center gap-2">
                            <div class="brand-icon rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0"
                                style="width: 44px; height: 44px; background: linear-gradient(135deg, #6366f1, #a855f7);">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2L2 7V17L12 22L22 17V7L12 Z" stroke="white" stroke-width="2"
                                        stroke-linejoin="round" />
                                    <path d="M2 7L12 12L22 7" stroke="white" stroke-width="2" stroke-linejoin="round" />
                                    <path d="M12 12V22" stroke="white" stroke-width="2" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="fs-3 fw-bold text-white tracking-tight ms-1">{{ setting('app_name', 'InnovaCRM') }}</span>
                        </div>
                    @endif
                </div>

                <h1 class="hero-title">
                    Enter<br>
                    <span class="text-gradient">Verification Code.</span>
                </h1>

                <p class="hero-subtitle">
                    Check your email inbox. We've sent a 6-digit OTP code to complete your password reset request.
                </p>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel">
            <div class="auth-card">
                <div class="text-center">
                    <div class="auth-icon-badge">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
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
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" style="background-color: #FEF2F2; border-color: #FECACA; color: #991B1B; border-radius: 10px; font-size: 0.85rem;">
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
            </div>
        </div>
    </div>

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
</body>

</html>
