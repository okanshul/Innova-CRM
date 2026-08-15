<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'InnovaCRM' }}</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Inline script to apply collapsed state before initial paint -->
    <script>
        (function() {
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        })();
    </script>

    @vite(['resources/scss/theme.scss', 'resources/js/dashboard.js'])

    {{ $styles ?? '' }}
    @stack('styles')
</head>

<body>
    <script>
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }
    </script>

    @include('partials.sidebar')

    <div id="main-content">
        @include('partials.header')

        <main class="flex-grow-1 p-3 p-md-4">
            @yield('content', $slot ?? '')
        </main>

        @include('partials.footer')
    </div>

    @include('partials.mobile-nav')

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Flatpickr CDN -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Shared App Utilities JS -->
    <script src="{{ asset('js/app-utils.js') }}"></script>

    {{ $scripts ?? '' }}
    @stack('scripts')
</body>

</html>
