<?php
declare(strict_types=1);

use App\Core\App;
use App\Core\Session;

/**
 * Read environment variable from .env or server environment
 */
function env(string $key, mixed $default = null): mixed
{
    static $envVars = null;

    if ($envVars === null) {
        $envVars = [];
        $envFile = App::getBasePath() . DIRECTORY_SEPARATOR . '.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || str_starts_with($line, '#')) {
                    continue;
                }
                if (str_contains($line, '=')) {
                    [$k, $v] = explode('=', $line, 2);
                    $k = trim($k);
                    $v = trim($v, " \t\n\r\0\x0B\"'");
                    $envVars[$k] = $v;
                }
            }
        }
    }

    if (array_key_exists($key, $envVars)) {
        return $envVars[$key];
    }
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    $val = getenv($key);
    return ($val !== false) ? $val : $default;
}

/**
 * Get configuration value
 */
function config(string $key, mixed $default = null): mixed
{
    $parts = explode('.', $key);
    $config = App::getConfig();
    
    foreach ($parts as $part) {
        if (!is_array($config) || !array_key_exists($part, $config)) {
            return $default;
        }
        $config = $config[$part];
    }
    
    return $config;
}

/**
 * Escape HTML output to prevent XSS
 */
function e(mixed $value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Format currency price (Indian Rupee formatting: e.g. ₹19,999)
 */
function format_price(float|int|string|null $amount): string
{
    $num = (float)($amount ?? 0);
    $currency = config('app.currency', '₹');
    
    // Format according to standard Indian numbering (e.g. 1,00,000 or standard comma)
    return $currency . ' ' . number_format($num, 0, '.', ',');
}

/**
 * Calculate discount percentage
 */
function discount_percentage(float|int $mrp, float|int $salePrice): int
{
    if ($mrp <= 0 || $salePrice >= $mrp) {
        return 0;
    }
    return (int) round((($mrp - $salePrice) / $mrp) * 100);
}

/**
 * Build application URL
 */
function url(string $path = ''): string
{
    $base = App::getBaseUrl();
    $path = ltrim($path, '/');
    if ($path === '') {
        return $base ?: '/';
    }
    return rtrim($base, '/') . '/' . $path;
}

/**
 * Build asset URL
 */
function asset(string $path): string
{
    $cleanPath = ltrim($path, '/');
    $fullPath = \App\Core\App::getBasePath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleanPath);
    $ver = file_exists($fullPath) ? (string)filemtime($fullPath) : '1.0';
    return url('assets/' . $cleanPath) . '?v=' . $ver;
}

/**
 * Build uploads URL
 */
function upload_url(?string $path, string $fallback = 'assets/images/placeholder-lock.svg'): string
{
    if (empty($path)) {
        return asset($fallback);
    }
    // If it's already an absolute URL or starts with http
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    return url('uploads/' . ltrim($path, '/'));
}

/**
 * Generate URL slug from string
 */
function slugify(string $text): string
{
    // Replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text) ?: $text;
    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim
    $text = trim($text, '-');
    // Remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // Lowercase
    $text = strtolower($text);

    return empty($text) ? 'item-' . time() : $text;
}

/**
 * Generate a unique ID
 */
function generate_id(string $prefix = ''): string
{
    return $prefix . bin2hex(random_bytes(6)) . dechex(time());
}

/**
 * CSRF token helpers
 */
function csrf_token(): string
{
    return Session::getCsrfToken();
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Session Flash helper
 */
function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        Session::setFlash($key, $message);
        return null;
    }
    return Session::getFlash($key);
}

/**
 * Get old input value from flash
 */
function old(string $key, mixed $default = ''): mixed
{
    $oldInput = Session::getFlash('old_input') ?? [];
    return $oldInput[$key] ?? $default;
}

/**
 * Redirect to a specific URL
 */
function redirect(string $path, int $status = 302): never
{
    $targetUrl = str_starts_with($path, 'http') ? $path : url($path);
    header('Location: ' . $targetUrl, true, $status);
    exit;
}

/**
 * Send JSON response
 */
function json_response(mixed $data, int $status = 200): never
{
    header('Content-Type: application/json; charset=UTF-8', true, $status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Current customer user
 */
function auth_customer(): ?array
{
    return Session::get('customer');
}

/**
 * Check if customer is logged in
 */
function is_customer_logged_in(): bool
{
    return !empty(Session::get('customer'));
}

/**
 * Current admin user
 */
function auth_admin(): ?array
{
    return Session::get('admin');
}

/**
 * Check if admin is logged in
 */
function is_admin_logged_in(): bool
{
    return !empty(Session::get('admin'));
}

/**
 * Check if logged-in admin has a specific role or is Super Admin
 */
function admin_has_role(string|array $roles): bool
{
    $admin = auth_admin();
    if (!$admin) {
        return false;
    }
    $userRole = $admin['role'] ?? '';
    if ($userRole === 'Super Admin') {
        return true;
    }
    if (is_array($roles)) {
        return in_array($userRole, $roles, true);
    }
    return $userRole === $roles;
}

