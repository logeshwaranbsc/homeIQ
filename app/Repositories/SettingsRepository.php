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
        ];
    }
}

