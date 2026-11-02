<?php
$baseDir = dirname(__DIR__);
require_once $baseDir . '/app/Core/App.php';
require_once $baseDir . '/app/Helpers/helpers.php';

use App\Core\App;
use App\Core\View;
use App\Repositories\CategoryRepository;

App::init($baseDir);
$categoryRepo = new CategoryRepository();
$categories = $categoryRepo->all();

echo "Testing admin/products/form CREATE...\n";
$html = View::render('admin/products/form', [
    'title' => 'Add New Digital Lock',
    'product' => null,
    'categories' => $categories
], 'admin');

echo "admin/products/form CREATE rendered successfully, length: " . strlen($html) . "\n";
echo "Has doctype: " . (str_contains($html, '<!DOCTYPE html>') ? 'YES' : 'NO') . "\n";
echo "Has admin sidebar: " . (str_contains($html, 'admin-sidebar') ? 'YES' : 'NO') . "\n";
