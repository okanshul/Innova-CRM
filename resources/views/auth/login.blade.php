<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>InnovaCRM - Login</title>

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
        .left-panel {
            width: 40%;
            background-color: var(--dark-bg-left);
            position: relative;
            overflow: hidden;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Decorative Background Elements */
        .left-panel::before {
            content: '';
            position: absolute;
            top: -20%;
            left: -20%;
            width: 70%;
            height: 70%;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, rgba(10,10,20,0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -20%;
            right: -20%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(129,140,248,0.1) 0%, rgba(10,10,20,0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Constellation Lines */
        .constellation {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: 
                radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
                radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 50px 50px, 100px 100px;
            background-position: 0 0, 25px 25px;
            opacity: 0.5;
            z-index: 0;
            pointer-events: none;
        }

        .left-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 440px;
            width: 100%;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 4rem;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            min-width: 38px;
            background: linear-gradient(135deg, #6366f1, #a855f7) !important;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
            color: #ffffff;
        }

        .text-gradient {
            background: linear-gradient(to right, #818cf8, var(--brand-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            color: var(--text-gray);
            font-size: 1.125rem;
            max-width: 400px;
            margin-bottom: 3rem;
            line-height: 1.6;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .feature-icon {
            width: 32px;
            height: 32px;
            min-width: 32px;
            border-radius: 8px;
            background-color: rgba(99, 102, 241, 0.15);
            color: #818cf8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            margin-top: 2px;
        }

        .feature-text h5 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 4px 0;
            color: #fff;
        }

        .feature-text p {
            font-size: 0.875rem;
            color: var(--text-gray);
            margin: 0;
        }

        /* --- RIGHT PANEL (LIGHT THEME) --- */
        .right-panel {
            width: 60%;
            background-color: var(--light-bg-right);
            color: var(--light-text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-card {
            background-color: var(--light-card-bg);
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            transition: padding 0.2s ease, border-radius 0.2s ease;
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
            .form-check-label, .forgot-link {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="split-layout">
        <!-- LEFT PANEL -->
        <div class="left-panel d-none d-lg-flex">
            <div class="constellation"></div>
            
            <div class="left-content">
                <div class="brand-logo mb-4">
                    @if(setting('system_logo'))
                        <img src="{{ asset(setting('system_logo')) }}" alt="{{ setting('app_name', 'InnovaCRM') }}" style="max-height: 48px; max-width: 220px; object-fit: contain;">
                    @else
                        <div class="d-inline-flex align-items-center gap-2">
                            <div class="brand-icon rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0" style="width: 44px; height: 44px; background: linear-gradient(135deg, #6366f1, #a855f7);">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2L2 7V17L12 22L22 17V7L12 Z" stroke="white" stroke-width="2" stroke-linejoin="round" />
                                    <path d="M2 7L12 12L22 7" stroke="white" stroke-width="2" stroke-linejoin="round" />
                                    <path d="M12 12V22" stroke="white" stroke-width="2" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="fs-3 fw-bold text-white tracking-tight ms-1">{{ setting('app_name', 'InnovaCRM') }}</span>
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

            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel">
            <div class="login-card">
                <!-- Brand Header -->
                <div class="text-center mb-3">
                    @if(setting('system_logo'))
                        <img src="{{ asset(setting('system_logo')) }}" alt="{{ setting('app_name', 'InnovaCRM') }}" style="max-height: 48px; max-width: 220px; object-fit: contain;">
                    @else
                        <div class="d-inline-flex align-items-center gap-2">
                            <div class="brand-icon rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0" style="width: 44px; height: 44px; background: linear-gradient(135deg, #6366f1, #a855f7);">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2L2 7V17L12 22L22 17V7L12 Z" stroke="white" stroke-width="2" stroke-linejoin="round" />
                                    <path d="M2 7L12 12L22 7" stroke="white" stroke-width="2" stroke-linejoin="round" />
                                    <path d="M12 12V22" stroke="white" stroke-width="2" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="fs-3 fw-bold text-body-emphasis tracking-tight ms-1">{{ setting('app_name', 'InnovaCRM') }}</span>
                        </div>
                    @endif
                </div>

                <div class="text-center">
                    <h3>Welcome back 👋</h3>
                    <p class="subtitle">Sign in to your InnovaCRM account</p>
                </div>

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label ps-2">Email address</label>
                        <div class="light-input-group">
                            <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" autofocus autocomplete="username" placeholder="Enter your email">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1" style="color: #DC2626 !important;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label ps-2">Password</label>
                        <div class="light-input-group">
                            <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" id="password" name="password" autocomplete="current-password" placeholder="Enter your password">
                            <button type="button" class="toggle-password" id="togglePassword" aria-label="Toggle password visibility"><i class="fa-regular fa-eye"></i></button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1" style="color: #DC2626 !important;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Options -->
                    <div class="options-row mb-3 pb-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a href="/forgot-password" class="forgot-link">Forgot password?</a>
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
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>


