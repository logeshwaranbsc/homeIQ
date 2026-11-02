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
            $path = $request->getPath();
            $isCheckout = str_contains($path, 'checkout');
            $msg = $isCheckout 
                ? 'Please sign in or create an account to complete your purchase.' 
                : 'Please log in to your account to continue.';
            Session::setFlash('error', $msg);
            $redirectTarget = $isCheckout ? 'checkout' : ltrim($request->getUri(), '/');
            Session::set('redirect_after_login', $redirectTarget);
            redirect('login?redirect=' . urlencode($redirectTarget));
            return false;
        }
        return true;
    }
}

