<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\App;

class ChatbotKnowledgeRepository
{
    private string $filePath;

    public function __construct()
    {
        $base = App::getBasePath() ?: dirname(__DIR__, 2);
        $this->filePath = $base . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'chatbot_knowledge.json';
        if (!file_exists($this->filePath)) {
            @file_put_contents($this->filePath, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function all(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }
        $data = json_decode((string)file_get_contents($this->filePath), true);
        return is_array($data) ? $data : [];
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getActive(): array
    {
        return array_values(array_filter($this->all(), fn($item) => !empty($item['active'])));
    }

    public function findById(string $id): ?array
    {
        foreach ($this->all() as $item) {
            if (($item['id'] ?? '') === $id) {
                return $item;
            }
        }
        return null;
    }

    public function save(array $items): bool
    {
        return @file_put_contents($this->filePath, json_encode(array_values($items), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    public function create(array $data): array
    {
        $items = $this->all();
        $id = 'kb_' . time() . '_' . substr(md5(uniqid('', true)), 0, 6);
        $entry = [
            'id' => $id,
            'category' => trim((string)($data['category'] ?? 'General')),
            'keywords' => is_array($data['keywords'] ?? null) 
                ? $data['keywords'] 
                : array_filter(array_map('trim', explode(',', (string)($data['keywords'] ?? '')))),
            'question' => trim((string)($data['question'] ?? '')),
            'answer' => trim((string)($data['answer'] ?? '')),
            'link_url' => trim((string)($data['link_url'] ?? '')),
            'link_label' => trim((string)($data['link_label'] ?? '')),
            'active' => isset($data['active']) ? (bool)$data['active'] : true,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $items[] = $entry;
        $this->save($items);
        return $entry;
    }

    public function update(string $id, array $data): ?array
    {
        $items = $this->all();
        foreach ($items as &$item) {
            if (($item['id'] ?? '') === $id) {
                if (isset($data['category'])) $item['category'] = trim((string)$data['category']);
                if (isset($data['question'])) $item['question'] = trim((string)$data['question']);
                if (isset($data['answer'])) $item['answer'] = trim((string)$data['answer']);
                if (isset($data['link_url'])) $item['link_url'] = trim((string)$data['link_url']);
                if (isset($data['link_label'])) $item['link_label'] = trim((string)$data['link_label']);
                if (isset($data['active'])) $item['active'] = (bool)$data['active'];
                if (isset($data['keywords'])) {
                    $item['keywords'] = is_array($data['keywords'])
                        ? $data['keywords']
                        : array_filter(array_map('trim', explode(',', (string)$data['keywords'])));
                }
                $item['updated_at'] = date('Y-m-d H:i:s');
                $this->save($items);
                return $item;
            }
        }
        return null;
    }

    public function delete(string $id): bool
    {
        $items = $this->all();
        $filtered = array_filter($items, fn($item) => ($item['id'] ?? '') !== $id);
        if (count($filtered) === count($items)) {
            return false;
        }
        return $this->save($filtered);
    }

    public function toggle(string $id): ?bool
    {
        $items = $this->all();
        foreach ($items as &$item) {
            if (($item['id'] ?? '') === $id) {
                $item['active'] = empty($item['active']);
                $item['updated_at'] = date('Y-m-d H:i:s');
                $this->save($items);
                return $item['active'];
            }
        }
        return null;
    }
}
