<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>InnovaCRM - Login</title>

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

            /* Light theme variables for right panel */
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
        /* --- LEFT PANEL (ULTRA ENHANCED DARK DESIGN) --- */
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

        /* Ambient Background Elements for Left Panel */
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

        .left-orb-3 {
            top: 45%;
            left: 30%;
            width: 280px;
            height: 280px;
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.18), rgba(99, 102, 241, 0.15));
            filter: blur(65px);
        }

        /* Grid Pattern Overlay */
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

        /* Decorative Static Rings */
        .left-geo-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px dashed rgba(168, 85, 247, 0.25);
            pointer-events: none;
        }

        .left-geo-ring-1 {
            top: 4%;
            right: 4%;
            width: 240px;
            height: 240px;
        }

        .left-geo-ring-2 {
            bottom: 6%;
            left: 5%;
            width: 320px;
            height: 320px;
            border: 1px dashed rgba(99, 102, 241, 0.22);
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

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 3.25rem;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            min-width: 44px;
            background: linear-gradient(135deg, #6366F1, #A855F7) !important;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.2) inset;
        }

        .hero-title {
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.12;
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

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            margin-bottom: 1.75rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1.1rem;
            padding: 0.9rem 1.15rem;
            background: rgba(255, 255, 255, 0.035);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 14px;
            transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
        }

        .feature-item:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(99, 102, 241, 0.3);
            transform: translateX(3px);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.28), rgba(168, 85, 247, 0.22));
            border: 1px solid rgba(99, 102, 241, 0.4);
            color: #A5B4FC;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
        }

        .feature-text h5 {
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0 0 2px 0;
            color: #F8FAFC;
        }

        .feature-text p {
            font-size: 0.825rem;
            color: #94A3B8;
            margin: 0;
        }

        /* Trust Stats Banner */
        .left-trust-banner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.025);
            border: 1px solid rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 14px;
            padding: 0.85rem 1.25rem;
            margin-top: 0.5rem;
        }

        .trust-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .stat-number {
            font-size: 1.1rem;
            font-weight: 700;
            color: #F8FAFC;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.725rem;
            color: #94A3B8;
            margin-top: 2px;
        }

        .trust-divider {
            width: 1px;
            height: 24px;
            background: rgba(255, 255, 255, 0.08);
        }

        /* --- RIGHT PANEL (ULTRA ENHANCED BACKGROUND) --- */
        .right-panel {
            width: 60%;
            background-color: #F8FAFC;
            background-image:
                radial-gradient(circle at 90% 10%, rgba(99, 102, 241, 0.14) 0%, transparent 45%),
                radial-gradient(circle at 10% 90%, rgba(168, 85, 247, 0.12) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.08) 0%, transparent 60%),
                linear-gradient(180deg, #F8FAFC 0%, #F1F5F9 100%);
            color: var(--light-text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        /* Ambient Background Decorative Elements */
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

        .bg-orb-3 {
            top: 40%;
            right: 15%;
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.12), rgba(99, 102, 241, 0.15));
        }

        /* Tech Dot & Grid Pattern Overlay */
        .bg-pattern {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(rgba(99, 102, 241, 0.16) 1.2px, transparent 1.2px),
                linear-gradient(to right, rgba(99, 102, 241, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(99, 102, 241, 0.04) 1px, transparent 1px);
            background-size: 30px 30px;
            mask-image: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.9) 0%, transparent 80%);
            -webkit-mask-image: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.9) 0%, transparent 80%);
            opacity: 0.75;
        }

        /* Abstract Ring Graphics */
        .bg-geo-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px dashed rgba(99, 102, 241, 0.22);
            pointer-events: none;
        }

        .bg-geo-ring-1 {
            top: 6%;
            left: 6%;
            width: 200px;
            height: 200px;
        }

        .bg-geo-ring-2 {
            bottom: 8%;
            right: 5%;
            width: 280px;
            height: 280px;
            border: 1px dashed rgba(168, 85, 247, 0.18);
        }

        /* Decorative Glass Badges in Background */
        .bg-chip {
            position: absolute;
            z-index: 0;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.6rem 1.15rem;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #475569;
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.1), 0 2px 6px rgba(0, 0, 0, 0.02);
            pointer-events: none;
            opacity: 0.9;
        }

        .bg-chip .chip-icon {
            color: #6366F1;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
        }

        .bg-chip-1 {
            top: 5%;
            right: 8%;
        }

        .bg-chip-2 {
            bottom: 5%;
            left: 8%;
        }

        .login-card {
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
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .login-card:hover {
            transform: translateY(-2px);
            box-shadow:
                0 30px 70px -15px rgba(99, 102, 241, 0.2),
                0 12px 30px -5px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(99, 102, 241, 0.15);
        }

        .login-card h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--light-text-main);
            margin-bottom: 0.5rem;
        }

        .login-card .subtitle {
            color: var(--light-text-muted);
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .form-label {
            color: var(--light-text-label);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
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
            min-width: 0;
            flex: 1;
            outline: none;
            font-size: 0.95rem;
        }

        .light-input-group input::placeholder {
            color: #94A3B8;
        }

        .light-input-group .toggle-password {
            background: transparent;
            border: none;
            color: #64748B;
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: color 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .light-input-group .toggle-password:hover {
            color: var(--light-text-main);
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: nowrap;
            width: 100%;
            gap: 0.5rem;
        }

        .form-check {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding-left: 0 !important;
            margin: 0 !important;
        }

        .form-check-input {
            margin: 0 !important;
            float: none !important;
            cursor: pointer;
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
            background-color: #FFFFFF;
            border-color: #CBD5E1;
        }

        .form-check-input:checked {
            background-color: var(--brand-purple);
            border-color: var(--brand-purple);
        }

        .form-check-label {
            color: var(--light-text-muted);
            font-size: 0.875rem;
            white-space: nowrap;
            cursor: pointer;
            user-select: none;
            margin-bottom: 0;
        }

        .forgot-link {
            color: var(--brand-purple);
            font-size: 0.875rem;
            text-decoration: none;
            font-weight: 500;
            white-space: nowrap;
            transition: color 0.2s ease;
            flex-shrink: 0;
        }

        .forgot-link:hover {
            color: #4338CA;
            text-decoration: underline;
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

        .signup-text {
            color: var(--light-text-muted);
            font-size: 0.9rem;
            margin-top: 1.5rem;
        }

        .signup-link {
            color: var(--brand-purple);
            text-decoration: none;
            font-weight: 500;
        }

        .signup-link:hover {
            text-decoration: underline;
        }

        .alert-light-error {
            background-color: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
        }

        /* --- RESPONSIVE BREAKPOINTS --- */
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

        @media (max-width: 576px) {
            .right-panel {
                padding: 1.25rem 0.75rem;
            }

            .login-card {
                padding: 1.5rem 1rem;
                border-radius: 14px;
            }

            .login-card h3 {
                font-size: 1.4rem;
            }

            .login-card .subtitle {
                font-size: 0.85rem;
                margin-bottom: 1.25rem;
            }

            .light-input-group input {
                font-size: 0.875rem;
            }

            .light-input-group input::placeholder {
                font-size: 0.85rem;
            }

            .form-check-label,
            .forgot-link {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="split-layout">
        <!-- LEFT PANEL -->
        <div class="left-panel d-none d-lg-flex">
            <!-- Left Ambient Background -->
            <div class="left-panel-bg">
                <div class="left-orb left-orb-1"></div>
                <div class="left-orb left-orb-2"></div>
                <div class="left-orb left-orb-3"></div>
                <div class="left-grid-pattern"></div>
                <div class="left-geo-ring left-geo-ring-1"></div>
                <div class="left-geo-ring left-geo-ring-2"></div>
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
                            <span
                                class="fs-3 fw-bold text-white tracking-tight ms-1">{{ setting('app_name', 'InnovaCRM') }}</span>
                        </div>
                    @endif
                </div>

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

            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel">
            <!-- Ambient Background -->
            <div class="right-panel-bg">
                <div class="bg-orb bg-orb-1"></div>
                <div class="bg-orb bg-orb-2"></div>
                <div class="bg-orb bg-orb-3"></div>
                <div class="bg-pattern"></div>
                <div class="bg-geo-ring bg-geo-ring-1"></div>
                <div class="bg-geo-ring bg-geo-ring-2"></div>

                <!-- Decorative Glass Badges in Background -->
                <div class="bg-chip bg-chip-1">
                    <span class="chip-icon"><i class="fa-solid fa-shield-halved"></i></span>
                    <span>256-Bit SSL Protection</span>
                </div>
                <div class="bg-chip bg-chip-2">
                    <span class="chip-icon"><i class="fa-solid fa-circle-check"></i></span>
                    <span>System Status: Operational</span>
                </div>
            </div>

            <div class="login-card">
                <!-- Brand Header -->
                <div class="text-center mb-3">
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
                                    <path d="M2 7L12 12L22 7" stroke="white" stroke-width="2"
                                        stroke-linejoin="round" />
                                    <path d="M12 12V22" stroke="white" stroke-width="2" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span
                                class="fs-3 fw-bold text-body-emphasis tracking-tight ms-1">{{ setting('app_name', 'InnovaCRM') }}</span>
                        </div>
                    @endif
                </div>

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
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Vanilla JS Password Toggle -->
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';

            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
