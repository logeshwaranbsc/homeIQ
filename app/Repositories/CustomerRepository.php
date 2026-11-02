<?php
declare(strict_types=1);

namespace App\Repositories;

class CustomerRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct('customers.json', 'cust_');
    }

    public function findByEmail(string $email): ?array
    {
        $email = mb_strtolower(trim($email));
        $items = $this->all();
        foreach ($items as $item) {
            if (mb_strtolower($item['email'] ?? '') === $email) {
                return $item;
            }
        }
        return null;
    }
}

