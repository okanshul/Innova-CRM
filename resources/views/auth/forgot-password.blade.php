<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ setting('app_name', 'InnovaCRM') }} - Forgot Password</title>

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
            --dark-bg-left: #0A0A14;
            --dark-bg-right: #12121F;
            --dark-card-bg: #1A1A2E;
            --dark-input-bg: #0F0F1A;
            --dark-input-border: #2A2A40;
            --brand-purple: #5a40f8;
            --brand-purple-light: #5a40f8;
            --text-gray: #9CA3AF;

            /* Light theme variables */
            --light-bg-right: #F8FAFC;
            --light-card-bg: #FFFFFF;
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

        /* --- LEFT PANEL --- */
        .left-panel {
            width: 40%;
            background-color: #06060F;
            background-image:
                radial-gradient(circle at 10% 10%, rgba(99, 102, 241, 0.28) 0%, transparent 45%),
                radial-gradient(circle at 90% 55%, rgba(168, 85, 247, 0.22) 0%, transparent 45%),
                radial-gradient(circle at 20% 85%, rgba(14, 165, 233, 0.18) 0%, transparent 45%),
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
            z-index: 0;
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

        .left-orb-2 {
            bottom: -15%;
            right: -10%;
            width: 480px;
            height: 480px;
            background: linear-gradient(135deg, rgba(129, 140, 248, 0.25), rgba(14, 165, 233, 0.2));
        }

        .left-grid-pattern {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(rgba(255, 255, 255, 0.14) 1.2px, transparent 1.2px),
                linear-gradient(to right, rgba(255, 255, 255, 0.035) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.035) 1px, transparent 1px);
            background-size: 32px 32px;
            mask-image: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.95) 0%, transparent 85%);
            -webkit-mask-image: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.95) 0%, transparent 85%);
            opacity: 0.65;
        }

        .left-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 450px;
            width: 100%;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1.25rem;
            letter-spacing: -0.035em;
            color: #ffffff;
        }

        .text-gradient {
            background: linear-gradient(135deg, #A5B4FC 0%, #C084FC 50%, #818CF8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            color: #94A3B8;
            font-size: 1.05rem;
            max-width: 420px;
            margin-bottom: 2.25rem;
            line-height: 1.6;
        }

        /* --- RIGHT PANEL --- */
        .right-panel {
            width: 60%;
            background-color: #F8FAFC;
            background-image:
                radial-gradient(circle at 90% 10%, rgba(99, 102, 241, 0.14) 0%, transparent 45%),
                radial-gradient(circle at 10% 90%, rgba(168, 85, 247, 0.12) 0%, transparent 45%),
                linear-gradient(180deg, #F8FAFC 0%, #F1F5F9 100%);
            color: var(--light-text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .right-panel-bg {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .bg-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: 0.7;
        }

        .bg-orb-1 {
            top: -10%;
            right: -5%;
            width: 420px;
            height: 420px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.25), rgba(168, 85, 247, 0.2));
        }

        .bg-orb-2 {
            bottom: -12%;
            left: -5%;
            width: 460px;
            height: 460px;
            background: linear-gradient(135deg, rgba(129, 140, 248, 0.22), rgba(59, 130, 246, 0.16));
        }

        .bg-pattern {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(rgba(99, 102, 241, 0.16) 1.2px, transparent 1.2px),
                linear-gradient(to right, rgba(99, 102, 241, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(99, 102, 241, 0.04) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.75;
        }

        .auth-card {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            border-radius: 22px;
            padding: 2.75rem;
            width: 100%;
            max-width: 460px;
            box-shadow:
                0 25px 60px -15px rgba(99, 102, 241, 0.14),
                0 10px 25px -5px rgba(0, 0, 0, 0.03),
                0 0 0 1px rgba(99, 102, 241, 0.1);
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

        .back-link {
            color: var(--light-text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s ease;
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
        }
    </style>
</head>

<body>
    <div class="split-layout">
        <!-- LEFT PANEL -->
        <div class="left-panel d-none d-lg-flex">
            <div class="left-panel-bg">
                <div class="left-orb left-orb-1"></div>
                <div class="left-orb left-orb-2"></div>
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
                    Account<br>
                    <span class="text-gradient">Recovery.</span>
                </h1>

                <p class="hero-subtitle">
                    Enter your registered email address to receive a secure 6-digit verification code to reset your password.
                </p>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel">
            <div class="right-panel-bg">
                <div class="bg-orb bg-orb-1"></div>
                <div class="bg-orb bg-orb-2"></div>
                <div class="bg-pattern"></div>
            </div>

            <div class="auth-card">
                <div class="text-center">
                    <div class="auth-icon-badge">
                        <i class="fa-solid fa-key"></i>
                    </div>
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
            </div>
        </div>
    </div>
</body>

</html>
