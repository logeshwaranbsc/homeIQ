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

        // About Page Editorial Hero Section
        $aboutHeroEnabled = (bool)$request->post('about_hero_enabled', false);
        $aboutHeroBadge = trim((string)$request->post('about_hero_badge', 'Architectural Security'));
        $aboutHeroBadgeIcon = trim((string)$request->post('about_hero_badge_icon', 'bi-shield-lock-fill'));
        $aboutHeroTitle = trim((string)$request->post('about_hero_title', 'Engineered for Security.'));
        $aboutHeroTitleHighlight = trim((string)$request->post('about_hero_title_highlight', 'Designed for Living.'));
        $aboutHeroDescription = trim((string)$request->post('about_hero_description', 'HOMEIQ was conceived to bring uncompromising biometric door security and refined architectural elegance to modern residences.'));

        $aboutHeroChip1Text = trim((string)$request->post('about_hero_chip1_text', '3D AI Facial Biometrics'));
        $aboutHeroChip1Icon = trim((string)$request->post('about_hero_chip1_icon', 'bi-cpu-fill text-warning'));
        $aboutHeroChip2Text = trim((string)$request->post('about_hero_chip2_text', '304 Stainless Steel Mortise'));
        $aboutHeroChip2Icon = trim((string)$request->post('about_hero_chip2_icon', 'bi-shield-check text-success'));
        $aboutHeroChip3Text = trim((string)$request->post('about_hero_chip3_text', 'Bank-Grade Local AES-256'));
        $aboutHeroChip3Icon = trim((string)$request->post('about_hero_chip3_icon', 'bi-fingerprint text-info'));

        $aboutHeroBtn1Text = trim((string)$request->post('about_hero_btn1_text', 'Explore Locks'));
        $aboutHeroBtn1Url = trim((string)$request->post('about_hero_btn1_url', 'shop'));
        $aboutHeroBtn2Text = trim((string)$request->post('about_hero_btn2_text', 'Get in Touch'));
        $aboutHeroBtn2Url = trim((string)$request->post('about_hero_btn2_url', 'contact'));

        $aboutHeroCardBadge = trim((string)$request->post('about_hero_card_badge', 'Flagship Biometric Series'));
        $aboutHeroCardStatus = trim((string)$request->post('about_hero_card_status', 'Live Biometrics'));
        $aboutHeroCardFooter = trim((string)$request->post('about_hero_card_footer', '500,000 Cycle Durability • IP65 Weatherproof'));
        $aboutHeroImage = trim((string)$request->post('about_hero_image', 'products/homeiq-ai-face-lock.svg'));

        $aboutHeroImageFile = $request->file('about_hero_image_file');
        if ($aboutHeroImageFile) {
            $uploadAbout = FileUploader::upload($aboutHeroImageFile, 'about');
            if (!empty($uploadAbout['success']) && !empty($uploadAbout['filename'])) {
                $aboutHeroImage = $uploadAbout['filename'];
            }
        }
        $aboutHeroImage = ltrim($aboutHeroImage, '/\\');
        $aboutHeroImage = preg_replace('#^uploads[\\\\/]+#i', '', $aboutHeroImage);
        $aboutHeroImage = preg_replace('#^about[\\\\/]+about[\\\\/]+#i', 'about/', $aboutHeroImage);

        // Keep legacy story_* keys aligned for backward compatibility
        $storyEnabled = $aboutHeroEnabled;
        $storyTheme = 'dark';
        $storyBadge = $aboutHeroBadge;
        $storyBadgeIcon = $aboutHeroBadgeIcon;
        $storyTitle = trim($aboutHeroTitle . ' ' . $aboutHeroTitleHighlight);
        $storySubtitle = $aboutHeroTitleHighlight;
        $storyDescription = $aboutHeroDescription;
        $storyMetric1Val = '<0.3s';
        $storyMetric1Label = 'Sensor Scan';
        $storyMetric2Val = '9-in-1';
        $storyMetric2Label = 'Access Modes';
        $storyMetric3Val = '304';
        $storyMetric3Label = 'Steel Mortise';
        $storyMetric4Val = 'AES';
        $storyMetric4Label = '128-Bit Safe';
        $storyBtn1Text = $aboutHeroBtn1Text;
        $storyBtn1Url = $aboutHeroBtn1Url;
        $storyBtn2Text = $aboutHeroBtn2Text;
        $storyBtn2Url = $aboutHeroBtn2Url;
        $storyPillText = $aboutHeroCardFooter;
        $storyImage = $aboutHeroImage;

        $brandVideoUrl = trim((string)$request->post('brand_video_url', ''));
        if (empty($brandVideoUrl)) {
            $brandVideoUrl = 'https://res.cloudinary.com/uep3k36v/video/upload/v1794397919/Untitled_video_gwr_video_mvp.mp4';
        }

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
            'about_hero_enabled' => $aboutHeroEnabled,
            'about_hero_badge' => $aboutHeroBadge,
            'about_hero_badge_icon' => $aboutHeroBadgeIcon,
            'about_hero_title' => $aboutHeroTitle,
            'about_hero_title_highlight' => $aboutHeroTitleHighlight,
            'about_hero_description' => $aboutHeroDescription,
            'about_hero_chip1_text' => $aboutHeroChip1Text,
            'about_hero_chip1_icon' => $aboutHeroChip1Icon,
            'about_hero_chip2_text' => $aboutHeroChip2Text,
            'about_hero_chip2_icon' => $aboutHeroChip2Icon,
            'about_hero_chip3_text' => $aboutHeroChip3Text,
            'about_hero_chip3_icon' => $aboutHeroChip3Icon,
            'about_hero_btn1_text' => $aboutHeroBtn1Text,
            'about_hero_btn1_url' => $aboutHeroBtn1Url,
            'about_hero_btn2_text' => $aboutHeroBtn2Text,
            'about_hero_btn2_url' => $aboutHeroBtn2Url,
            'about_hero_card_badge' => $aboutHeroCardBadge,
            'about_hero_card_status' => $aboutHeroCardStatus,
            'about_hero_card_footer' => $aboutHeroCardFooter,
            'about_hero_image' => $aboutHeroImage,
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
            'brand_video_url' => $brandVideoUrl,
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
        $this->auditRepo->record('settings_updated', $admin['username'] ?? 'Admin', 'Updated system, store, about page hero, and promotional campaign settings');

        flash('success', 'Store configuration, about page hero section, and promo campaign banner updated successfully.');
        redirect('admin/settings');
    }
}

