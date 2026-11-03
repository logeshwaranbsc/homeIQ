<?php
declare(strict_types=1);

namespace App\Repositories;

class ReviewRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct('reviews.json', 'rev_');
    }

    /**
     * Get reviews submitted for a specific product
     */
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

    /**
     * Get general site/platform reviews only (for homepage / testimonials)
     * Excludes individual product-specific reviews.
     */
    public function getSiteReviews(bool $approvedOnly = true): array
    {
        $reviews = $this->filter(function ($r) use ($approvedOnly) {
            if ($approvedOnly && empty($r['is_approved'])) {
                return false;
            }
            // Explicit site review flag or type
            if (!empty($r['is_site_review']) || ($r['review_type'] ?? '') === 'site') {
                return true;
            }
            // If explicitly marked as product review, exclude from site reviews
            if (isset($r['is_site_review']) && $r['is_site_review'] === false) {
                return false;
            }
            if (($r['review_type'] ?? '') === 'product') {
                return false;
            }
            // Legacy reviews where product_id was a mock key (prod_1, prod_2) or empty
            if (empty($r['product_id']) || in_array($r['product_id'], ['prod_1', 'prod_2', 'site'], true)) {
                return true;
            }
            return false;
        });

        usort($reviews, fn($a, $b) => strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now'));
        return $reviews;
    }

    public function getPending(): array
    {
        return $this->filter(fn($r) => empty($r['is_approved']));
    }
}
