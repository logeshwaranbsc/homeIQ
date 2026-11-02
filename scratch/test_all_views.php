<?php
echo "STARTING TEST VIEWS...\n";
$baseDir = dirname(__DIR__);
require_once $baseDir . '/app/Core/App.php';
require_once $baseDir . '/app/Helpers/helpers.php';

use App\Core\App;
use App\Core\View;

App::init($baseDir);
echo "APP INITIALIZED\n";

try {
    $html = View::render('storefront/pages/about', [], 'storefront');
    echo "About rendered, length: " . strlen($html) . "\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
