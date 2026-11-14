<?php
declare(strict_types=1);

namespace App\Repositories;

class VideoTestimonialRepository extends JsonRepository
{
    public const MAX_ITEMS = 10;

    public function __construct()
    {
        parent::__construct('video_testimonials.json', 'vid_');
    }

    /**
     * Get all active video testimonials sorted by display_order
     * Maximum 10 items
     * @return array
     */
    public function getActive(): array
    {
        $items = $this->filter(fn($item) => !empty($item['is_active']));
        usort($items, function ($a, $b) {
            $orderA = (int)($a['display_order'] ?? 1);
            $orderB = (int)($b['display_order'] ?? 1);
            if ($orderA === $orderB) {
                return strcmp($a['created_at'] ?? '', $b['created_at'] ?? '');
            }
            return $orderA <=> $orderB;
        });
        return array_slice($items, 0, self::MAX_ITEMS);
    }

    /**
     * Get all video testimonials sorted by display_order
     * @return array
     */
    public function getAllSorted(): array
    {
        $items = $this->all();
        usort($items, function ($a, $b) {
            $orderA = (int)($a['display_order'] ?? 1);
            $orderB = (int)($b['display_order'] ?? 1);
            if ($orderA === $orderB) {
                return strcmp($a['created_at'] ?? '', $b['created_at'] ?? '');
            }
            return $orderA <=> $orderB;
        });
        return $items;
    }

    /**
     * Check if a new video testimonial can be added (Strict max 4 limit)
     */
    public function canAdd(): bool
    {
        return $this->count() < self::MAX_ITEMS;
    }

    /**
     * Return total count of video testimonials
     */
    public function count(): int
    {
        return count($this->all());
    }

    /**
     * Return remaining available upload slots (from 0 to 4)
     */
    public function getRemainingSlots(): int
    {
        return max(0, self::MAX_ITEMS - $this->count());
    }
}

