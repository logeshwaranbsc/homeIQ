<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\App;

abstract class JsonRepository
{
    protected string $filename;
    protected string $filePath;
    protected string $idPrefix = '';

    public function __construct(string $filename, string $idPrefix = '')
    {
        $this->filename = $filename;
        $this->idPrefix = $idPrefix;
        $storageDir = App::getBasePath() . DIRECTORY_SEPARATOR . 'storage';
        
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $this->filePath = $storageDir . DIRECTORY_SEPARATOR . $filename;
        $this->ensureFileExists();
    }

    /**
     * Ensure storage file exists and contains valid JSON array
     */
    protected function ensureFileExists(): void
    {
        if (!file_exists($this->filePath)) {
            $this->atomicWrite([]);
        }
    }

    /**
     * Read all items
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $fp = fopen($this->filePath, 'r');
        if (!$fp) {
            return [];
        }

        flock($fp, LOCK_SH);
        $content = stream_get_contents($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        if ($content === false || trim($content) === '') {
            return [];
        }

        $data = json_decode($content, true);
        if (!is_array($data)) {
            return [];
        }

        return $data;
    }

    /**
     * Find item by ID
     */
    public function find(string $id): ?array
    {
        $items = $this->all();
        foreach ($items as $item) {
            if (isset($item['id']) && (string)$item['id'] === (string)$id) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Find item by key and value
     */
    public function findBy(string $key, mixed $value): ?array
    {
        $items = $this->all();
        foreach ($items as $item) {
            if (isset($item[$key]) && $item[$key] === $value) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Filter items matching condition
     * @param callable(array): bool $callback
     * @return array
     */
    public function filter(callable $callback): array
    {
        $items = $this->all();
        return array_values(array_filter($items, $callback));
    }

    /**
     * Create new record
     */
    public function create(array $data): array
    {
        $items = $this->all();

        if (empty($data['id'])) {
            $data['id'] = $this->generateUniqueId();
        }

        $now = date('Y-m-d H:i:s');
        $data['created_at'] = $data['created_at'] ?? $now;
        $data['updated_at'] = $now;

        $items[] = $data;

        $this->atomicWrite($items);
        return $data;
    }

    /**
     * Update existing record
     */
    public function update(string $id, array $data): ?array
    {
        $items = $this->all();
        $found = false;
        $updatedItem = null;

        foreach ($items as &$item) {
            if (isset($item['id']) && (string)$item['id'] === (string)$id) {
                // Preserve ID and created_at
                $originalId = $item['id'];
                $originalCreatedAt = $item['created_at'] ?? date('Y-m-d H:i:s');

                $item = array_merge($item, $data);
                $item['id'] = $originalId;
                $item['created_at'] = $originalCreatedAt;
                $item['updated_at'] = date('Y-m-d H:i:s');

                $updatedItem = $item;
                $found = true;
                break;
            }
        }

        if (!$found) {
            return null;
        }

        $this->atomicWrite($items);
        return $updatedItem;
    }

    /**
     * Delete record by ID
     */
    public function delete(string $id): bool
    {
        $items = $this->all();
        $filtered = array_filter($items, fn($item) => isset($item['id']) && (string)$item['id'] !== (string)$id);

        if (count($filtered) === count($items)) {
            return false;
        }

        $this->atomicWrite(array_values($filtered));
        return true;
    }

    /**
     * Atomic write to file using temporary file and lock
     */
    public function atomicWrite(array $data): bool
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            throw new \RuntimeException("Failed to encode JSON for {$this->filename}");
        }

        $tempPath = $this->filePath . '.' . uniqid('tmp_', true) . '.tmp';
        
        $fp = fopen($tempPath, 'w');
        if (!$fp) {
            throw new \RuntimeException("Cannot create temporary storage file: {$tempPath}");
        }

        flock($fp, LOCK_EX);
        fwrite($fp, $json);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        // Windows atomic replace: if target file exists, on some systems rename can fail if open,
        // so we attempt rename with fallback copy+unlink
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && file_exists($this->filePath)) {
            @unlink($this->filePath);
        }

        if (!@rename($tempPath, $this->filePath)) {
            if (!@copy($tempPath, $this->filePath)) {
                @unlink($tempPath);
                throw new \RuntimeException("Failed to atomically replace {$this->filePath}");
            }
            @unlink($tempPath);
        }

        return true;
    }

    /**
     * Generate unique record ID
     */
    protected function generateUniqueId(): string
    {
        return $this->idPrefix . bin2hex(random_bytes(4)) . dechex(time());
    }
}

