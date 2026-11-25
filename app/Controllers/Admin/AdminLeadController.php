<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\ContactMessageRepository;
use App\Repositories\AuditLogRepository;

class AdminLeadController
{
    private ContactMessageRepository $leadRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->leadRepo = new ContactMessageRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function index(Request $request): void
    {
        $type = (string)$request->query('type', 'all');
        $status = (string)$request->query('status', 'all');
        $q = trim((string)$request->query('q', ''));

        $leads = $this->leadRepo->filterLeads($type, $status, $q);
        $allLeads = $this->leadRepo->all();

        $stats = [
            'total' => count($allLeads),
            'bulk' => $this->leadRepo->getBulkCount(),
            'unread' => $this->leadRepo->getUnreadCount(),
            'closed' => count(array_filter($allLeads, fn($m) => ($m['status'] ?? '') === 'closed')),
        ];

        echo View::render('admin/leads/index', [
            'title' => 'Leads & Bulk Orders | HOMEIQ Admin',
            'leads' => $leads,
            'stats' => $stats,
            'currentType' => $type,
            'currentStatus' => $status,
            'q' => $q,
        ], 'admin');
    }

    public function show(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $lead = $this->leadRepo->find($id);

        if (!$lead) {
            flash('error', 'Inquiry / Lead record not found.');
            redirect('admin/leads');
        }

        echo View::render('admin/leads/view', [
            'title' => 'Inquiry #' . $lead['id'] . ' | HOMEIQ Admin',
            'lead' => $lead,
        ], 'admin');
    }

    public function updateStatus(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $lead = $this->leadRepo->find($id);

        if (!$lead) {
            flash('error', 'Lead record not found.');
            redirect('admin/leads');
        }

        $newStatus = trim((string)$request->post('status', 'unread'));
        $validStatuses = ['unread', 'contacted', 'in_progress', 'closed'];

        if (!in_array($newStatus, $validStatuses, true)) {
            $newStatus = 'unread';
        }

        $adminNote = trim((string)$request->post('admin_notes', ''));

        $updateData = [
            'status' => $newStatus,
            'admin_notes' => $adminNote,
            'last_handled_at' => date('Y-m-d H:i:s'),
        ];

        $this->leadRepo->update($id, $updateData);

        $admin = auth_admin();
        $adminName = $admin['username'] ?? 'Admin';
        $leadType = ($lead['inquiry_type'] ?? 'general') === 'bulk' ? 'Bulk Order' : 'General Lead';
        $this->auditRepo->record(
            'lead_status_updated',
            $adminName,
            "Updated {$leadType} #{$id} status to '{$newStatus}'"
        );

        flash('success', "Lead status updated to " . ucfirst(str_replace('_', ' ', $newStatus)) . ".");
        redirect('admin/leads/view/' . urlencode($id));
    }

    public function delete(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $lead = $this->leadRepo->find($id);

        if ($lead) {
            $this->leadRepo->delete($id);
            $admin = auth_admin();
            $adminName = $admin['username'] ?? 'Admin';
            $this->auditRepo->record(
                'lead_deleted',
                $adminName,
                "Deleted Lead #{$id} ({$lead['name']})"
            );
            flash('success', 'Lead record removed permanently.');
        } else {
            flash('error', 'Lead not found or already deleted.');
        }

        redirect('admin/leads');
    }
}
