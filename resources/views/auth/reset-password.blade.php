<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ setting('app_name', 'InnovaCRM') }} - Reset Password</title>

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
            --light-text-label: #334155;
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

        .light-input-group {
            background-color: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            display: flex;
            align-items: center;
            overflow: hidden;
            transition: all 0.2s ease;
            min-height: 46px;
        }

        .light-input-group:focus-within {
            border-color: var(--brand-purple);
            background-color: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(90, 64, 248, 0.15);
        }

        .light-input-group .input-icon {
            padding: 0.75rem 0.75rem 0.75rem 1rem;
            color: #64748B;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
        }

        .light-input-group input {
            background: transparent;
            border: none;
            color: var(--light-text-main);
            padding: 0.75rem 0.75rem 0.75rem 0;
            width: 100%;
            outline: none;
            font-size: 0.95rem;
        }

        .light-input-group .toggle-password {
            background: transparent;
            border: none;
            color: #64748B;
            padding: 0.75rem 1rem;
            cursor: pointer;
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

        /* Password Strength Meter */
        .strength-meter-bar {
            height: 4px;
            background-color: #E2E8F0;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 6px;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background-color 0.3s ease;
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
                    Set New<br>
                    <span class="text-gradient">Password.</span>
                </h1>

                <p class="hero-subtitle">
                    Create a strong, unique password for your account to ensure optimal security.
                </p>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel">
            <div class="auth-card">
                <div class="text-center">
                    <div class="auth-icon-badge">
                        <i class="fa-solid fa-lock text-primary"></i>
                    </div>
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
                            <button type="button" class="toggle-password" id="togglePass1">
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
                            <button type="button" class="toggle-password" id="togglePass2">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-gradient mb-3">
                        <i class="fa-solid fa-rotate me-2"></i> Update Password & Sign In
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle
            function setupToggle(buttonId, inputId) {
                document.getElementById(buttonId).addEventListener('click', function() {
                    const input = document.getElementById(inputId);
                    input.type = input.type === 'password' ? 'text' : 'password';
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }
            setupToggle('togglePass1', 'password');
            setupToggle('togglePass2', 'password_confirmation');

            // Password strength meter
            const passInput = document.getElementById('password');
            const fill = document.getElementById('strengthFill');
            const label = document.getElementById('strengthLabel');

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
        });
    </script>
</body>

</html>
