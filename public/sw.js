// Service Worker for PinePix PWA
const CACHE_NAME = 'pinepix-v1';
const RUNTIME_CACHE = 'pinepix-runtime-v1';

// Assets to cache on install
const STATIC_ASSETS = [
    '/',
    '/index.php',
    '/dashboard.php',
    '/assets/css/main.css',
    '/assets/css/custom.css',
    '/assets/js/main.js',
    '/favicon.ico',
    '/favicon-96x96.png',
    '/favicon.svg',
    '/apple-touch-icon.png',
    '/site.webmanifest'
];

// Install event - cache static assets
self.addEventListener('install', (event) => {
    console.log('[Service Worker] Installing...');
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[Service Worker] Caching static assets');
                return cache.addAll(STATIC_ASSETS.map(url => new Request(url, { cache: 'reload' })));
            })
            .then(() => self.skipWaiting())
            .catch((error) => {
                console.error('[Service Worker] Cache install failed:', error);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    console.log('[Service Worker] Activating...');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME && cacheName !== RUNTIME_CACHE) {
                        console.log('[Service Worker] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip cross-origin requests
    if (url.origin !== location.origin) {
        return;
    }

    // Skip API requests (they need to be fresh)
    if (url.pathname.startsWith('/api/')) {
        return;
    }

    // Skip auth pages (they need to be fresh)
    if (url.pathname.startsWith('/auth/')) {
        return;
    }

    // For navigation requests, try network first, then cache
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    // Clone the response
                    const responseToCache = response.clone();
                    caches.open(RUNTIME_CACHE).then((cache) => {
                        cache.put(request, responseToCache);
                    });
                    return response;
                })
                .catch(() => {
                    return caches.match(request).then((cachedResponse) => {
                        if (cachedResponse) {
                            return cachedResponse;
                        }
                        // Fallback to offline page if available
                        return caches.match('/index.php');
                    });
                })
        );
        return;
    }

    // For static assets, try cache first, then network
    event.respondWith(
        caches.match(request)
            .then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return fetch(request).then((response) => {
                    // Don't cache non-successful responses
                    if (!response || response.status !== 200 || response.type !== 'basic') {
                        return response;
                    }
                    const responseToCache = response.clone();
                    caches.open(RUNTIME_CACHE).then((cache) => {
                        cache.put(request, responseToCache);
                    });
                    return response;
                });
            })
            .catch(() => {
                // Return offline fallback for images
                if (request.destination === 'image') {
                    return new Response('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect width="200" height="200" fill="#f59e0b"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="white" font-size="20">PinePix</text></svg>', {
                        headers: { 'Content-Type': 'image/svg+xml' }
                    });
                }
            })
    );
});

// Handle background sync (for future offline form submissions)
self.addEventListener('sync', (event) => {
    if (event.tag === 'background-sync') {
        console.log('[Service Worker] Background sync triggered');
        // Implement background sync logic here
    }
});

// Handle push notifications (for future implementation)
self.addEventListener('push', (event) => {
    console.log('[Service Worker] Push notification received');
    const options = {
        body: event.data ? event.data.text() : 'New update available',
        icon: '/favicon-96x96.png',
        badge: '/favicon-96x96.png',
        vibrate: [200, 100, 200],
        tag: 'pinepix-notification',
        requireInteraction: false
    };
    event.waitUntil(
        self.registration.showNotification('PinePix', options)
    );
});

// Handle notification clicks
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    event.waitUntil(
        clients.openWindow('/')
    );
});

