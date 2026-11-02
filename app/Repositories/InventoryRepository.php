<?php
declare(strict_types=1);

namespace App\Repositories;

class InventoryRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct('inventory_movements.json', 'mov_');
    }

    public function logMovement(
        string $productId,
        string $productName,
        string $type, // 'Adjustment', 'Sale', 'Restock', 'Return', 'Initial'
        int $quantityChange,
        int $stockBefore,
        int $stockAfter,
        string $reference = '',
        string $notes = '',
        string $performedBy = 'System'
    ): array {
        return $this->create([
            'product_id' => $productId,
            'product_name' => $productName,
            'type' => $type,
            'quantity_change' => $quantityChange,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'reference' => $reference,
            'notes' => $notes,
            'performed_by' => $performedBy,
        ]);
    }

    public function getRecent(int $limit = 50): array
    {
        $movements = $this->all();
        usort($movements, fn($a, $b) => strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now'));
        return array_slice($movements, 0, $limit);
    }
}

