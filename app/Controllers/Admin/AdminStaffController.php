<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\AdminRepository;
use App\Repositories\AuditLogRepository;

class AdminStaffController
{
    private AdminRepository $adminRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->adminRepo = new AdminRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function index(): void
    {
        $admins = $this->adminRepo->all();
        $currentAdmin = auth_admin();

        echo View::render('admin/staff/index', [
            'title' => 'Staff & Role Management | HOMEIQ Admin',
            'admins' => $admins,
            'currentAdmin' => $currentAdmin,
            'roles' => ['Super Admin', 'Product Manager', 'Order Manager', 'Inventory Manager', 'Content Manager'],
        ], 'admin');
    }

    public function create(): void
    {
        echo View::render('admin/staff/form', [
            'title' => 'Create Staff Account | HOMEIQ Admin',
            'adminUser' => null,
            'roles' => ['Super Admin', 'Product Manager', 'Order Manager', 'Inventory Manager', 'Content Manager'],
        ], 'admin');
    }

    public function store(Request $request): void
    {
        $name = trim((string)$request->post('name', ''));
        $username = mb_strtolower(trim((string)$request->post('username', '')));
        $email = mb_strtolower(trim((string)$request->post('email', '')));
        $role = (string)$request->post('role', 'Product Manager');
        $password = (string)$request->post('password', '');

        if (empty($name) || empty($username) || empty($email) || empty($password)) {
            flash('error', 'All fields are required.');
            redirect('admin/staff/create');
        }

        if (strlen($password) < 8) {
            flash('error', 'Password must be at least 8 characters long.');
            redirect('admin/staff/create');
        }

        if ($this->adminRepo->findByUsername($username) || $this->adminRepo->findByEmail($email)) {
            flash('error', 'An admin with this username or email already exists.');
            redirect('admin/staff/create');
        }

        $created = $this->adminRepo->create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'role' => $role,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'is_active' => true,
        ]);

        $currentAdmin = auth_admin();
        $this->auditRepo->record('staff_created', $currentAdmin['username'] ?? 'Admin', "Created staff member {$username} with role {$role}");

        flash('success', "Staff account for '{$name}' created successfully.");
        redirect('admin/staff');
    }

    public function edit(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $adminUser = $this->adminRepo->find($id);

        if (!$adminUser) {
            flash('error', 'Staff member not found.');
            redirect('admin/staff');
        }

        echo View::render('admin/staff/form', [
            'title' => 'Edit Staff Member: ' . $adminUser['name'] . ' | HOMEIQ Admin',
            'adminUser' => $adminUser,
            'roles' => ['Super Admin', 'Product Manager', 'Order Manager', 'Inventory Manager', 'Content Manager'],
        ], 'admin');
    }

    public function update(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $adminUser = $this->adminRepo->find($id);

        if (!$adminUser) {
            flash('error', 'Staff member not found.');
            redirect('admin/staff');
        }

        $name = trim((string)$request->post('name', ''));
        $email = mb_strtolower(trim((string)$request->post('email', '')));
        $role = (string)$request->post('role', $adminUser['role']);
        $password = (string)$request->post('password', '');
        $isActive = (bool)$request->post('is_active', true);

        $updateData = [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'is_active' => $isActive,
        ];

        if (!empty($password)) {
            if (strlen($password) < 8) {
                flash('error', 'New password must be at least 8 characters long.');
                redirect("admin/staff/edit/{$id}");
            }
            $updateData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->adminRepo->update($id, $updateData);

        $currentAdmin = auth_admin();
        $this->auditRepo->record('staff_updated', $currentAdmin['username'] ?? 'Admin', "Updated staff account {$adminUser['username']}");

        flash('success', "Staff account '{$name}' updated.");
        redirect('admin/staff');
    }

    public function delete(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $currentAdmin = auth_admin();

        if ($currentAdmin['id'] === $id) {
            flash('error', 'You cannot delete your own active administrator account.');
            redirect('admin/staff');
            return;
        }

        $target = $this->adminRepo->find($id);
        if ($target) {
            $this->adminRepo->delete($id);
            $this->auditRepo->record('staff_deleted', $currentAdmin['username'] ?? 'Admin', "Deleted staff member {$target['username']}");
            flash('success', 'Staff account deleted.');
        }

        redirect('admin/staff');
    }
}

