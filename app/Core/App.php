<?php
declare(strict_types=1);

namespace App\Core;

class App
{
    private static ?array $config = null;
    private static ?string $basePath = null;
    private static ?string $baseUrl = null;
    private static ?Router $router = null;

    public static function init(string $basePath): void
    {
        self::$basePath = rtrim($basePath, DIRECTORY_SEPARATOR);

        // Register class autoloader
        spl_autoload_register([self::class, 'autoload']);

        // Load configuration
        $configFile = self::$basePath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'app.php';
        if (file_exists($configFile)) {
            self::$config = require $configFile;
        } else {
            self::$config = [];
        }

        // Set timezone
        date_default_timezone_set(self::$config['app']['timezone'] ?? 'Asia/Kolkata');

        // Configure error reporting
        if (self::$config['app']['debug'] ?? false) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(0);
            ini_set('display_errors', '0');
        }

        // Include helpers
        $helpersFile = self::$basePath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'helpers.php';
        if (file_exists($helpersFile)) {
            require_once $helpersFile;
        }

        // Start session
        Session::start();
    }

    public static function autoload(string $className): void
    {
        // Only autoload classes in App\ namespace
        if (!str_starts_with($className, 'App\\')) {
            return;
        }

        $relativeClass = substr($className, 4); // Remove 'App\'
        $file = self::$basePath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 
                str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }

    public static function getRouter(): Router
    {
        if (self::$router === null) {
            self::$router = new Router();
        }
        return self::$router;
    }

    public static function getConfig(): array
    {
        return self::$config ?? [];
    }

    public static function getBasePath(): string
    {
        return self::$basePath ?: dirname(__DIR__, 2);
    }

    public static function getBaseUrl(): string
    {
        if (self::$baseUrl !== null) {
            return self::$baseUrl;
        }

        $configuredUrl = self::$config['app']['url'] ?? '';
        if (!empty($configuredUrl)) {
            self::$baseUrl = rtrim($configuredUrl, '/');
            return self::$baseUrl;
        }

        // In CLI mode, default to http://localhost:8000
        if (PHP_SAPI === 'cli') {
            self::$baseUrl = 'http://localhost:8000';
            return self::$baseUrl;
        }

        // Auto-detect base URL for web requests
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 80) == 443 ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
        
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $dir = dirname($script);
        
        // Clean directory path
        if ($dir === '/' || $dir === '\\' || $dir === '.' || empty($dir)) {
            $dir = '';
        } else {
            $dir = '/' . trim(str_replace('\\', '/', $dir), '/');
            // If the script is inside public/, strip public from URL
            if ($dir === '/public') {
                $dir = '';
            }
        }

        self::$baseUrl = rtrim($protocol . $host . $dir, '/');
        return self::$baseUrl;
    }

    public static function run(): void
    {
        $request = new Request();
        self::getRouter()->dispatch($request);
    }
}
