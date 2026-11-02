<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderRepository;
use App\Repositories\AuditLogRepository;

class AdminCustomerController
{
    private CustomerRepository $customerRepo;
    private OrderRepository $orderRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->customerRepo = new CustomerRepository();
        $this->orderRepo = new OrderRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function index(Request $request): void
    {
        $q = trim((string)$request->query('q', ''));
        $customers = $this->customerRepo->all();

        if (!empty($q)) {
            $needle = mb_strtolower($q);
            $customers = array_filter($customers, function ($c) use ($needle) {
                return str_contains(mb_strtolower($c['name'] ?? ''), $needle) ||
                       str_contains(mb_strtolower($c['email'] ?? ''), $needle) ||
                       str_contains(mb_strtolower($c['phone'] ?? ''), $needle);
            });
        }

        echo View::render('admin/customers/index', [
            'title' => 'Customer Management | HOMEIQ Admin',
            'customers' => array_values($customers),
            'q' => $q,
        ], 'admin');
    }

    public function show(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $customer = $this->customerRepo->find($id);

        if (!$customer) {
            flash('error', 'Customer not found.');
            redirect('admin/customers');
        }

        $orders = $this->orderRepo->findByCustomer($id);

        echo View::render('admin/customers/view', [
            'title' => 'Customer Details: ' . $customer['name'] . ' | HOMEIQ Admin',
            'customer' => $customer,
            'orders' => $orders,
        ], 'admin');
    }

    public function toggleStatus(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $customer = $this->customerRepo->find($id);

        if ($customer) {
            $newStatus = empty($customer['is_active']);
            $this->customerRepo->update($id, ['is_active' => $newStatus]);
            $admin = auth_admin();
            $this->auditRepo->record('customer_status_toggle', $admin['username'] ?? 'Admin', "Changed status of {$customer['email']} to " . ($newStatus ? 'Active' : 'Inactive'));
            flash('success', "Customer status changed to " . ($newStatus ? 'Active' : 'Deactivated') . '.');
        }

        redirect('admin/customers');
    }
}

