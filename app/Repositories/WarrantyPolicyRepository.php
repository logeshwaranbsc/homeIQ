<?php
declare(strict_types=1);

namespace App\Repositories;

use RuntimeException;

class WarrantyPolicyRepository
{
    private string $filePath;

    public function __construct(?string $filePath = null)
    {
        if ($filePath === null) {
            $this->filePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'warranty' . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'warranty-policies.json';
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
            $default = [
                'policies' => [
                    [
                        'id' => 'pol_smart_lock',
                        'product_category' => 'Smart Door Lock',
                        'product_model' => null,
                        'duration_months' => 24,
                        'description' => '2-Year Official Comprehensive Manufacturer Warranty covering electronic mortise, AI sensors, motherboard, and lock body.',
                        'active' => true
                    ],
                    [
                        'id' => 'pol_vdp',
                        'product_category' => 'VDP',
                        'product_model' => null,
                        'duration_months' => 12,
                        'description' => '1-Year Official Warranty covering indoor touch monitor, outdoor camera bell unit, power supply, and night vision hardware.',
                        'active' => true
                    ],
                    [
                        'id' => 'pol_retrofit',
                        'product_category' => 'Retrofit Smart Module',
                        'product_model' => null,
                        'duration_months' => 12,
                        'description' => '1-Year Official Warranty covering internal relay circuits, surge protection components, and Wi-Fi synchronization units.',
                        'active' => true
                    ],
                    [
                        'id' => 'pol_switch',
                        'product_category' => 'Smart Touch Switch',
                        'product_model' => null,
                        'duration_months' => 12,
                        'description' => '1-Year Official Warranty covering toughened glass touch panel, capacitive sensors, and smart relay boards.',
                        'active' => true
                    ],
                    [
                        'id' => 'pol_other',
                        'product_category' => 'Other',
                        'product_model' => null,
                        'duration_months' => 12,
                        'description' => 'Standard 1-Year Manufacturer Warranty on all genuine HOMEIQ automation peripherals and hardware accessories.',
                        'active' => true
                    ]
                ]
            ];
            @file_put_contents($this->filePath, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }

    public function all(): array
    {
        $content = @file_get_contents($this->filePath);
        if (!$content) {
            return [];
        }

        $decoded = json_decode($content, true);
        return $decoded['policies'] ?? [];
    }

    public function getPolicyForCategory(string $category, ?string $model = null): ?array
    {
        $policies = $this->all();
        $matched = null;

        foreach ($policies as $pol) {
            if (empty($pol['active'])) {
                continue;
            }

            // Exact category match
            if (strcasecmp((string)$pol['product_category'], $category) === 0) {
                // If policy specifies a model, check model match
                if (!empty($pol['product_model'])) {
                    if ($model !== null && strcasecmp((string)$pol['product_model'], $model) === 0) {
                        return $pol;
                    }
                } else {
                    $matched = $pol;
                }
            }
        }

        return $matched;
    }

    public function calculateWarrantyDates(string $arg1, string $arg2, ?string $arg3 = null): array
    {
        // Flexible argument handling: accepts ($purchaseDate, $category, $model) or ($category, $model, $purchaseDate)
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $arg1)) {
            $purchaseDate = $arg1;
            $category = $arg2;
            $model = $arg3;
        } elseif ($arg3 !== null && preg_match('/^\d{4}-\d{2}-\d{2}$/', $arg3)) {
            $category = $arg1;
            $model = $arg2;
            $purchaseDate = $arg3;
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $arg2)) {
            $category = $arg1;
            $purchaseDate = $arg2;
            $model = $arg3;
        } else {
            $purchaseDate = date('Y-m-d');
            $category = $arg1;
            $model = $arg2;
        }

        $policy = $this->getPolicyForCategory($category, $model);
        $durationMonths = (int)($policy['duration_months'] ?? 12);

        $ts = strtotime($purchaseDate);
        if ($ts === false) {
            $ts = time();
        }

        $startDate = date('Y-m-d', $ts);
        $endDate = date('Y-m-d', strtotime("+{$durationMonths} months", $ts));

        return [
            'duration_months' => $durationMonths,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'policy_id' => $policy['id'] ?? null,
            'policy_matched' => $policy['id'] ?? null,
        ];
    }

    public function savePolicies(array $policies): bool
    {
        $data = ['policies' => array_values($policies)];
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return false;
        }

        $tempFile = $this->filePath . '.tmp';
        if (file_put_contents($tempFile, $json) !== false) {
            return rename($tempFile, $this->filePath);
        }
        return false;
    }
}
