<?php
declare(strict_types=1);

namespace App\Repositories;

class CouponRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct('coupons.json', 'cpn_');
    }

    public function findByCode(string $code): ?array
    {
        $code = strtoupper(trim($code));
        $items = $this->all();
        foreach ($items as $item) {
            if (strtoupper($item['code'] ?? '') === $code) {
                return $item;
            }
        }
        return null;
    }
}

