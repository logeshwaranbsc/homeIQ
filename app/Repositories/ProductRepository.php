<?php
declare(strict_types=1);

namespace App\Repositories;

class ProductRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct('products.json', 'prod_');
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function findBySku(string $sku): ?array
    {
        return $this->findBy('sku', $sku);
    }

    public function getActive(): array
    {
        return $this->filter(fn($p) => !empty($p['is_active']));
    }

    public function getFeatured(int $limit = 8): array
    {
        $active = $this->getActive();
        $featured = array_values(array_filter($active, fn($p) => !empty($p['is_featured'])));
        if (count($featured) < $limit) {
            $existingIds = array_column($featured, 'id');
            foreach ($active as $p) {
                if (!in_array($p['id'], $existingIds, true)) {
                    $featured[] = $p;
                    if (count($featured) >= $limit) {
                        break;
                    }
                }
            }
        }
        return array_slice($featured, 0, $limit);
    }

    public function getLowStock(int $threshold = 5): array
    {
        return $this->filter(fn($p) => ($p['stock'] ?? 0) <= $threshold && ($p['stock'] ?? 0) > 0);
    }

    public function getOutOfStock(): array
    {
        return $this->filter(fn($p) => ($p['stock'] ?? 0) <= 0);
    }

    /**
     * Search and filter products
     */
    public function search(array $criteria = []): array
    {
        $products = $this->getActive();

        // Keyword search (name, SKU, short desc, features)
        if (!empty($criteria['q'])) {
            $q = mb_strtolower(trim((string)$criteria['q']));
            $products = array_filter($products, function ($p) use ($q) {
                $name = mb_strtolower($p['name'] ?? '');
                $sku = mb_strtolower($p['sku'] ?? '');
                $desc = mb_strtolower($p['short_description'] ?? '');
                $features = is_array($p['features'] ?? null) ? mb_strtolower(implode(' ', $p['features'])) : '';
                return str_contains($name, $q) || str_contains($sku, $q) || str_contains($desc, $q) || str_contains($features, $q);
            });
        }

        // Category filter
        if (!empty($criteria['category'])) {
            $category = $criteria['category'];
            $products = array_filter($products, function ($p) use ($category) {
                if (($p['category_slug'] ?? '') === $category || ($p['category'] ?? '') === $category) {
                    return true;
                }
                if (($category === 'digital-smart-locks' || $category === 'smart-locks') &&
                    in_array($p['category_slug'] ?? '', ['smart-locks', 'digital-smart-locks'], true)) {
                    return true;
                }
                return false;
            });
        }

        // Price range
        if (isset($criteria['min_price']) && is_numeric($criteria['min_price'])) {
            $min = (float)$criteria['min_price'];
            $products = array_filter($products, fn($p) => (float)($p['sale_price'] ?? $p['price'] ?? 0) >= $min);
        }

        if (isset($criteria['max_price']) && is_numeric($criteria['max_price'])) {
            $max = (float)$criteria['max_price'];
            $products = array_filter($products, fn($p) => (float)($p['sale_price'] ?? $p['price'] ?? 0) <= $max);
        }

        // Access method filter
        if (!empty($criteria['access_method'])) {
            $method = mb_strtolower($criteria['access_method']);
            $products = array_filter($products, function ($p) use ($method) {
                $methods = array_map('mb_strtolower', $p['access_methods'] ?? []);
                return in_array($method, $methods, true);
            });
        }

        // Connectivity filter
        if (!empty($criteria['connectivity'])) {
            $conn = mb_strtolower($criteria['connectivity']);
            $products = array_filter($products, function ($p) use ($conn) {
                $conns = array_map('mb_strtolower', $p['connectivity'] ?? []);
                return in_array($conn, $conns, true);
            });
        }

        // Color filter
        if (!empty($criteria['color'])) {
            $color = mb_strtolower($criteria['color']);
            $products = array_filter($products, function ($p) use ($color) {
                return mb_strtolower($p['color'] ?? '') === $color;
            });
        }

        // Stock status
        if (!empty($criteria['in_stock'])) {
            $products = array_filter($products, fn($p) => ($p['stock'] ?? 0) > 0);
        }

        // Sorting
        $sort = $criteria['sort'] ?? 'newest';
        usort($products, function ($a, $b) use ($sort) {
            $priceA = (float)($a['sale_price'] ?? $a['price'] ?? 0);
            $priceB = (float)($b['sale_price'] ?? $b['price'] ?? 0);
            $dateA = strtotime($a['created_at'] ?? '1970-01-01');
            $dateB = strtotime($b['created_at'] ?? '1970-01-01');
            $ratingA = (float)($a['rating'] ?? 0);
            $ratingB = (float)($b['rating'] ?? 0);

            return match ($sort) {
                'price_asc' => $priceA <=> $priceB,
                'price_desc' => $priceB <=> $priceA,
                'rating' => $ratingB <=> $ratingA,
                'popularity' => ($b['sales_count'] ?? 0) <=> ($a['sales_count'] ?? 0),
                default => $dateB <=> $dateA, // newest
            };
        });

        return array_values($products);
    }
}

