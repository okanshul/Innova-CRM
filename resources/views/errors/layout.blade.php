<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('code') {{ trim($__env->yieldContent('title')) }} | InnovaCRM</title>
    @php
        $favicon = null;
        try {
            if (function_exists('setting')) {
                $favicon = setting('favicon');
            }
        } catch (\Throwable $e) {}
    @endphp
    @if($favicon)
        <link rel="icon" href="{{ asset($favicon) }}">
    @endif

    {{-- Apply saved / system theme before paint (no flash) --}}
    <script>
        (function () {
            try {
                var saved = localStorage.getItem('theme');
                var dark = saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches);
                if (dark) document.documentElement.classList.add('dark');
            } catch (e) {}
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #3b2fe0;
            --primary-hover: #2f24c4;
            --on-primary: #ffffff;
            --bg-from: #ebf0ff;
            --bg-to: #ebf0ff;
            --surface: #ffffff;
            --border: #dcdffa;
            --heading: #0f1030;
            --code: #3b2fe0;
            --muted: #6b7094;
            --blob: rgba(99, 102, 241, .16);
            --blob-2: rgba(139, 92, 246, .10);
            --shadow: 0 1px 2px rgba(59, 47, 224, .06), 0 6px 16px rgba(59, 47, 224, .08);
        }

        html.dark {
            --primary: #5b52ff;
            --primary-hover: #7168ff;
            --bg-from: #0c0e1d;
            --bg-to: #12153a;
            --surface: #151837;
            --border: #2a2f66;
            --heading: #f4f5ff;
            --code: #7f78ff;
            --muted: #9aa0cf;
            --blob: rgba(124, 120, 255, .20);
            --blob-2: rgba(168, 85, 247, .12);
            --shadow: 0 1px 2px rgba(0, 0, 0, .4), 0 6px 16px rgba(0, 0, 0, .35);
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body { min-height: 100vh; margin: 0; }
        html { overflow-x: hidden; }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            color: var(--heading);
            background: linear-gradient(135deg, var(--bg-from) 0%, var(--bg-to) 100%);
            background-attachment: fixed;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; }

        svg.i { width: 1em; height: 1em; flex: none; }

        .page {
            min-height: 100vh;
            max-width: 1240px;
            margin: 0 auto;
            padding: 28px 32px 40px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* ---------- content ---------- */
        .content {
            flex: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            align-items: center;
            gap: 32px;
            padding: 24px 0;
        }

        .copy { max-width: 520px; }

        .code {
            margin: 0;
            font-size: clamp(88px, 13vw, 164px);
            font-weight: 900;
            line-height: .9;
            letter-spacing: -.05em;
            color: var(--code);
        }

        .title {
            margin: 14px 0 0;
            font-size: clamp(30px, 4vw, 44px);
            font-weight: 800;
            letter-spacing: -.03em;
            line-height: 1.1;
        }

        .message {
            margin: 16px 0 0;
            max-width: 440px;
            font-size: 16px;
            line-height: 1.6;
            color: var(--muted);
        }

        .actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 30px; }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 13px 22px;
            font: inherit;
            font-size: 14px;
            font-weight: 700;
            line-height: 1;
            border-radius: 0.5rem;
            border: 1px solid transparent;
            cursor: pointer;
            transition: background .15s, border-color .15s, transform .15s;
        }

        .btn:active { transform: translateY(1px); }

        .btn-primary {
            color: var(--on-primary);
            background: var(--primary);
            box-shadow: 0 6px 16px rgba(59, 47, 224, .28);
        }

        .btn-primary:hover { background: var(--primary-hover); }

        .btn-outline {
            color: var(--primary);
            background: var(--surface);
            border-color: var(--border);
        }

        .btn-outline:hover { border-color: var(--primary); }

        .btn:focus-visible {
            outline: 3px solid var(--primary);
            outline-offset: 2px;
        }

        /* ---------- artwork ---------- */
        .art {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 360px;
        }

        .art img {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 560px;
            height: auto;
            filter: drop-shadow(0 18px 28px rgba(59, 47, 224, .18));
        }

        /* Blob Cloud Backdrop */
        .art img.cloud-backdrop {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 140%;
            max-width: none;
            z-index: 0;
            pointer-events: none;
            transform: translate(-50%, -50%);
            filter: none;
        }

        /* ---------- responsive ---------- */
        @media (max-width: 900px) {
            .page { padding: 80px 20px 32px; }

            .error-logo {
                position: absolute;
                top: 24px;
                left: 20px;
                margin-bottom: 0 !important;
            }

            .content {
                grid-template-columns: 1fr;
                text-align: left;
                align-content: center;
                gap: 8px;
            }

            .copy {
                margin: -70px 0 0 0;
                order: 2;
                text-align: left;
            }
            
            /* Apply z-index to children directly so .error-logo isn't trapped in a relative container */
            .copy > .code, .copy > .title, .copy > .message, .copy > .actions {
                position: relative;
                z-index: 10;
            }

            .art { order: 1; min-height: 0; margin: 0 auto; text-align: center; }
            .art img { max-width: 360px; }
            .message { margin-left: 0; margin-right: 0; }
            .actions { justify-content: flex-start; }
        }

        @media (max-width: 520px) {
            .actions .btn { flex: 1 1 100%; }
        }
    </style>
