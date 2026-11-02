<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$baseDir = dirname(__DIR__);
require_once $baseDir . '/app/Core/App.php';
require_once $baseDir . '/app/Helpers/helpers.php';
use App\Core\App;
use App\Core\View;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;

App::init($baseDir);

try {
    $productRepo = new ProductRepository();
    $categoryRepo = new CategoryRepository();

    $products = $productRepo->all();
    $product = $products[0] ?? null;
    $categories = $categoryRepo->all();

    $html = View::render('admin/products/form', [
        'title' => 'Edit ' . ($product['name'] ?? 'Product') . ' | HOMEIQ Admin',
        'product' => $product,
        'categories' => $categories,
    ], 'admin');

    echo "SUCCESS: Length is " . strlen($html) . "\n";
    echo "First 200 chars:\n" . substr($html, 0, 200) . "\n";
} catch (\Throwable $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
