<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\SettingsRepository;
use App\Repositories\AuditLogRepository;
use App\Helpers\FileUploader;

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

        // Homepage Editorial Brand Story Section
        $storyEnabled = (bool)$request->post('story_enabled', false);
        $storyTheme = trim((string)$request->post('story_theme', 'dark'));
        $storyBadge = trim((string)$request->post('story_badge', 'HARDWARE PHILOSOPHY'));
        $storyBadgeIcon = trim((string)$request->post('story_badge_icon', 'bi-cpu'));
        $storyTitle = trim((string)$request->post('story_title', 'A NEW STANDARD IN ARCHITECTURAL DEFENSE'));
        $storySubtitle = trim((string)$request->post('story_subtitle', ''));
        $storyDescription = trim((string)$request->post('story_description', ''));
        $storyMetric1Val = trim((string)$request->post('story_metric1_val', '<0.3s'));
        $storyMetric1Label = trim((string)$request->post('story_metric1_label', 'Sensor Scan'));
        $storyMetric2Val = trim((string)$request->post('story_metric2_val', '9-in-1'));
        $storyMetric2Label = trim((string)$request->post('story_metric2_label', 'Access Modes'));
        $storyMetric3Val = trim((string)$request->post('story_metric3_val', '304'));
        $storyMetric3Label = trim((string)$request->post('story_metric3_label', 'Steel Mortise'));
        $storyMetric4Val = trim((string)$request->post('story_metric4_val', 'AES'));
        $storyMetric4Label = trim((string)$request->post('story_metric4_label', '128-Bit Safe'));
        $storyBtn1Text = trim((string)$request->post('story_btn1_text', 'Explore All Hardware'));
        $storyBtn1Url = trim((string)$request->post('story_btn1_url', 'shop'));
        $storyBtn2Text = trim((string)$request->post('story_btn2_text', 'Read Security Briefing'));
        $storyBtn2Url = trim((string)$request->post('story_btn2_url', 'about'));
        $storyPillText = trim((string)$request->post('story_pill_text', 'Aerospace 6063-T6 • 304 Stainless Steel Mortise'));
        $storyImage = trim((string)$request->post('story_image', 'products/homeiq-ai-face-lock.svg'));

        $imageFile = $request->file('story_image_file');
        if ($imageFile) {
            $upload = FileUploader::upload($imageFile, 'story');
            if (!empty($upload['success']) && !empty($upload['filename'])) {
                $storyImage = $upload['filename'];
            }
        }
        // Sanitize path (strip leading slashes, uploads/ prefix, and duplicate subfolder names)
        $storyImage = ltrim($storyImage, '/\\');
        $storyImage = preg_replace('#^uploads[\\\\/]+#i', '', $storyImage);
        $storyImage = preg_replace('#^story[\\\\/]+story[\\\\/]+#i', 'story/', $storyImage);

        // Homepage Promotional Campaign Banner Section
        $promoEnabled = (bool)$request->post('promo_enabled', false);
        $promoTheme = trim((string)$request->post('promo_theme', 'dark'));
        $promoBadge = trim((string)$request->post('promo_badge', 'VIP PRIVILEGE CAMPAIGN'));
        $promoBadgeIcon = trim((string)$request->post('promo_badge_icon', 'bi-tag-fill'));
        $promoTitle = trim((string)$request->post('promo_title', 'Upgrade to Keyless Living with Coupon WELCOME10'));
        $promoCouponCode = trim((string)$request->post('promo_coupon_code', 'WELCOME10'));
        $promoDescription = trim((string)$request->post('promo_description', ''));
        $promoBtnText = trim((string)$request->post('promo_btn_text', 'Claim Offer & Shop Now'));
        $promoBtnUrl = trim((string)$request->post('promo_btn_url', 'shop'));
        $promoNote = trim((string)$request->post('promo_note', ''));
        $promoPillText = trim((string)$request->post('promo_pill_text', '10% Instant Privilege Discount'));
        $promoImage = trim((string)$request->post('promo_image', 'products/video-door-lock.svg'));

        $promoImageFile = $request->file('promo_image_file');
        if ($promoImageFile) {
            $uploadPromo = FileUploader::upload($promoImageFile, 'promo');
            if (!empty($uploadPromo['success']) && !empty($uploadPromo['filename'])) {
                $promoImage = $uploadPromo['filename'];
            }
        }
        // Sanitize path (strip leading slashes, uploads/ prefix, and duplicate subfolder names)
        $promoImage = ltrim($promoImage, '/\\');
        $promoImage = preg_replace('#^uploads[\\\\/]+#i', '', $promoImage);
        $promoImage = preg_replace('#^promo[\\\\/]+promo[\\\\/]+#i', 'promo/', $promoImage);

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
            'story_enabled' => $storyEnabled,
            'story_theme' => $storyTheme,
            'story_badge' => $storyBadge,
            'story_badge_icon' => $storyBadgeIcon,
            'story_title' => $storyTitle,
            'story_subtitle' => $storySubtitle,
            'story_description' => $storyDescription,
            'story_metric1_val' => $storyMetric1Val,
            'story_metric1_label' => $storyMetric1Label,
            'story_metric2_val' => $storyMetric2Val,
            'story_metric2_label' => $storyMetric2Label,
            'story_metric3_val' => $storyMetric3Val,
            'story_metric3_label' => $storyMetric3Label,
            'story_metric4_val' => $storyMetric4Val,
            'story_metric4_label' => $storyMetric4Label,
            'story_btn1_text' => $storyBtn1Text,
            'story_btn1_url' => $storyBtn1Url,
            'story_btn2_text' => $storyBtn2Text,
            'story_btn2_url' => $storyBtn2Url,
            'story_pill_text' => $storyPillText,
            'story_image' => $storyImage,
            'promo_enabled' => $promoEnabled,
            'promo_theme' => $promoTheme,
            'promo_badge' => $promoBadge,
            'promo_badge_icon' => $promoBadgeIcon,
            'promo_title' => $promoTitle,
            'promo_coupon_code' => $promoCouponCode,
            'promo_description' => $promoDescription,
            'promo_btn_text' => $promoBtnText,
            'promo_btn_url' => $promoBtnUrl,
            'promo_note' => $promoNote,
            'promo_pill_text' => $promoPillText,
            'promo_image' => $promoImage,
        ]);

        $admin = auth_admin();
        $this->auditRepo->record('settings_updated', $admin['username'] ?? 'Admin', 'Updated system, store, and homepage promotional campaign settings');

        flash('success', 'Store configuration, brand story, and promo campaign banner updated successfully.');
        redirect('admin/settings');
    }
}

