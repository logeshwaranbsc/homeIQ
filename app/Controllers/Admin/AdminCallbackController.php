<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\CallbackLeadRepository;
use App\Repositories\CallbackSettingsRepository;
use App\Repositories\AdminRepository;
use App\Services\CallbackAuditService;

class AdminCallbackController
{
    private CallbackLeadRepository $repo;
    private CallbackSettingsRepository $settingsRepo;
    private CallbackAuditService $auditService;
    private AdminRepository $adminRepo;

    public function __construct()
    {
        $this->repo = new CallbackLeadRepository();
        $this->settingsRepo = new CallbackSettingsRepository();
        $this->auditService = new CallbackAuditService();
        $this->adminRepo = new AdminRepository();
    }

    /**
     * Callback Enquiries Listing & Dashboard Summary
     */
    public function index(Request $request): void
    {
        $filters = [
            'q' => trim((string)$request->query('q', '')),
            'status' => trim((string)$request->query('status', 'ALL')),
            'product_interest' => trim((string)$request->query('product_interest', 'ALL')),
            'assigned_to' => trim((string)$request->query('assigned_to', 'ALL')),
            'start_date' => trim((string)$request->query('start_date', '')),
            'end_date' => trim((string)$request->query('end_date', '')),
        ];

        $counts = $this->repo->getCounts();
        $filtered = $this->repo->filter($filters);

        // Settings for product options dropdown
        $settings = $this->settingsRepo->get();
        $productOptions = $settings['product_options'] ?? [
            'Smart Door Lock',
            'Video Door Phone (VDP)',
            'Retrofit Smart Module',
            'Other Products'
        ];

        // Staff members for filter
        $staffMembers = $this->adminRepo->all();

        // Pagination
        $page = max(1, (int)$request->query('page', 1));
        $perPage = 15;
        $totalItems = count($filtered);
        $totalPages = max(1, (int)ceil($totalItems / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;
        $pagedLeads = array_slice($filtered, $offset, $perPage);

        echo View::render('admin/callback_enquiries/index', [
            'title' => 'Callback Enquiries & Leads | HOMEIQ Admin',
            'leads' => $pagedLeads,
            'counts' => $counts,
            'filters' => $filters,
            'productOptions' => $productOptions,
            'staffMembers' => $staffMembers,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total_items' => $totalItems,
                'total_pages' => $totalPages,
            ]
        ], 'admin');
    }

    /**
     * View Details of a Single Callback Enquiry
     */
    public function show(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $lead = $this->repo->find($id);

        if (!$lead) {
            flash('error', "Callback enquiry '{$id}' was not found.");
            redirect('admin/callback-enquiries');
            return;
        }

        $auditTrail = $this->auditService->getForLead($id);
        $staffMembers = $this->adminRepo->all();
        $matchedOrders = $this->repo->findMatchingOrders((string)($lead['mobile_number'] ?? ''));

        echo View::render('admin/callback_enquiries/view', [
            'title' => "Enquiry {$id} | HOMEIQ Admin",
            'lead' => $lead,
            'auditTrail' => $auditTrail,
            'staffMembers' => $staffMembers,
            'matchedOrders' => $matchedOrders,
        ], 'admin');
    }

    /**
     * Update Lead Status, Notes, Assignment, and Follow-up Date
     */
    public function update(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $lead = $this->repo->find($id);

        if (!$lead) {
            flash('error', "Callback enquiry '{$id}' was not found.");
            redirect('admin/callback-enquiries');
            return;
        }

        $admin = auth_admin();
        $adminName = $admin['name'] ?? ($admin['email'] ?? 'Administrator');

        $status = strtoupper(trim((string)$request->input('status', $lead['status'] ?? 'NEW')));
        $notes = trim((string)$request->input('admin_notes', ''));
        $assignedTo = trim((string)$request->input('assigned_to', ''));
        $followUpDate = trim((string)$request->input('follow_up_date', ''));

        $validStatuses = [
            'NEW',
            'CONTACTED',
            'FOLLOW-UP REQUIRED',
            'CONVERTED',
            'NOT INTERESTED',
            'UNREACHABLE'
        ];

        if (!in_array($status, $validStatuses, true)) {
            flash('error', 'Invalid lead status selected.');
            redirect("admin/callback-enquiries/view/{$id}");
            return;
        }

        // Verification guard for CONVERTED:
        // Do not automatically mark leads as converted without checking order verification
        if ($status === 'CONVERTED') {
            $orders = $this->repo->findMatchingOrders((string)($lead['mobile_number'] ?? ''));
            $orderRef = trim((string)$request->input('order_reference', ''));
            if (empty($orders) && empty($orderRef)) {
                // If no matching order was found automatically, require a confirmed order number / note
                flash('warning', 'Notice: No recent online orders were found for mobile ' . htmlspecialchars((string)$lead['mobile_number']) . '. Please ensure order details are noted.');
            }
        }

        $updates = [
            'status' => $status,
            'admin_notes' => $notes,
            'assigned_to' => $assignedTo !== '' ? $assignedTo : null,
            'follow_up_date' => $followUpDate !== '' ? $followUpDate : null,
        ];

        $oldStatus = $lead['status'] ?? 'NEW';
        $statusChanged = ($oldStatus !== $status);

        $actionNote = $statusChanged
            ? "Status changed from {$oldStatus} to {$status}."
            : "Follow-up details updated.";

        if ($assignedTo !== ($lead['assigned_to'] ?? '')) {
            $actionNote .= " Assigned to: " . ($assignedTo ?: 'Unassigned') . ".";
        }
        if ($followUpDate !== ($lead['follow_up_date'] ?? '')) {
            $actionNote .= " Follow-up date: " . ($followUpDate ?: 'None') . ".";
        }
        if ($notes !== '') {
            $actionNote .= " Notes: {$notes}";
        }

        $updated = $this->repo->update($id, $updates, $adminName, $actionNote);

        if ($updated) {
            $this->auditService->record(
                $id,
                $statusChanged ? "STATUS_{$status}" : 'UPDATED',
                $adminName,
                $actionNote
            );
            flash('success', "Lead {$id} updated successfully!");
        } else {
            flash('error', "Failed to update lead {$id}.");
        }

        redirect("admin/callback-enquiries/view/{$id}");
    }

    /**
     * Admin Settings View
     */
    public function settings(Request $request): void
    {
        $settings = $this->settingsRepo->get();

        echo View::render('admin/callback_enquiries/settings', [
            'title' => 'Callback Popup Settings | HOMEIQ Admin',
            'settings' => $settings,
        ], 'admin');
    }

    /**
     * Update Callback Settings
     */
    public function updateSettings(Request $request): void
    {
        $enabled = (bool)$request->input('enabled', false);
        $delay = max(5, (int)$request->input('trigger_delay_seconds', 60));
        $heading = trim((string)$request->input('heading', ''));
        $desc = trim((string)$request->input('description', ''));
        $btnText = trim((string)$request->input('button_text', ''));
        $rawOptions = (string)$request->input('product_options_text', '');

        $options = array_values(array_filter(array_map('trim', explode("\n", $rawOptions))));
        if (empty($options)) {
            $options = [
                'Smart Door Lock',
                'Video Door Phone (VDP)',
                'Retrofit Smart Module',
                'Other Products'
            ];
        }

        $data = [
            'enabled' => $enabled,
            'trigger_delay_seconds' => $delay,
            'heading' => $heading !== '' ? $heading : 'Need Help Before You Buy?',
            'description' => $desc !== '' ? $desc : 'Let our product expert help you choose the right product.',
            'button_text' => $btnText !== '' ? $btnText : 'REQUEST A CALLBACK',
            'product_options' => $options,
        ];

        $saved = $this->settingsRepo->save($data);

        $admin = auth_admin();
        $adminName = $admin['name'] ?? 'Administrator';

        if ($saved) {
            $this->auditService->record(
                'SYSTEM',
                'SETTINGS_UPDATED',
                $adminName,
                "Updated callback popup settings (Enabled: " . ($enabled ? 'Yes' : 'No') . ", Delay: {$delay}s)."
            );
            flash('success', 'Callback popup settings saved successfully!');
        } else {
            flash('error', 'Failed to save callback popup settings.');
        }

        redirect('admin/callback-enquiries/settings');
    }
}
