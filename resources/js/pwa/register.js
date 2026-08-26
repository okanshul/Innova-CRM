import { notifyUpdateAvailable, handleControllerChange } from './update.js';

export function registerServiceWorker() {
    if (!('serviceWorker' in navigator)) {
        console.log('[PWA] Service workers are not supported by this browser.');
        return;
    }

    const config = window.pwaConfig || {};
    const swUrl = config.swUrl || '/sw.js';

    window.addEventListener('load', () => {
        navigator.serviceWorker.register(swUrl, { scope: '/' })
            .then((registration) => {
                console.log('[PWA] ServiceWorker registered with scope:', registration.scope);

                // Handle update checking
                registration.addEventListener('updatefound', () => {
                    const installingWorker = registration.installing;
                    if (!installingWorker) return;

                    installingWorker.addEventListener('statechange', () => {
                        if (installingWorker.state === 'installed') {
                            if (navigator.serviceWorker.controller) {
                                console.log('[PWA] New content is available; showing update notification.');
                                notifyUpdateAvailable(installingWorker);
                            } else {
                                console.log('[PWA] Content is cached for offline use.');
                            }
                        }
                    });
                });
            })
            .catch((error) => {
                console.error('[PWA] ServiceWorker registration failed:', error);
            });

        handleControllerChange();
    });
}
