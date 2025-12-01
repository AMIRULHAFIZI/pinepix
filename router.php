<?php
// Router script for PHP built-in server
// Handles routing for public files and assets

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Security: Block access to sensitive files/directories
if (preg_match('#^/(config|\.env|\.git|vendor|\.htaccess|router\.php|composer\.(json|lock))#', $uri)) {
    http_response_code(403);
    exit('Access Forbidden');
}

// Handle service worker (must be served with correct MIME type and no caching)
if ($uri === '/sw.js') {
    $filePath = __DIR__ . '/public/sw.js';
    if (file_exists($filePath)) {
        header('Content-Type: application/javascript');
        header('Service-Worker-Allowed: /');
        // Don't cache service worker
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        readfile($filePath);
        return true;
    }
    http_response_code(404);
    exit('Service Worker not found');
}

// Handle favicon and root-level static files (favicon.ico, apple-touch-icon.png, etc.)
$rootStaticFiles = ['favicon.ico', 'favicon-96x96.png', 'favicon.svg', 'apple-touch-icon.png', 'site.webmanifest', 'web-app-manifest-192x192.png', 'web-app-manifest-512x512.png'];
if (in_array(ltrim($uri, '/'), $rootStaticFiles)) {
    $filePath = __DIR__ . '/public/' . ltrim($uri, '/');
    if (file_exists($filePath) && is_file($filePath)) {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'ico' => 'image/x-icon',
            'png' => 'image/png',
            'svg' => 'image/svg+xml',
            'json' => 'application/json',
            'webmanifest' => 'application/manifest+json',
        ];
        if (isset($mimeTypes[$extension])) {
            header('Content-Type: ' . $mimeTypes[$extension]);
        }
        // Cache favicon files for 1 year
        header('Cache-Control: public, max-age=31536000');
        readfile($filePath);
        return true;
    }
    http_response_code(404);
    exit('File not found');
}

// Handle assets directory (CSS, JS, images) - serve from root assets/
if (preg_match('#^/assets/(.*)$#', $uri, $matches)) {
    $assetPath = __DIR__ . '/assets/' . $matches[1];
    if (file_exists($assetPath) && is_file($assetPath)) {
        // Serve the asset with appropriate content type
        $extension = strtolower(pathinfo($assetPath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
        ];
        if (isset($mimeTypes[$extension])) {
            header('Content-Type: ' . $mimeTypes[$extension]);
        }
        readfile($assetPath);
        return true;
    }
    http_response_code(404);
    exit('Asset not found');
}

// Handle root path - serve index.php
if ($uri === '/' || $uri === '') {
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    require __DIR__ . '/public/index.php';
    return true;
}

// Let PHP server handle existing files in public/ automatically
return false;

