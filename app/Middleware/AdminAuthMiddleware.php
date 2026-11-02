<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Session;

class AdminAuthMiddleware
{
    public function handle(Request $request): bool
    {
        if (!is_admin_logged_in()) {
            Session::setFlash('error', 'Administrator authentication required.');
            redirect('admin/login');
            return false;
        }
        return true;
    }
}

