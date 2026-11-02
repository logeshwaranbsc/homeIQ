<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(Request $request): bool
    {
        if (!is_customer_logged_in()) {
            Session::setFlash('error', 'Please log in to your account to continue.');
            Session::set('redirect_after_login', $request->getUri());
            redirect('login');
            return false;
        }
        return true;
    }
}

