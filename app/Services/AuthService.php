<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Session;
use App\Repositories\CustomerRepository;
use App\Repositories\AdminRepository;
use App\Repositories\AuditLogRepository;

class AuthService
{
    private CustomerRepository $customerRepo;
    private AdminRepository $adminRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->customerRepo = new CustomerRepository();
        $this->adminRepo = new AdminRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    /**
     * Customer Registration
     */
    public function registerCustomer(array $data): array
    {
        $email = mb_strtolower(trim($data['email'] ?? ''));
        $name = trim($data['name'] ?? '');
        $password = $data['password'] ?? '';
        $phone = trim($data['phone'] ?? '');

        if (empty($name)) {
            return ['success' => false, 'error' => 'Please enter your full name.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Please provide a valid email address.'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'error' => 'Password must be at least 6 characters long.'];
        }

        if ($this->customerRepo->findByEmail($email)) {
            return ['success' => false, 'error' => 'An account with this email address already exists.'];
        }

        $customer = $this->customerRepo->create([
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'phone' => $phone,
            'is_active' => true,
            'addresses' => [],
        ]);

        // Auto login
        $this->loginCustomerSession($customer);

        return ['success' => true, 'customer' => $customer];
    }

    /**
     * Customer Login
     */
    public function loginCustomer(string $email, string $password): array
    {
        $email = mb_strtolower(trim($email));
        $customer = $this->customerRepo->findByEmail($email);

        if (!$customer || !password_verify($password, $customer['password_hash'] ?? '')) {
            return ['success' => false, 'error' => 'Invalid email address or password.'];
        }

        if (empty($customer['is_active'])) {
            return ['success' => false, 'error' => 'Your account has been deactivated. Please contact support.'];
        }

        $this->loginCustomerSession($customer);
        return ['success' => true, 'customer' => $customer];
    }

    private function loginCustomerSession(array $customer): void
    {
        Session::regenerate();
        Session::set('customer', [
            'id' => $customer['id'],
            'name' => $customer['name'],
            'email' => $customer['email'],
            'phone' => $customer['phone'] ?? '',
        ]);
    }

    public function logoutCustomer(): void
    {
        Session::remove('customer');
        Session::regenerate();
    }

    /**
     * Admin Login with rate limiting
     */
    public function loginAdmin(string $identifier, string $password, string $ip): array
    {
        $rateLimitKey = 'admin_login_attempts_' . md5($ip);
        $attempts = Session::get($rateLimitKey, 0);
        $lastAttemptTime = Session::get($rateLimitKey . '_time', time());

        // Reset attempts if 15 minutes have passed
        if (time() - $lastAttemptTime > 900) {
            $attempts = 0;
            Session::set($rateLimitKey, 0);
        }

        if ($attempts >= 5) {
            $remaining = 900 - (time() - $lastAttemptTime);
            $minutes = max(1, ceil($remaining / 60));
            return ['success' => false, 'error' => "Too many failed login attempts. Please try again in {$minutes} minutes."];
        }

        $admin = $this->adminRepo->findByUsername($identifier) ?? $this->adminRepo->findByEmail($identifier);

        if (!$admin || !password_verify($password, $admin['password_hash'] ?? '')) {
            $attempts++;
            Session::set($rateLimitKey, $attempts);
            Session::set($rateLimitKey . '_time', time());
            return ['success' => false, 'error' => 'Invalid administrator credentials.'];
        }

        if (empty($admin['is_active'])) {
            return ['success' => false, 'error' => 'This administrator account is disabled.'];
        }

        // Reset rate limit on success
        Session::remove($rateLimitKey);
        Session::remove($rateLimitKey . '_time');

        // Regenerate session
        Session::regenerate();
        Session::set('admin', [
            'id' => $admin['id'],
            'name' => $admin['name'],
            'username' => $admin['username'],
            'email' => $admin['email'],
            'role' => $admin['role'] ?? 'Super Admin',
        ]);

        $this->auditRepo->record('admin_login', $admin['username'], "Logged in from {$ip}", $ip);

        return ['success' => true, 'admin' => $admin];
    }

    public function logoutAdmin(): void
    {
        $admin = Session::get('admin');
        if ($admin) {
            $this->auditRepo->record('admin_logout', $admin['username'] ?? 'admin', 'Logged out');
        }
        Session::remove('admin');
        Session::regenerate();
    }
}

