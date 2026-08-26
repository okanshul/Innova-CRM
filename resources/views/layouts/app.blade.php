<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? setting('app_name', setting('company_name', 'InnovaCRM')) }}</title>

    @include('partials.pwa')

    @if(setting('favicon'))
        <link rel="icon" href="{{ asset(setting('favicon')) }}">
    @endif

    <style>
        :root {
            @if(setting('primary_color'))
                --bs-primary: {{ setting('primary_color') }} !important;
                --bs-primary-rgb: {{ implode(',', sscanf(setting('primary_color'), "#%02x%02x%02x")) }} !important;
            @endif
            @if(setting('secondary_color'))
                --bs-secondary: {{ setting('secondary_color') }} !important;
            @endif
            @if(setting('sidebar_bg_color'))
                --sidebar-bg-color: {{ setting('sidebar_bg_color') }} !important;
            @endif
        }
        @if(setting('sidebar_bg_color'))
            #sidebar {
                background-color: {{ setting('sidebar_bg_color') }} !important;
                --bs-offcanvas-bg: {{ setting('sidebar_bg_color') }} !important;
            }
        @endif
    </style>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Inline script to apply theme preference & collapsed state before initial paint -->
    <script>
        window.crmSettings = {
            itemsPerPage: parseInt("{{ setting('items_per_page', 10) }}", 10) || 10,
            currencySymbol: "{{ setting('currency_symbol', 'USD') }}",
            dateFormat: "{{ setting('date_format', 'MMM D, YYYY') }}",
            timeFormat: "{{ setting('time_format', '12') }}",
            timezone: "{{ setting('timezone', 'Asia/Kolkata') }}"
        };
        (function() {
            var storedTheme = localStorage.getItem('theme') || 'auto';
            var appliedTheme = storedTheme;
            if (storedTheme === 'auto') {
                appliedTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-bs-theme', appliedTheme);
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        })();
    </script>

    @vite(['resources/scss/theme.scss', 'resources/js/dashboard.js', 'resources/js/app.js'])

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
    @include('partials.global-search')

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Flatpickr CDN -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Shared App Utilities JS -->
    <script src="{{ asset('js/app-utils.js') }}"></script>
    <script src="{{ asset('js/global-search.js') }}"></script>

    @if (session('welcome'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof showSuccessToast === 'function') {
                    showSuccessToast("{{ session('welcome') }}");
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: "{{ session('welcome') }}",
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true
                    });
                }
            });
        </script>
    @elseif (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof showSuccessToast === 'function') {
                    showSuccessToast("{{ session('success') }}");
                }
            });
        </script>
    @endif

    {{ $scripts ?? '' }}
    @stack('scripts')
</body>

</html>
