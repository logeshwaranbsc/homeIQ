<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Session;

class CsrfMiddleware
{
    public function handle(Request $request): bool
    {
        if ($request->isGet()) {
            return true;
        }

        $token = $request->post('_csrf_token') ?? 
                 $request->header('X-CSRF-TOKEN') ?? 
                 ($request->json()['_csrf_token'] ?? null);

        if (!Session::validateCsrfToken($token)) {
            if ($request->isAjax()) {
                http_response_code(419);
                echo json_encode(['success' => false, 'error' => 'Security token expired. Please refresh the page.']);
                exit;
            }
            Session::setFlash('error', 'Your session expired. Please try submitting again.');
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return false;
        }

        return true;
    }
}

