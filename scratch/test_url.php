<?php
$baseDir = dirname(__DIR__);
require_once $baseDir . '/app/Core/App.php';
require_once $baseDir . '/app/Helpers/helpers.php';

use App\Core\App;

App::init($baseDir);

// Case 1: php -S localhost:8000
$_SERVER['HTTPS'] = 'off';
$_SERVER['HTTP_HOST'] = 'localhost:8000';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['REQUEST_URI'] = '/admin/dashboard';
echo "Case 1 asset: " . asset('css/admin.css') . PHP_EOL;

// Case 2: XAMPP / Apache in /HomeiQ
$_SERVER['SCRIPT_NAME'] = '/HomeiQ/index.php';
$_SERVER['REQUEST_URI'] = '/HomeiQ/admin/dashboard';
$prop = new ReflectionProperty('App\Core\App', 'baseUrl');
$prop->setValue(null, null);
echo "Case 2 asset: " . asset('css/admin.css') . PHP_EOL;
