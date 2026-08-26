export function initInstallPrompt() {
    const config = window.pwaConfig || {};
    if (config.enableInstallPrompt === false) {
        return;
    }

    let deferredPrompt = null;

    const installModal = document.getElementById('pwaInstallModal');
    const iosModal = document.getElementById('pwaIosModal');
    const installBtn = document.getElementById('pwaInstallBtn');
    const dismissBtn = document.getElementById('pwaDismissBtn');
    const closeBtn = document.getElementById('pwaCloseBtn');
    const iosCloseBtn = document.getElementById('pwaIosCloseBtn');
    const iosDismissBtn = document.getElementById('pwaIosDismissBtn');

    // Helper to check if running in standalone mode
    function isStandalone() {
        return (
            window.matchMedia('(display-mode: standalone)').matches ||
            window.navigator.standalone === true ||
            document.referrer.includes('android-app://')
        );
    }

    // Helper to check if install prompt was recently dismissed
    function isDismissed() {
        const dismissedAt = localStorage.getItem('pwa_install_dismissed');
        if (!dismissedAt) return false;

        const dismissalDays = config.dismissalDays || 7;
        const expiryTime = parseInt(dismissedAt, 10) + dismissalDays * 24 * 60 * 60 * 1000;
        return Date.now() < expiryTime;
    }

    // Record dismissal
    function recordDismissal() {
        localStorage.setItem('pwa_install_dismissed', Date.now().toString());
    }

    // Hide Modals
    function hideInstallModal() {
        if (installModal) installModal.classList.add('d-none');
    }

    function hideIosModal() {
        if (iosModal) iosModal.classList.add('d-none');
    }

    // Show Install Modal
    function showInstallModal() {
        if (isStandalone() || isDismissed()) return;
        if (installModal) {
            installModal.classList.remove('d-none');
            installModal.setAttribute('aria-hidden', 'false');
        }
    }

    // Show iOS Modal
    function showIosModal() {
        if (isStandalone() || isDismissed()) return;
        if (iosModal) {
            iosModal.classList.remove('d-none');
            iosModal.setAttribute('aria-hidden', 'false');
        }
    }

    // Detect iOS Safari
    function isIosSafari() {
        const ua = window.navigator.userAgent;
        const isIos = /iPhone|iPad|iPod/.test(ua) || (window.navigator.platform === 'MacIntel' && window.navigator.maxTouchPoints > 1);
        const isSafari = /Safari/.test(ua) && !/CriOS|FxiOS|EdgiOS/.test(ua);
        return isIos && isSafari;
    }

    // Intercept beforeinstallprompt for Android / Desktop Chromium
    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent default browser banner
        e.preventDefault();
        deferredPrompt = e;

        // Show custom modal
        showInstallModal();
    });

    // Handle Install Button Click
    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) {
                return;
            }

            hideInstallModal();
            deferredPrompt.prompt();

            const { outcome } = await deferredPrompt.userChoice;
            console.log(`[PWA] Install prompt outcome: ${outcome}`);

            if (outcome === 'accepted') {
                localStorage.removeItem('pwa_install_dismissed');
            } else {
                recordDismissal();
            }

            deferredPrompt = null;
        });
    }

    // Handle Dismiss & Close Buttons
    if (dismissBtn) {
        dismissBtn.addEventListener('click', () => {
            recordDismissal();
            hideInstallModal();
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            recordDismissal();
            hideInstallModal();
        });
    }

    if (iosCloseBtn) {
        iosCloseBtn.addEventListener('click', () => {
            recordDismissal();
            hideIosModal();
        });
    }

    if (iosDismissBtn) {
        iosDismissBtn.addEventListener('click', () => {
            recordDismissal();
            hideIosModal();
        });
    }

    // Listen for successful app installation
    window.addEventListener('appinstalled', () => {
        console.log('[PWA] Application successfully installed.');
        deferredPrompt = null;
        hideInstallModal();
        hideIosModal();
        localStorage.removeItem('pwa_install_dismissed');
    });

    // If on iOS and not standalone, show iOS modal after a brief delay
    if (isIosSafari() && !isStandalone() && !isDismissed()) {
        setTimeout(() => {
            showIosModal();
        }, 2000);
    }
}
