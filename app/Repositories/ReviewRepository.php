<?php
declare(strict_types=1);

namespace App\Repositories;

class ReviewRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct('reviews.json', 'rev_');
    }

    public function findByProduct(string $productId, bool $approvedOnly = true): array
    {
        $reviews = $this->filter(function ($r) use ($productId, $approvedOnly) {
            $matchProd = ($r['product_id'] ?? '') === $productId;
            if (!$matchProd) {
                return false;
            }
            if ($approvedOnly && empty($r['is_approved'])) {
                return false;
            }
            return true;
        });

        usort($reviews, fn($a, $b) => strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now'));
        return $reviews;
    }

    public function getPending(): array
    {
        return $this->filter(fn($r) => empty($r['is_approved']));
    }
}

