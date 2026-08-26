import { registerServiceWorker } from './register.js';
import { initInstallPrompt } from './install.js';
import { initOfflineDetection } from './offline.js';

export function initPWA() {
    registerServiceWorker();
    initInstallPrompt();
    initOfflineDetection();
}

// Auto init on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPWA);
} else {
    initPWA();
}
