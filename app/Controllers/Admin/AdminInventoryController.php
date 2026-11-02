<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\ProductRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\AuditLogRepository;

class AdminInventoryController
{
    private ProductRepository $productRepo;
    private InventoryRepository $inventoryRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->inventoryRepo = new InventoryRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function index(): void
    {
        $products = $this->productRepo->all();
        $movements = $this->inventoryRepo->getRecent(30);

        $lowStockCount = count($this->productRepo->getLowStock(5));
        $outOfStockCount = count($this->productRepo->getOutOfStock());

        echo View::render('admin/inventory/index', [
            'title' => 'Inventory Management | HOMEIQ Admin',
            'products' => $products,
            'movements' => $movements,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
        ], 'admin');
    }

    public function adjust(Request $request): void
    {
        $productId = (string)$request->post('product_id', '');
        $product = $this->productRepo->find($productId);

        if (!$product) {
            flash('error', 'Product not found.');
            redirect('admin/inventory');
        }

        $adjustmentType = (string)$request->post('adjustment_type', 'Restock'); // 'Restock', 'Adjustment', 'Damage', 'Return'
        $quantity = (int)$request->post('quantity', 0);
        $notes = trim((string)$request->post('notes', ''));

        if ($quantity === 0) {
            flash('error', 'Adjustment quantity cannot be zero.');
            redirect('admin/inventory');
        }

        $stockBefore = (int)($product['stock'] ?? 0);
        $stockAfter = max(0, $stockBefore + $quantity);

        $this->productRepo->update($productId, ['stock' => $stockAfter]);

        $admin = auth_admin();
        $this->inventoryRepo->logMovement(
            productId: $productId,
            productName: $product['name'],
            type: $adjustmentType,
            quantityChange: $quantity,
            stockBefore: $stockBefore,
            stockAfter: $stockAfter,
            reference: 'Manual Adjustment',
            notes: $notes ?: "Manual inventory adjustment ({$adjustmentType})",
            performedBy: $admin['name'] ?? 'Admin'
        );

        $this->auditRepo->record('stock_adjusted', $admin['username'] ?? 'Admin', "Adjusted stock for {$product['name']} from {$stockBefore} to {$stockAfter} ({$adjustmentType})");

        flash('success', "Stock updated for '{$product['name']}'. New stock: {$stockAfter} units.");
        redirect('admin/inventory');
    }
}

