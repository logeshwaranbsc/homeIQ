<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Services\AuthService;

class AdminAuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function loginForm(): void
    {
        if (is_admin_logged_in()) {
            redirect('admin/dashboard');
        }

        echo View::render('admin/login', [
            'title' => 'Admin Sign In | HOMEIQ Management',
        ], 'auth');
    }

    public function login(Request $request): void
    {
        $identifier = trim((string)$request->post('identifier', ''));
        $password = (string)$request->post('password', '');

        if (empty($identifier) || empty($password)) {
            flash('error', 'Please enter your username/email and password.');
            redirect('admin/login');
        }

        $result = $this->authService->loginAdmin($identifier, $password, $request->ip());

        if (!$result['success']) {
            flash('error', $result['error']);
            redirect('admin/login');
        }

        flash('success', "Signed in successfully as {$result['admin']['name']}.");
        redirect('admin/dashboard');
    }

    public function logout(): void
    {
        $this->authService->logoutAdmin();
        flash('info', 'You have been signed out of the admin panel.');
        redirect('admin/login');
    }
}

