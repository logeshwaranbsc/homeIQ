<?php
$baseUrl = 'http://localhost:8000';

$endpoints = [
    // Static assets
    '/assets/css/admin.css' => ['code' => 200, 'type' => 'text/css'],
    '/assets/css/storefront.css' => ['code' => 200, 'type' => 'text/css'],
    '/assets/js/admin.js' => ['code' => 200, 'type' => 'application/javascript'],
    '/assets/js/storefront.js' => ['code' => 200, 'type' => 'application/javascript'],
    '/assets/images/placeholder-lock.svg' => ['code' => 200, 'type' => 'image/svg+xml'],
    
    // Storefront HTML pages
    '/' => ['code' => 200, 'check' => 'storefront.css'],
    '/shop' => ['code' => 200, 'check' => 'storefront.css'],
    '/products' => ['code' => 200, 'check' => 'storefront.css'],
    '/cart' => ['code' => 200, 'check' => 'storefront.css'],
    '/checkout' => ['code' => 302], // Redirects to cart when empty
    '/order-tracking' => ['code' => 200, 'check' => 'storefront.css'],
    '/about' => ['code' => 200, 'check' => 'storefront.css'],
    '/contact' => ['code' => 200, 'check' => 'storefront.css'],
    '/faq' => ['code' => 200, 'check' => 'storefront.css'],
    '/shipping-policy' => ['code' => 200, 'check' => 'storefront.css'],
    '/returns-policy' => ['code' => 200, 'check' => 'storefront.css'],
    '/privacy-policy' => ['code' => 200, 'check' => 'storefront.css'],
    '/terms' => ['code' => 200, 'check' => 'storefront.css'],
    
    // Admin HTML pages
    '/admin/login' => ['code' => 200, 'check' => 'admin.css'],
    '/admin' => ['code' => 302],
    '/admin/dashboard' => ['code' => 302],
];

$allPassed = true;

foreach ($endpoints as $path => $expected) {
    $ch = curl_init($baseUrl . $path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    $passed = true;
    if ($code !== $expected['code']) {
        $passed = false;
    }
    if (isset($expected['type']) && !str_starts_with($type, $expected['type'])) {
        $passed = false;
    }
    if (isset($expected['check']) && !str_contains($body, $expected['check'])) {
        $passed = false;
    }

    $statusStr = $passed ? '[PASS]' : '[FAIL]';
    echo sprintf("%s %-35s Code: %d (exp %d) Type: %s Size: %d\n",
        $statusStr, $path, $code, $expected['code'], $type, strlen($body)
    );
    if (!$passed) {
        $allPassed = false;
    }
}

if ($allPassed) {
    echo "\n>>> ALL 17 ENDPOINTS AND STATIC ASSETS VERIFIED WITH 100% SUCCESS <<<\n";
} else {
    echo "\n>>> SOME CHECKS FAILED <<<\n";
    exit(1);
}
