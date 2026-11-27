<?php
declare(strict_types=1);

namespace App\Services\Chatbot;

use App\Core\Session;

class ChatbotSessionService
{
    private const SESSION_KEY = 'homeiq_chatbot_context';
    private const HISTORY_LIMIT = 12;

    /**
     * Get the active session context
     */
    public function getContext(): array
    {
        $context = Session::get(self::SESSION_KEY);
        if (!is_array($context)) {
            $context = [
                'conversation_id' => 'conv_' . bin2hex(random_bytes(8)),
                'active_product' => null,
                'last_topic' => null,
                'last_intent' => null,
                'history' => [],
                'created_at' => time(),
            ];
            Session::set(self::SESSION_KEY, $context);
        }
        return $context;
    }

    /**
     * Update active product in conversation memory
     */
    public function setActiveProduct(?array $product): void
    {
        $context = $this->getContext();
        $context['active_product'] = $product ? [
            'id' => $product['id'] ?? '',
            'name' => $product['name'] ?? '',
            'slug' => $product['slug'] ?? '',
            'sale_price' => $product['sale_price'] ?? null,
            'features' => $product['features'] ?? [],
            'access_methods' => $product['access_methods'] ?? [],
            'specifications' => $product['specifications'] ?? [],
        ] : null;
        Session::set(self::SESSION_KEY, $context);
    }

    /**
     * Get the currently active product being discussed
     */
    public function getActiveProduct(): ?array
    {
        $context = $this->getContext();
        return $context['active_product'] ?? null;
    }

    /**
     * Update last topic
     */
    public function setLastTopic(string $topic, ?string $intent = null): void
    {
        $context = $this->getContext();
        $context['last_topic'] = $topic;
        if ($intent) {
            $context['last_intent'] = $intent;
        }
        Session::set(self::SESSION_KEY, $context);
    }

    public function getLastTopic(): ?string
    {
        $context = $this->getContext();
        return $context['last_topic'] ?? null;
    }

    /**
     * Append turn to conversation history
     */
    public function addMessage(string $role, string $text, array $meta = []): void
    {
        $context = $this->getContext();
        $history = $context['history'] ?? [];
        $history[] = [
            'role' => $role, // 'user' or 'assistant'
            'text' => $text,
            'time' => time(),
            'meta' => $meta,
        ];

        // Keep last N messages
        if (count($history) > self::HISTORY_LIMIT) {
            $history = array_slice($history, -self::HISTORY_LIMIT);
        }

        $context['history'] = $history;
        Session::set(self::SESSION_KEY, $context);
    }

    /**
     * Get recent conversation history
     */
    public function getHistory(): array
    {
        $context = $this->getContext();
        return $context['history'] ?? [];
    }

    /**
     * Reset the conversation context
     */
    public function reset(): void
    {
        Session::remove(self::SESSION_KEY);
    }
}
