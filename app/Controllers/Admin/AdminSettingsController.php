<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\SettingsRepository;
use App\Repositories\AuditLogRepository;

class AdminSettingsController
{
    private SettingsRepository $settingsRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->settingsRepo = new SettingsRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function index(): void
    {
        $settings = $this->settingsRepo->all();

        echo View::render('admin/settings/index', [
            'title' => 'System & Store Settings | HOMEIQ Admin',
            'settings' => $settings,
        ], 'admin');
    }

    public function update(Request $request): void
    {
        $storeName = trim((string)$request->post('store_name', 'HOMEIQ'));
        $tagline = trim((string)$request->post('tagline', ''));
        $supportEmail = trim((string)$request->post('support_email', ''));
        $supportPhone = trim((string)$request->post('support_phone', ''));
        $address = trim((string)$request->post('address', ''));
        $freeShippingMin = (float)$request->post('free_shipping_min', 5000);
        $flatShippingRate = (float)$request->post('flat_shipping_rate', 250);
        $enableCod = (bool)$request->post('enable_cod', false);
        $announcementBarEnabled = (bool)$request->post('announcement_bar_enabled', false);

        $announcementsRaw = (string)$request->post('announcements', '');
        $announcements = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $announcementsRaw)))));

        $this->settingsRepo->save([
            'store_name' => $storeName,
            'tagline' => $tagline,
            'support_email' => $supportEmail,
            'support_phone' => $supportPhone,
            'address' => $address,
            'free_shipping_min' => $freeShippingMin,
            'flat_shipping_rate' => $flatShippingRate,
            'enable_cod' => $enableCod,
            'announcement_bar_enabled' => $announcementBarEnabled,
            'announcements' => $announcements,
        ]);

        $admin = auth_admin();
        $this->auditRepo->record('settings_updated', $admin['username'] ?? 'Admin', 'Updated system and store settings');

        flash('success', 'Store configuration and announcement bar updated successfully.');
        redirect('admin/settings');
    }
}

