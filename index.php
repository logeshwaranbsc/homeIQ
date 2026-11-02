<?php
declare(strict_types=1);

/**
 * HOMEIQ Root Gateway & Development Server Router
 * Ensures static files and routes are served properly in all environments:
 * PHP built-in server (php -S), Apache (XAMPP/WAMP), Nginx, and production.
 */

$rawUri = $_SERVER['REQUEST_URI'] ?? '/';
$uri = parse_url($rawUri, PHP_URL_PATH) ?: '/';
$uri = rawurldecode($uri);

// Prevent directory traversal
$cleanUri = '/' . ltrim(str_replace(['../', '..\\'], '', $uri), '/');

// Check if static file exists in public/ or root
$publicFile = __DIR__ . '/public' . $cleanUri;
$rootFile = __DIR__ . $cleanUri;

$targetFile = null;
if (is_file($publicFile)) {
    $targetFile = $publicFile;
} elseif (is_file($rootFile) && !str_ends_with($rootFile, '.php')) {
    $targetFile = $rootFile;
}

if ($targetFile !== null && is_file($targetFile)) {
    $ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Never serve sensitive files
    if (in_array($ext, ['php', 'phtml', 'json', 'env', 'git', 'lock', 'md', 'bat', 'sh'], true)) {
        http_response_code(403);
        echo "403 Forbidden";
        exit;
    }

    $mimes = [
        'css'   => 'text/css; charset=UTF-8',
        'js'    => 'application/javascript; charset=UTF-8',
        'mjs'   => 'application/javascript; charset=UTF-8',
        'svg'   => 'image/svg+xml',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'webp'  => 'image/webp',
        'ico'   => 'image/x-icon',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'eot'   => 'application/vnd.ms-fontobject',
        'pdf'   => 'application/pdf',
        'txt'   => 'text/plain; charset=UTF-8',
        'map'   => 'application/json',
    ];

    $mime = $mimes[$ext] ?? (function_exists('mime_content_type') ? mime_content_type($targetFile) : 'application/octet-stream');
    
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($targetFile));
    header('Cache-Control: public, max-age=86400');
    header('Access-Control-Allow-Origin: *');
    readfile($targetFile);
    exit;
}

// Forward to public front controller
require_once __DIR__ . '/public/index.php';
