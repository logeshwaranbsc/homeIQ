<?php
declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Core\Request;
use App\Core\View;
use App\Core\Session;
use App\Services\AuthService;

class CustomerAuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function loginForm(): void
    {
        if (is_customer_logged_in()) {
            redirect('account');
        }

        echo View::render('storefront/login', [
            'title' => 'Customer Login | HOMEIQ',
        ]);
    }

    public function login(Request $request): void
    {
        $email = trim((string)$request->post('email', ''));
        $password = (string)$request->post('password', '');

        if (empty($email) || empty($password)) {
            flash('error', 'Please enter your email and password.');
            redirect('login');
        }

        $result = $this->authService->loginCustomer($email, $password);

        if (!$result['success']) {
            flash('error', $result['error']);
            redirect('login');
        }

        flash('success', "Welcome back, {$result['customer']['name']}!");
        $redirectUrl = Session::get('redirect_after_login') ?? 'account';
        Session::remove('redirect_after_login');
        redirect($redirectUrl);
    }

    public function registerForm(): void
    {
        if (is_customer_logged_in()) {
            redirect('account');
        }

        echo View::render('storefront/register', [
            'title' => 'Create Account | HOMEIQ',
        ]);
    }

    public function register(Request $request): void
    {
        $data = [
            'name' => trim((string)$request->post('name', '')),
            'email' => trim((string)$request->post('email', '')),
            'phone' => trim((string)$request->post('phone', '')),
            'password' => (string)$request->post('password', ''),
        ];

        $confirmPassword = (string)$request->post('password_confirmation', '');
        if ($data['password'] !== $confirmPassword) {
            flash('error', 'Passwords do not match.');
            redirect('register');
        }

        $result = $this->authService->registerCustomer($data);

        if (!$result['success']) {
            flash('error', $result['error']);
            redirect('register');
        }

        flash('success', 'Your HOMEIQ account has been created successfully!');
        redirect('account');
    }

    public function logout(): void
    {
        $this->authService->logoutCustomer();
        flash('info', 'You have been logged out.');
        redirect('login');
    }
}

