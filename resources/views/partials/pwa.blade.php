<!-- PWA Meta Tags -->
@php
    $pwaManifestFile = public_path('manifest.webmanifest');
    $pwaManifestHref = file_exists($pwaManifestFile)
        ? 'data:application/manifest+json;base64,' . base64_encode(file_get_contents($pwaManifestFile))
        : asset('manifest.webmanifest');
@endphp
<link rel="manifest" href="{{ $pwaManifestHref }}" crossorigin="use-credentials">

<meta name="theme-color" content="{{ config('pwa.theme_color', '#4f46e5') }}">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ config('pwa.short_name', 'InnovaCRM') }}">
<link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/icon-192x192.png') }}">

<!-- PWA Window Configuration -->
<script>
    window.pwaConfig = {
        swUrl: "{{ asset('sw.js') }}",
        appName: "{{ config('pwa.name', 'InnovaCRM') }}",
        shortName: "{{ config('pwa.short_name', 'InnovaCRM') }}",
        dismissalDays: {{ config('pwa.dismissal_days', 7) }},
        enableInstallPrompt: {{ config('pwa.enable_install_prompt', true) ? 'true' : 'false' }},
        enableOffline: {{ config('pwa.enable_offline', true) ? 'true' : 'false' }}
    };
</script>

<!-- Custom PWA Install Bottom Sheet / Centered Modal -->
<div id="pwaInstallModal" class="pwa-modal-overlay d-none" aria-hidden="true" role="dialog" aria-labelledby="pwaModalTitle">
    <div class="pwa-modal-card">
        <button type="button" class="pwa-modal-close" id="pwaCloseBtn" aria-label="Close modal">&times;</button>
        
        <div class="pwa-modal-header">
            <img src="{{ asset('icons/icon-192x192.png') }}" alt="{{ config('pwa.name', 'InnovaCRM') }} Icon" class="pwa-app-icon">
            <div>
                <h3 class="pwa-app-title" id="pwaModalTitle">Install {{ config('pwa.name', 'InnovaCRM') }}</h3>
                <p class="pwa-app-subtitle">Get faster access and a better experience by installing the app.</p>
            </div>
        </div>

        <div class="pwa-benefits-list">
            <div class="pwa-benefit-item">
                <div class="pwa-benefit-icon"><i class="fa-solid fa-bolt"></i></div>
                <div>
                    <strong>Faster Access</strong>
                    <span>Open the app directly from your home screen.</span>
                </div>
            </div>
            <div class="pwa-benefit-item">
                <div class="pwa-benefit-icon"><i class="fa-solid fa-mobile-screen-button"></i></div>
                <div>
                    <strong>App-like Experience</strong>
                    <span>Use the application without browser distractions.</span>
                </div>
            </div>
            <div class="pwa-benefit-item">
                <div class="pwa-benefit-icon"><i class="fa-solid fa-bell"></i></div>
                <div>
                    <strong>Stay Updated</strong>
                    <span>Get the latest features and instant performance updates.</span>
                </div>
            </div>
        </div>

        <div class="pwa-modal-actions">
            <button type="button" class="btn pwa-btn-primary" id="pwaInstallBtn">
                <i class="fa-solid fa-download me-1"></i> Install App
            </button>
            <button type="button" class="btn pwa-btn-secondary" id="pwaDismissBtn">
                Maybe Later
            </button>
        </div>
    </div>
</div>

<!-- iOS Install Instruction Modal -->
<div id="pwaIosModal" class="pwa-modal-overlay d-none" aria-hidden="true" role="dialog">
    <div class="pwa-modal-card pwa-ios-card">
        <button type="button" class="pwa-modal-close" id="pwaIosCloseBtn" aria-label="Close modal">&times;</button>

        <div class="pwa-modal-header">
            <img src="{{ asset('icons/icon-192x192.png') }}" alt="{{ config('pwa.name', 'InnovaCRM') }} Icon" class="pwa-app-icon">
            <div>
                <h3 class="pwa-app-title">Install {{ config('pwa.name', 'InnovaCRM') }}</h3>
                <p class="pwa-app-subtitle">Add to your home screen for quick access on iOS.</p>
            </div>
        </div>

        <ol class="pwa-ios-steps">
            <li>
                <span class="pwa-step-num">1</span>
                <span>Tap the <strong>Share</strong> button <i class="fa-solid fa-share-from-square text-primary ms-1"></i> in Safari bottom bar.</span>
            </li>
            <li>
                <span class="pwa-step-num">2</span>
                <span>Scroll down and select <strong>"Add to Home Screen"</strong> <i class="fa-regular fa-square-plus text-primary ms-1"></i>.</span>
            </li>
            <li>
                <span class="pwa-step-num">3</span>
                <span>Tap <strong>"Add"</strong> in the top right corner.</span>
            </li>
        </ol>

        <div class="pwa-modal-actions mt-3">
            <button type="button" class="btn pwa-btn-secondary w-100" id="pwaIosDismissBtn">
                Got it
            </button>
        </div>
    </div>
</div>

<!-- PWA SW Update Toast -->
<div id="pwaUpdateToast" class="pwa-toast pwa-update-toast d-none" role="alert">
    <div class="pwa-toast-body">
        <i class="fa-solid fa-arrows-rotate pwa-toast-icon me-2"></i>
        <span>A new version is available.</span>
    </div>
    <button type="button" class="btn btn-sm btn-light fw-bold ms-3" id="pwaUpdateBtn">
        Update Now
    </button>
</div>

<!-- PWA Network Status Toast -->
<div id="pwaNetworkToast" class="pwa-toast pwa-network-toast d-none" role="status">
    <i id="pwaNetworkIcon" class="fa-solid fa-wifi me-2"></i>
    <span id="pwaNetworkMsg">Connected</span>
</div>
