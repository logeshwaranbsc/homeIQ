<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\App;

class SettingsRepository
{
    private string $filePath;

    public function __construct()
    {
        $this->filePath = App::getBasePath() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'settings.json';
        if (!file_exists($this->filePath)) {
            $this->save($this->getDefaults());
        }
    }

    public function all(): array
    {
        if (!file_exists($this->filePath)) {
            return $this->getDefaults();
        }
        $content = file_get_contents($this->filePath);
        $data = json_decode($content, true);
        return is_array($data) ? array_merge($this->getDefaults(), $data) : $this->getDefaults();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();
        return $all[$key] ?? $default;
    }

    public function save(array $settings): bool
    {
        $current = $this->all();
        $merged = array_merge($current, $settings);
        $json = json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        $tempPath = $this->filePath . '.' . uniqid('set_', true) . '.tmp';
        file_put_contents($tempPath, $json, LOCK_EX);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && file_exists($this->filePath)) {
            @unlink($this->filePath);
        }
        @rename($tempPath, $this->filePath);
        return true;
    }

    public function getDefaults(): array
    {
        return [
            'store_name' => 'HOMEIQ',
            'tagline' => 'Advanced Security. Elegant Design.',
            'support_email' => 'support@homeiq-security.com',
            'support_phone' => '+91 98765 43210',
            'address' => 'HOMEIQ Technologies, 402 Smart Tech Park, Cyber City, Bengaluru 560100',
            'currency_symbol' => '₹',
            'currency_code' => 'INR',
            'free_shipping_min' => 5000,
            'flat_shipping_rate' => 250,
            'enable_cod' => true,
            'announcement_bar_enabled' => true,
            'announcements' => [
                'Free Installation on Eligible Products',
                'Free Express Shipping on Orders Above ₹5,000',
                '2-Year Official Manufacturer Warranty Protection',
                '24/7 Dedicated Smart Security Technical Support',
            ],
            'meta_title' => 'HOMEIQ | Premium Digital Smart Door Locks & Security Hardware',
            'meta_description' => 'Explore HOMEIQ biometric digital door locks featuring AI face recognition, fingerprint access, palm vein unlock, Tuya app integration and premium mortise security.',
            // Homepage Editorial Brand Story Section (Customizable)
            'story_enabled' => true,
            'story_badge' => 'HARDWARE PHILOSOPHY',
            'story_badge_icon' => 'bi-cpu',
            'story_title' => 'A NEW STANDARD IN ARCHITECTURAL DEFENSE',
            'story_subtitle' => 'HOMEIQ — where intelligent engineering meets residential peace of mind.',
            'story_description' => 'We’re reimagining how discerning homeowners secure their properties. Crafted from aerospace-grade aluminum alloys and hardened 304 stainless steel mortises, each lock pairs impenetrable mechanical deadbolts with lightning-fast semiconductor biometrics.',
            'story_metric1_val' => '<0.3s',
            'story_metric1_label' => 'Sensor Scan',
            'story_metric2_val' => '9-in-1',
            'story_metric2_label' => 'Access Modes',
            'story_metric3_val' => '304',
            'story_metric3_label' => 'Steel Mortise',
            'story_metric4_val' => 'AES',
            'story_metric4_label' => '128-Bit Safe',
            'story_btn1_text' => 'Explore All Hardware',
            'story_btn1_url' => 'shop',
            'story_btn2_text' => 'Read Security Briefing',
            'story_btn2_url' => 'about',
            'story_image' => 'products/homeiq-ai-face-lock.svg',
            'story_pill_text' => 'Aerospace 6063-T6 • 304 Stainless Steel Mortise',
            // Homepage Exclusive Promotional Campaign Banner (Customizable)
            'promo_enabled' => true,
            'promo_theme' => 'dark',
            'promo_badge' => 'VIP PRIVILEGE CAMPAIGN',
            'promo_badge_icon' => 'bi-tag-fill',
            'promo_title' => 'Upgrade to Keyless Living with Coupon WELCOME10',
            'promo_coupon_code' => 'WELCOME10',
            'promo_description' => 'Enjoy an extra 10% privilege discount on smart locks above ₹10,000. Complete with complimentary doorstep installation by certified security technicians.',
            'promo_btn_text' => 'Claim Offer & Shop Now',
            'promo_btn_url' => 'shop',
            'promo_note' => 'Valid across all AI face, palm vein & fingerprint series.',
            'promo_image' => 'products/video-door-lock.svg',
            'promo_pill_text' => '10% Instant Privilege Discount',
        ];
    }
}

