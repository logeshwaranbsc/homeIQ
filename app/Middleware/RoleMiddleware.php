<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Session;

class RoleMiddleware
{
    private array $allowedRoles;

    public function __construct(array $allowedRoles = ['Super Admin'])
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function handle(Request $request): bool
    {
        if (!is_admin_logged_in()) {
            redirect('admin/login');
            return false;
        }

        $admin = auth_admin();
        $userRole = $admin['role'] ?? '';

        // Super Admin always has full access
        if ($userRole === 'Super Admin' || in_array($userRole, $this->allowedRoles, true)) {
            return true;
        }

        Session::setFlash('error', 'Access denied. You do not have permission to access that section.');
        redirect('admin/dashboard');
        return false;
    }
}

