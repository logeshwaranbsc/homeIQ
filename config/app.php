<?php
declare(strict_types=1);

/**
 * HOMEIQ Smart Locks E-Commerce
 * Application Configuration
 */

// Determine base paths
$baseDir = dirname(__DIR__);
$storageDir = $baseDir . DIRECTORY_SEPARATOR . 'storage';
$publicDir = $baseDir . DIRECTORY_SEPARATOR . 'public';
$uploadDir = $publicDir . DIRECTORY_SEPARATOR . 'uploads';

return [
    'app' => [
        'name' => 'HOMEIQ',
        'tagline' => 'Advanced Security. Keyless Convenience.',
        'env' => 'production', // 'development' or 'production'
        'debug' => false,
        'url' => '', // Automatically detected if empty
        'currency' => '₹',
        'currency_code' => 'INR',
        'timezone' => 'Asia/Kolkata',
    ],
    
    'paths' => [
        'base' => $baseDir,
        'storage' => $storageDir,
        'public' => $publicDir,
        'uploads' => $uploadDir,
        'views' => $baseDir . DIRECTORY_SEPARATOR . 'views',
    ],

    'session' => [
        'name' => 'homeiq_session',
        'lifetime' => 86400 * 7, // 7 days
        'secure' => false, // Set to true if HTTPS is strictly enforced
        'httponly' => true,
        'samesite' => 'Lax',
    ],

    'security' => [
        'csrf_token_key' => '_csrf_token',
        'rate_limit_login_attempts' => 5,
        'rate_limit_lockout_seconds' => 900, // 15 minutes
    ],

    'shipping' => [
        'free_shipping_threshold' => 5000, // Orders ₹5000+ get free shipping
        'flat_rate' => 250,
        'free_installation_threshold' => 15000,
    ],

    'upload' => [
        'max_file_size' => 5 * 1024 * 1024, // 5MB
        'allowed_mimes' => ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'],
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp', 'svg'],
    ],

    'brand' => [
        'support_email' => 'support@homeiq-security.com',
        'support_phone' => '+91 98765 43210',
        'address' => 'HOMEIQ Technologies Pvt Ltd, 402 Smart Tech Park, Cyber City, Bengaluru 560100',
        'social' => [
            'instagram' => 'https://instagram.com/homeiq_security',
            'facebook' => 'https://facebook.com/homeiqlocks',
            'youtube' => 'https://youtube.com/@homeiqsecurity',
        ]
    ]
];

