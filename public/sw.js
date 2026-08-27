const CACHE_VERSION = 'v1.0.1';
const CACHE_NAME = `innovacrm-${CACHE_VERSION}`;
const OFFLINE_URL = '/offline';

const PRECACHE_ASSETS = [
    OFFLINE_URL,
    '/manifest.webmanifest',
    '/favicon.ico',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
];

// Install Event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(async (cache) => {
            try {
                return await cache.addAll(PRECACHE_ASSETS);
            } catch (err) {
                console.warn('[PWA SW] Pre-cache failed for some assets, caching individually:', err);
                for (const asset of PRECACHE_ASSETS) {
                    try {
                        await cache.add(asset);
                    } catch (e) {
                        console.warn(`[PWA SW] Could not cache ${asset}:`, e);
                    }
                }
            }
        })
    );
    self.skipWaiting();
});

// Activate Event
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName.startsWith('innovacrm-') && cacheName !== CACHE_NAME) {
                        console.log('[PWA SW] Deleting obsolete cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event
self.addEventListener('fetch', (event) => {
    const { request } = event;

    // Only handle GET requests
    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    // Skip non-http/https (chrome-extension, etc.)
    if (!url.protocol.startsWith('http')) {
        return;
    }

    // Skip dynamic non-cacheable routes (e.g. auth actions, livewire, debugbar, API endpoints with sensitive data, user uploaded storage)
    if (
        url.pathname.startsWith('/api/') ||
        url.pathname.startsWith('/logout') ||
        url.pathname.startsWith('/storage/') ||
        url.pathname.includes('sanctum') ||
        url.pathname.includes('_debugbar')
    ) {
        return;
    }

    // Handle HTML Navigation requests (Network First, fallback to offline page)
    if (request.mode === 'navigate' || request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    if (response.status === 200) {
                        const copy = response.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                    }
                    return response;
                })
                .catch(async () => {
                    const cache = await caches.open(CACHE_NAME);
                    const cachedResponse = await cache.match(request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Return the dedicated offline fallback view
                    const offlinePage = await cache.match(OFFLINE_URL);
                    return offlinePage || new Response('Offline', { status: 503, statusText: 'Service Unavailable' });
                })
        );
        return;
    }

    // Handle Static Assets (CSS, JS, Fonts, Images) - Cache First with Stale-While-Revalidate
    const isStaticAsset = (
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'image' ||
        request.destination === 'font' ||
        url.pathname.startsWith('/build/') ||
        url.pathname.startsWith('/css/') ||
        url.pathname.startsWith('/js/') ||
        url.pathname.startsWith('/icons/')
    );

    if (isStaticAsset) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                const fetchPromise = fetch(request).then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const copy = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                    }
                    return networkResponse;
                }).catch(() => cachedResponse);

                return cachedResponse || fetchPromise;
            })
        );
        return;
    }
});

// Message Event (for update handling)
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