</head>
<body>
@php
    $dashboardUrl = \Illuminate\Support\Facades\Route::has('dashboard') ? route('dashboard') : url('/dashboard');
@endphp

<div class="page">
    <main class="content">
        <section class="copy">
            <div class="error-logo" style="margin-bottom: 10px; text-decoration: none; display: inline-block;">
                <a href="{{ url('/') }}" style="text-decoration: none; color: inherit;">
                    @php
                        $systemLogo = null;
                        $appName = 'InnovaCRM';
                        try {
                            if (function_exists('setting')) {
                                $systemLogo = setting('system_logo');
                                $appName = setting('app_name', 'InnovaCRM');
                            }
                        } catch (\Throwable $e) {}
                    @endphp
                    @if($systemLogo)
                        <img src="{{ asset($systemLogo) }}" alt="{{ $appName }}" style="max-height: 48px; max-width: 200px; object-fit: contain; border-radius: 8px;">
                    @else
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #6366f1, #a855f7); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                                <i class="fa-solid fa-cube" style="color: white; font-size: 24px;"></i>
                            </div>
                            <span style="font-size: 28px; font-weight: 700; letter-spacing: -0.02em; color: var(--heading);">{{ $appName }}</span>
                        </div>
                    @endif
                </a>
            </div>
            <h1 class="code">@yield('code')</h1>
            <h2 class="title">@yield('title')</h2>
            <p class="message">@yield('message')</p>

            <div class="actions">
                <button type="button" class="btn btn-primary" onclick="goBack()">
                    <i class="fa-solid fa-arrow-left"></i>
                    Go Back
                </button>
                <a href="{{ $dashboardUrl }}" class="btn btn-outline">
                    <i class="fa-solid fa-house"></i>
                    Go to Dashboard
                </a>
            </div>
        </section>

        <aside class="art" aria-hidden="true">
            @php
                $errorCode = trim($__env->yieldContent('code', '500'));
                $imageMap = [
                    '400' => '400-bad-request.png',
                    '401' => '401-unauthorized.png',
                    '403' => '403-forbidden.png',
                    '404' => '404-not-found.png',
                    '500' => '500-server-error.png',
                    '503' => '503-service-unavailable.png',
                ];
                $imageName = $imageMap[$errorCode] ?? '500-server-error.png';
            @endphp
            <!-- Blob Cloud Shape Backdrop -->
            <img class="cloud-backdrop" src="{{ asset('assets/errors/Blob.png') }}" alt="" decoding="async">
            <img src="{{ asset('assets/errors/' . $imageName) }}" alt="" width="560" height="560" decoding="async">
        </aside>
    </main>
</div>

<script>
    function goBack() {
        if (window.history.length > 1 && document.referrer) {
            window.history.back();
        } else {
            window.location.href = @json($dashboardUrl);
        }
    }
</script>
</body>
</html>
