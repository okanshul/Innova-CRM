<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - {{ config('pwa.name', 'InnovaCRM') }}</title>

    <meta name="theme-color" content="{{ config('pwa.theme_color', '#4f46e5') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: {{ config('pwa.theme_color', '#4f46e5') }};
            --primary-hover: #4338ca;
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .offline-card {
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            max-width: 460px;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon-container {
            width: 88px;
            height: 88px;
            margin: 0 auto 1.75rem;
            border-radius: 50%;
            background: rgba(79, 70, 229, 0.15);
            border: 2px solid rgba(79, 70, 229, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #818cf8;
            font-size: 2.25rem;
            position: relative;
        }

        .pulse-ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid rgba(79, 70, 229, 0.4);
            animation: pulse 2s cubic-bezier(0.45, 0, 0.55, 1) infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.95);
                opacity: 0.8;
            }
            50% {
                transform: scale(1.15);
                opacity: 0;
            }
            100% {
                transform: scale(0.95);
                opacity: 0;
            }
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            letter-spacing: -0.025em;
        }

        p {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .btn-retry {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            width: 100%;
            padding: 0.875rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            border: none;
            border-radius: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
            text-decoration: none;
        }

        .btn-retry:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.5);
        }

        .btn-retry:active {
            transform: translateY(0);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 1.5rem;
            font-size: 0.825rem;
            color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
            padding: 0.35rem 0.85rem;
            border-radius: 2rem;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #f59e0b;
        }
    </style>
</head>

<body>
    <div class="offline-card">
        <div class="icon-container">
            <div class="pulse-ring"></div>
            <i class="fa-solid fa-wifi-slash"></i>
        </div>

        <h1>You are Offline</h1>
        <p>It looks like you've lost your internet connection. Some features may not be available until you reconnect.</p>

        <button type="button" class="btn-retry" id="retryBtn" onclick="location.reload();">
            <i class="fa-solid fa-arrows-rotate"></i> Retry Connection
        </button>

        <div class="status-badge" id="statusBadge">
            <span class="status-dot"></span> Waiting for connection...
        </div>
    </div>

    <script>
        // Auto reconnect detection
        window.addEventListener('online', function() {
            const badge = document.getElementById('statusBadge');
            if (badge) {
                badge.style.color = '#10b981';
                badge.style.background = 'rgba(16, 185, 129, 0.1)';
                badge.style.borderColor = 'rgba(16, 185, 129, 0.2)';
                badge.innerHTML = '<span class="status-dot" style="background:#10b981"></span> Connection restored! Reloading...';
            }
            setTimeout(function() {
                window.location.reload();
            }, 1000);
        });
    </script>
</body>

</html>
