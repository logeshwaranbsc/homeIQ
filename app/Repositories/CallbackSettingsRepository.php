<?php
declare(strict_types=1);

namespace App\Repositories;

class CallbackSettingsRepository
{
    private string $filePath;

    public function __construct(?string $filePath = null)
    {
        if ($filePath === null) {
            $this->filePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'callback-leads' . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'callback-settings.json';
        } else {
            $this->filePath = $filePath;
        }

        $this->ensureFile();
    }

    private function ensureFile(): void
    {
        $dir = dirname($this->filePath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0750, true);
        }

        if (!file_exists($this->filePath)) {
            $defaults = $this->getDefaults();
            @file_put_contents($this->filePath, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }

    public function getDefaults(): array
    {
        return [
            'enabled' => true,
            'trigger_delay_seconds' => 60,
            'heading' => 'Need Help Before You Buy?',
            'description' => 'Let our product expert help you choose the right product.',
            'button_text' => 'REQUEST A CALLBACK',
            'product_options' => [
                'Smart Door Lock',
                'Video Door Phone (VDP)',
                'Retrofit Smart Module',
                'Other Products',
            ],
        ];
    }

    public function get(): array
    {
        if (!file_exists($this->filePath)) {
            return $this->getDefaults();
        }

        $content = @file_get_contents($this->filePath);
        if (!$content) {
            return $this->getDefaults();
        }

        $decoded = json_decode($content, true);
        if (!is_array($decoded)) {
            return $this->getDefaults();
        }

        return array_merge($this->getDefaults(), $decoded);
    }

    public function save(array $settings): bool
    {
        $current = $this->get();
        $merged = [
            'enabled' => !empty($settings['enabled']),
            'trigger_delay_seconds' => max(5, (int)($settings['trigger_delay_seconds'] ?? 60)),
            'heading' => trim((string)($settings['heading'] ?? $current['heading'])),
            'description' => trim((string)($settings['description'] ?? $current['description'])),
            'button_text' => trim((string)($settings['button_text'] ?? $current['button_text'])),
            'product_options' => !empty($settings['product_options']) && is_array($settings['product_options'])
                ? array_values(array_filter(array_map('trim', $settings['product_options'])))
                : $current['product_options'],
            'updated_at' => date('c'),
        ];

        $json = json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            return false;
        }

        $tempFile = $this->filePath . '.tmp_' . uniqid('', true);
        if (@file_put_contents($tempFile, $json) === false) {
            return false;
        }

        if (!@rename($tempFile, $this->filePath)) {
            @unlink($tempFile);
            return false;
        }

        return true;
    }
}
