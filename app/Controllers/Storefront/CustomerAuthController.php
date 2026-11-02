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

    public function loginForm(Request $request): void
    {
        $redirect = (string)$request->query('redirect', '');
        if ($redirect) {
            Session::set('redirect_after_login', $redirect);
        }

        if (is_customer_logged_in()) {
            redirect($redirect ?: 'account');
        }

        echo View::render('storefront/login', [
            'title' => 'Customer Login | HOMEIQ',
            'redirect' => $redirect,
        ]);
    }

    public function login(Request $request): void
    {
        $email = trim((string)$request->post('email', ''));
        $password = (string)$request->post('password', '');
        $redirectUrl = (string)$request->post('redirect', $request->query('redirect', Session::get('redirect_after_login') ?? ''));

        if ($redirectUrl) {
            Session::set('redirect_after_login', $redirectUrl);
        }

        if ($request->isAjax()) {
            if (empty($email) || empty($password)) {
                json_response(['success' => false, 'error' => 'Please enter your email and password.'], 400);
            }

            $result = $this->authService->loginCustomer($email, $password);
            if (!$result['success']) {
                json_response(['success' => false, 'error' => $result['error']], 401);
            }

            $customerRepo = new \App\Repositories\CustomerRepository();
            $fullCustomer = $customerRepo->find($result['customer']['id']) ?: $result['customer'];

            $finalRedirect = Session::get('redirect_after_login') ?? 'checkout';
            Session::remove('redirect_after_login');

            json_response([
                'success' => true,
                'message' => "Welcome back, {$result['customer']['name']}!",
                'customer' => [
                    'id' => $fullCustomer['id'],
                    'name' => $fullCustomer['name'],
                    'email' => $fullCustomer['email'],
                    'phone' => $fullCustomer['phone'] ?? '',
                    'addresses' => $fullCustomer['addresses'] ?? [],
                ],
                'redirect' => url($finalRedirect)
            ]);
        }

        if (empty($email) || empty($password)) {
            flash('error', 'Please enter your email and password.');
            redirect($redirectUrl ? 'login?redirect=' . urlencode($redirectUrl) : 'login');
        }

        $result = $this->authService->loginCustomer($email, $password);

        if (!$result['success']) {
            flash('error', $result['error']);
            redirect($redirectUrl ? 'login?redirect=' . urlencode($redirectUrl) : 'login');
        }

        flash('success', "Welcome back, {$result['customer']['name']}!");
        $finalRedirect = Session::get('redirect_after_login') ?? 'account';
        Session::remove('redirect_after_login');
        redirect($finalRedirect);
    }

    public function registerForm(Request $request): void
    {
        $redirect = (string)$request->query('redirect', '');
        if ($redirect) {
            Session::set('redirect_after_login', $redirect);
        }

        if (is_customer_logged_in()) {
            redirect($redirect ?: 'account');
        }

        echo View::render('storefront/register', [
            'title' => 'Create Account | HOMEIQ',
            'redirect' => $redirect,
        ]);
    }

    public function register(Request $request): void
    {
        $redirectUrl = (string)$request->post('redirect', $request->query('redirect', Session::get('redirect_after_login') ?? ''));

        $data = [
            'name' => trim((string)$request->post('name', '')),
            'email' => trim((string)$request->post('email', '')),
            'phone' => trim((string)$request->post('phone', '')),
            'password' => (string)$request->post('password', ''),
        ];

        $confirmPassword = (string)$request->post('password_confirmation', '');
        if ($data['password'] !== $confirmPassword) {
            flash('error', 'Passwords do not match.');
            redirect($redirectUrl ? 'register?redirect=' . urlencode($redirectUrl) : 'register');
        }

        $result = $this->authService->registerCustomer($data);

        if (!$result['success']) {
            flash('error', $result['error']);
            redirect($redirectUrl ? 'register?redirect=' . urlencode($redirectUrl) : 'register');
        }

        flash('success', 'Your HOMEIQ account has been created successfully!');
        $finalRedirect = $redirectUrl ?: (Session::get('redirect_after_login') ?? 'account');
        Session::remove('redirect_after_login');
        redirect($finalRedirect);
    }

    public function logout(Request $request): void
    {
        $redirect = (string)$request->query('redirect', 'login');
        $this->authService->logoutCustomer();
        flash('info', 'You have been logged out.');
        redirect($redirect);
    }

    public function forgotPasswordForm(Request $request): void
    {
        if (is_customer_logged_in()) {
            redirect('account');
        }

        $redirect = (string)$request->query('redirect', '');

        echo View::render('storefront/forgot_password', [
            'title' => 'Forgot Password | HOMEIQ',
            'redirectTarget' => $redirect,
        ]);
    }

    public function sendResetLink(Request $request): void
    {
        $email = trim((string)$request->post('email', ''));
        $redirect = (string)$request->post('redirect', '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Please enter a valid email address.');
            redirect('forgot-password' . ($redirect ? '?redirect=' . urlencode($redirect) : ''));
        }

        $result = $this->authService->generatePasswordReset($email);

        if (!$result['success']) {
            flash('error', $result['error']);
            redirect('forgot-password' . ($redirect ? '?redirect=' . urlencode($redirect) : ''));
        }

        $resetUrl = url('reset-password?token=' . urlencode($result['token']) . '&email=' . urlencode($result['email']) . ($redirect ? '&redirect=' . urlencode($redirect) : ''));
        $customerName = $result['customer']['name'] ?? 'Valued Customer';

        $mailService = new \App\Services\MailService();
        $mailResult = $mailService->sendPasswordResetEmail($result['email'], $customerName, $resetUrl);

        echo View::render('storefront/forgot_password_sent', [
            'title' => 'Password Reset Link Ready | HOMEIQ',
            'email' => $result['email'],
            'token' => $result['token'],
            'resetUrl' => $resetUrl,
            'mailResult' => $mailResult,
            'redirectTarget' => $redirect,
        ]);
    }

    public function resetPasswordForm(Request $request): void
    {
        if (is_customer_logged_in()) {
            redirect('account');
        }

        $token = trim((string)$request->query('token', ''));
        $email = trim((string)$request->query('email', ''));
        $redirect = (string)$request->query('redirect', '');

        if (empty($token) || empty($email)) {
            flash('error', 'Invalid or missing password reset link. Please request a new one.');
            redirect('forgot-password');
        }

        $validation = $this->authService->validateResetToken($email, $token);
        if (!$validation['success']) {
            flash('error', $validation['error']);
            redirect('forgot-password');
        }

        echo View::render('storefront/reset_password', [
            'title' => 'Set New Password | HOMEIQ',
            'token' => $token,
            'email' => $email,
            'redirectTarget' => $redirect,
        ]);
    }

    public function resetPassword(Request $request): void
    {
        $token = trim((string)$request->post('token', ''));
        $email = trim((string)$request->post('email', ''));
        $password = (string)$request->post('password', '');
        $passwordConfirm = (string)$request->post('password_confirmation', '');
        $redirect = (string)$request->post('redirect', '');

        if ($password !== $passwordConfirm) {
            flash('error', 'New passwords do not match. Please try again.');
            redirect('reset-password?token=' . urlencode($token) . '&email=' . urlencode($email) . ($redirect ? '&redirect=' . urlencode($redirect) : ''));
        }

        if (strlen($password) < 6) {
            flash('error', 'Password must be at least 6 characters long.');
            redirect('reset-password?token=' . urlencode($token) . '&email=' . urlencode($email) . ($redirect ? '&redirect=' . urlencode($redirect) : ''));
        }

        $result = $this->authService->resetCustomerPassword($email, $token, $password);

        if (!$result['success']) {
            flash('error', $result['error']);
            redirect('forgot-password');
        }

        flash('success', 'Your password has been reset successfully! Please sign in with your new password.');
        redirect('login' . ($redirect ? '?redirect=' . urlencode($redirect) : ''));
    }
}

