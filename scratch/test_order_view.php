<?php
$baseDir = dirname(__DIR__);
require_once $baseDir . '/app/Core/App.php';
require_once $baseDir . '/app/Helpers/helpers.php';

use App\Core\App;
use App\Core\View;
use App\Repositories\OrderRepository;

App::init($baseDir);

$orderRepo = new OrderRepository();
$orders = $orderRepo->all();
$order = $orders[0] ?? null;

echo "Testing admin/orders/view with order " . ($order['order_number'] ?? 'none') . "...\n";
$html = View::render('admin/orders/view', ['order' => $order], 'admin');
echo "Rendered length: " . strlen($html) . "\n";
echo "Has doctype: " . (str_contains($html, '<!DOCTYPE html>') ? 'YES' : 'NO') . "\n";
echo "Has admin-sidebar: " . (str_contains($html, 'admin-sidebar') ? 'YES' : 'NO') . "\n";
