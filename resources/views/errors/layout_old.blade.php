<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Server Error') - {{ config('app.name', 'InnovaCRM') }}</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/scss/theme.scss', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --purple-primary: #5B5DF6;
            --purple-dark: #4F46E5;
            --purple-hover: #4338CA;
            --navy-text: #1E293B;
            --gray-text: #64748B;
            --gray-subtext: #94A3B8;
            --bg-light-gray: #F8FAFC;
            --border-light: #E2E8F0;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #FFFFFF;
            color: var(--navy-text);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        .text-navy {
            color: var(--navy-text) !important;
        }

        .text-secondary-gray {
            color: var(--gray-text) !important;
        }

        .bg-light-gray {
            background-color: var(--bg-light-gray) !important;
        }

        /* Top Logo / Badge */
        .error-badge-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #5B5DF6, #4F46E5);
            box-shadow: 0 4px 14px rgba(91, 93, 246, 0.3);
            border-radius: 10px;
        }

        .error-badge-text {
            font-weight: 700;
            color: var(--navy-text);
            font-size: 1rem;
            letter-spacing: -0.01em;
        }

        /* 500 Gradient Code */
        .error-code-title {
            font-size: clamp(6.5rem, 11vw, 9.5rem);
            font-weight: 900;
            line-height: 0.95;
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, #5B5DF6 0%, #4F46E5 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: #4F46E5;
        }

        /* Title */
        .error-heading {
            font-size: clamp(1.85rem, 3.2vw, 2.5rem);
            font-weight: 800;
            color: var(--navy-text);
            letter-spacing: -0.025em;
        }

        /* Divider */
        .error-divider {
            width: 80px;
            height: 5px;
            background: linear-gradient(90deg, #5B5DF6, #4F46E5);
            border-radius: 100px;
            opacity: 0.9;
        }

        /* Description */
        .error-description {
            color: var(--gray-text);
            font-size: 1.05rem;
            line-height: 1.65;
            max-width: 440px;
        }

        /* Buttons */
        .btn-primary-gradient {
            background: linear-gradient(90deg, #5B5DF6, #4F46E5);
            border: none;
            color: #ffffff !important;
            padding: 13px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary-gradient:hover, .btn-primary-gradient:focus {
            background: linear-gradient(90deg, #4F46E5, #4338CA);
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(79, 70, 229, 0.45);
            color: #ffffff !important;
        }

        .btn-outline-custom {
            background-color: #ffffff;
            border: 1px solid var(--border-light);
            color: var(--navy-text) !important;
            padding: 13px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-outline-custom:hover, .btn-outline-custom:focus {
            background-color: #F8FAFC !important;
            border-color: #CBD5E1 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            color: var(--navy-text) !important;
        }

        /* Support Card */
        .support-card {
            background-color: #F8FAFC;
            border: 1px solid #F1F5F9;
            border-radius: 16px;
            padding: 16px 20px;
            max-width: 440px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .headset-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: #EEF2FF;
            border: 1px solid #E0E7FF;
            color: #5B5DF6;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Right Column Illustration & Cloud Backdrop */
        .illustration-wrapper {
            position: relative;
            width: 100%;
            max-width: 780px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .illustration-cloud-backdrop {
            position: absolute;
            width: 110%;
            height: 110%;
            top: -5%;
            left: -5%;
            z-index: 0;
            pointer-events: none;
            overflow: visible;
        }

        .error-illustration {
            max-width: 720px;
            width: 100%;
            height: auto;
            object-fit: contain;
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 14px 30px rgba(79, 70, 229, 0.12));
            transition: transform 0.3s ease;
        }

        @media (min-width: 1400px) {
            .illustration-wrapper {
                max-width: 840px;
            }
            .error-illustration {
                max-width: 780px;
            }
        }

        .purple-dot {
            width: 6px;
            height: 6px;
            background-color: #818CF8;
            border-radius: 50%;
            display: inline-block;
        }

        .error-subtext {
            color: var(--gray-subtext);
            font-size: 0.875rem;
            font-weight: 500;
        }
    </style>

    @stack('styles')
</head>
<body class="h-100 bg-white text-dark antialiased">
    <div class="min-vh-100 d-flex flex-column justify-content-center position-relative overflow-hidden">
        
        <!-- Logo / Badge Header positioned at top -->
        <header class="position-absolute top-0 start-0 w-100 z-3">
            <div class="container d-flex justify-content-between align-items-center pt-4 pt-lg-5 px-4 px-sm-5 px-xl-5">
                <div class="d-flex align-items-center gap-2">
                    <div class="text-primary fs-4" style="color: var(--purple-primary) !important;">
                        <i class="fa-solid fa-cube"></i>
                    </div>
                    <span class="fw-bold fs-5 text-navy">InnovaCRM</span>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ url('/') }}" class="btn btn-outline-custom btn-sm d-inline-flex align-items-center gap-2 px-3 py-2" style="border-radius: 8px;">
                        <i class="fa-solid fa-house"></i> <span class="d-none d-sm-inline">Home</span>
                    </a>
                    <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="btn btn-outline-custom btn-sm d-inline-flex align-items-center gap-2 px-3 py-2" style="border-radius: 8px;">
                        <i class="fa-solid fa-border-all"></i> <span class="d-none d-sm-inline">Dashboard</span>
                    </a>
                </div>
            </div>
        </header>

        <main class="container flex-grow-1 d-flex align-items-center px-4 px-sm-5 px-xl-5 mt-5 mt-lg-0 pt-5 pt-lg-0">
            <div class="row align-items-center justify-content-between w-100 gy-5 gx-4 gx-lg-5 my-auto">
                
                <!-- Left Column: Content -->
                <div class="col-12 col-lg-5 col-xl-5 order-2 order-lg-1 ps-md-4 ps-lg-5">
                    <div class="d-flex flex-column align-items-center align-items-lg-start text-center text-lg-start">
                        
                        <!-- Huge Error Code -->
                        <h1 class="error-code-title user-select-none mb-0">
                            @yield('code', 'Error')
                        </h1>

                        <!-- Main Heading -->
                        <h2 class="error-heading mt-2 mb-1">
                            @yield('message', __('Something went wrong'))
                        </h2>

                        <!-- Description Text -->
                        <p class="error-description mb-4 mb-md-5 mt-3">
                            @yield('description', __('The page or action you requested could not be completed.'))
                        </p>

                        <!-- Interactive Buttons -->
                        <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-lg-start w-100">
                            <button type="button" onclick="history.back()" class="btn btn-primary-gradient d-inline-flex align-items-center justify-content-center gap-2" aria-label="Go Back">
                                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                                <span>Go Back</span>
                            </button>

                            <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="btn btn-outline-custom d-inline-flex align-items-center justify-content-center gap-2" aria-label="Go to Dashboard">
                                <i class="fa-solid fa-house" aria-hidden="true"></i>
                                <span>Go to Dashboard</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: 3D Image -->
                <div class="col-12 col-lg-7 col-xl-7 order-1 order-lg-2 text-center pe-md-4 pe-lg-5">
                    <div class="illustration-wrapper">
                        <!-- SVG Soft Cloud Shape Backdrop -->
                        <svg class="illustration-cloud-backdrop" viewBox="0 0 600 500" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M 230,40 C 360,20 490,50 535,135 C 580,220 550,330 485,400 C 420,470 250,480 155,425 C 60,370 50,260 95,170 C 140,80 160,50 230,40 Z" fill="#EEF2FF" opacity="0.85" />
                            <path d="M 270,60 C 380,45 470,75 505,150 C 540,225 520,305 465,365 C 410,425 270,435 185,390 C 100,345 90,255 130,175 C 170,95 185,70 270,60 Z" fill="#F3F0FF" opacity="0.65" />
                        </svg>
                        
                        <!-- Dynamic Error Image based on code -->
                        @php
                            $errorCode = trim(View::yieldContent('code', '500'));
                            $imageName = '500-server-error.png';
                            if ($errorCode == '400') $imageName = '400-bad-request.png';
                            elseif ($errorCode == '401') $imageName = '401-unauthorized.png';
                            elseif ($errorCode == '403') $imageName = '403-forbidden.png';
                            elseif ($errorCode == '404') $imageName = '404-not-found.png';
                            elseif ($errorCode == '503') $imageName = '503-service-unavailable.png';
                        @endphp
                        <img src="{{ asset('assets/errors/' . $imageName) }}" alt="{{ $errorCode }} Error Illustration" class="img-fluid user-select-none error-illustration">
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    @stack('scripts')
</body>
</html>
