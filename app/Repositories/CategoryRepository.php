<?php
declare(strict_types=1);

namespace App\Repositories;

class CategoryRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct('categories.json', 'cat_');
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function getActive(): array
    {
        $categories = $this->filter(fn($c) => !empty($c['is_active']));
        // Sort by display order
        usort($categories, fn($a, $b) => ($a['display_order'] ?? 0) <=> ($b['display_order'] ?? 0));
        return $categories;
    }
}

