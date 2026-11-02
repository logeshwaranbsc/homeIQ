<?php
declare(strict_types=1);

namespace App\Repositories;

class BannerRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct('banners.json', 'bnr_');
    }

    public function getActive(): array
    {
        $banners = $this->filter(fn($b) => !empty($b['is_active']));
        usort($banners, fn($a, $b) => ($a['display_order'] ?? 0) <=> ($b['display_order'] ?? 0));
        return $banners;
    }
}

