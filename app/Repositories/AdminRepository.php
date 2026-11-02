<?php
declare(strict_types=1);

namespace App\Repositories;

class AdminRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct('admins.json', 'adm_');
    }

    public function findByUsername(string $username): ?array
    {
        $u = mb_strtolower(trim($username));
        $items = $this->all();
        foreach ($items as $item) {
            if (mb_strtolower($item['username'] ?? '') === $u) {
                return $item;
            }
        }
        return null;
    }

    public function findByEmail(string $email): ?array
    {
        $e = mb_strtolower(trim($email));
        $items = $this->all();
        foreach ($items as $item) {
            if (mb_strtolower($item['email'] ?? '') === $e) {
                return $item;
            }
        }
        return null;
    }
}

