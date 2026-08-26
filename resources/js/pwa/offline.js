export function initOfflineDetection() {
    const networkToast = document.getElementById('pwaNetworkToast');
    const networkMsg = document.getElementById('pwaNetworkMsg');
    const networkIcon = document.getElementById('pwaNetworkIcon');

    let offlineTimer = null;

    function showStatusToast(isOnline) {
        if (!networkToast || !networkMsg || !networkIcon) return;

        clearTimeout(offlineTimer);

        if (isOnline) {
            networkToast.className = 'pwa-toast pwa-network-toast pwa-toast-success';
            networkIcon.className = 'fa-solid fa-wifi me-2';
            networkMsg.textContent = 'Network connection restored';

            // Auto-hide success toast after 3.5s
            offlineTimer = setTimeout(() => {
                networkToast.classList.add('d-none');
            }, 3500);
        } else {
            networkToast.className = 'pwa-toast pwa-network-toast pwa-toast-warning';
            networkIcon.className = 'fa-solid fa-wifi-slash me-2';
            networkMsg.textContent = 'Working offline. Some features may be unavailable.';
            networkToast.classList.remove('d-none');
        }
    }

    window.addEventListener('online', () => showStatusToast(true));
    window.addEventListener('offline', () => showStatusToast(false));

    // Initial status check if already offline on load
    if (!navigator.onLine) {
        showStatusToast(false);
    }
}
