<?php
declare(strict_types=1);

namespace App\Services\Chatbot;

use App\Repositories\ProductRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\ChatbotKnowledgeRepository;
use App\Core\App;

class ChatbotKnowledgeService
{
    private ProductRepository $productRepo;
    private SettingsRepository $settingsRepo;
    private ChatbotKnowledgeRepository $kbRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->settingsRepo = new SettingsRepository();
        $this->kbRepo = new ChatbotKnowledgeRepository();
    }

    /**
     * Get all active products from database
     * @return array<array<string, mixed>>
     */
    public function getProducts(): array
    {
        return $this->productRepo->getActive();
    }

    /**
     * Find a product by slug, ID, or SKU
     */
    public function findProduct(string $identifier): ?array
    {
        $product = $this->productRepo->findBySlug($identifier);
        if ($product) return $product;

        $product = $this->productRepo->findBySku($identifier);
        if ($product) return $product;

        return $this->productRepo->find($identifier);
    }

    /**
     * Search products by keyword matching in name, features, or access methods
     */
    public function searchProducts(string $query): array
    {
        $q = mb_strtolower(trim($query));
        if ($q === '') return [];

        $all = $this->getProducts();
        $matches = [];

        foreach ($all as $p) {
            $score = 0;
            $name = mb_strtolower($p['name'] ?? '');
            $shortDesc = mb_strtolower($p['short_description'] ?? '');
            $features = implode(' ', array_map('mb_strtolower', $p['features'] ?? []));
            $accessMethods = implode(' ', array_map('mb_strtolower', $p['access_methods'] ?? []));

            if (str_contains($name, $q)) $score += 10;
            if (str_contains($shortDesc, $q)) $score += 5;
            if (str_contains($features, $q)) $score += 4;
            if (str_contains($accessMethods, $q)) $score += 4;

            // Individual word matches
            $words = array_filter(explode(' ', $q), fn($w) => strlen($w) > 2);
            foreach ($words as $w) {
                if (str_contains($name, $w)) $score += 3;
                if (str_contains($features, $w)) $score += 2;
                if (str_contains($accessMethods, $w)) $score += 2;
            }

            if ($score > 0) {
                $matches[] = ['product' => $p, 'score' => $score];
            }
        }

        usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);
        return array_map(fn($m) => $m['product'], $matches);
    }

    /**
     * Get store settings
     */
    public function getSettings(): array
    {
        return $this->settingsRepo->all();
    }

    /**
     * Get warranty policies
     */
    public function getWarrantyPolicies(): array
    {
        $base = App::getBasePath() ?: dirname(__DIR__, 3);
        $file = $base . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'warranty' . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'warranty-policies.json';
        if (file_exists($file)) {
            $data = json_decode((string)file_get_contents($file), true);
            if (is_array($data) && isset($data['policies'])) {
                return $data['policies'];
            }
        }
        return [];
    }

    /**
     * Get active knowledge entries
     */
    public function getKnowledgeEntries(): array
    {
        return $this->kbRepo->getActive();
    }

    /**
     * Match knowledge base entries by user question text
     */
    public function matchKnowledge(string $query): ?array
    {
        $q = mb_strtolower(trim($query));
        $entries = $this->getKnowledgeEntries();
        $bestMatch = null;
        $highestScore = 0;

        foreach ($entries as $entry) {
            $score = 0;
            $question = mb_strtolower($entry['question'] ?? '');
            $keywords = $entry['keywords'] ?? [];

            // Exact or high similarity question match
            if ($question === $q) {
                return $entry;
            }
            if (str_contains($q, $question) || str_contains($question, $q)) {
                $score += 8;
            }

            foreach ($keywords as $kw) {
                $kw = mb_strtolower(trim((string)$kw));
                if ($kw !== '' && str_contains($q, $kw)) {
                    $score += 3;
                }
            }

            if ($score > $highestScore && $score >= 3) {
                $highestScore = $score;
                $bestMatch = $entry;
            }
        }

        return $bestMatch;
    }
}
