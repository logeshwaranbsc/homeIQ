<?php
declare(strict_types=1);

namespace App\Services\Chatbot;

use App\Core\Session;

class ChatbotService
{
    private ChatbotKnowledgeService $knowledgeService;
    private ChatbotSessionService $sessionService;
    private ChatbotIntentService $intentService;
    private ChatbotAiService $aiService;

    public function __construct()
    {
        $this->knowledgeService = new ChatbotKnowledgeService();
        $this->sessionService = new ChatbotSessionService();
        $this->intentService = new ChatbotIntentService($this->knowledgeService, $this->sessionService);
        $this->aiService = new ChatbotAiService($this->knowledgeService, $this->sessionService);
    }

    /**
     * Process an incoming chat query
     */
    public function handleChat(string $message, array $pageContext = []): array
    {
        $message = trim($message);
        if ($message === '') {
            return [
                'success' => false,
                'error' => 'Please provide a valid question.',
            ];
        }

        // Rate Limiting (max 35 messages per 60 seconds per session)
        if ($this->isRateLimited()) {
            return [
                'success' => true,
                'reply' => "You have sent several messages in a short time. Please wait a moment before sending another question, or feel free to call our support line directly at +91 98765 43210.",
                'suggestions' => ["Contact Customer Support", "Request a Callback"],
                'actions' => [
                    ['label' => 'Contact Support', 'url' => url('contact')]
                ],
            ];
        }

        // Record incoming user message
        $this->sessionService->addMessage('user', $message, ['page' => $pageContext['page'] ?? '']);

        // Analyze Intent & Context
        $intentData = $this->intentService->analyze($message, $pageContext);

        // Generate Grounded Response
        $response = $this->aiService->generateResponse($message, $intentData, $pageContext);

        // Record assistant reply in history
        $this->sessionService->addMessage('assistant', $response['reply'], [
            'intent' => $intentData['intent'] ?? '',
            'product' => $intentData['product']['slug'] ?? null,
        ]);

        return [
            'success' => true,
            'reply' => $response['reply'],
            'suggestions' => $response['suggestions'] ?? [],
            'actions' => $response['actions'] ?? [],
            'active_product' => $intentData['product'] ? [
                'name' => $intentData['product']['name'],
                'slug' => $intentData['product']['slug'],
                'price' => $intentData['product']['sale_price'] ?? null,
            ] : null,
        ];
    }

    /**
     * Get suggested questions tailored to current page
     */
    public function getSuggestedQuestions(string $page = '', ?string $productSlug = null): array
    {
        $page = trim(mb_strtolower($page), '/');

        if ($page === 'warranty-registration' || str_contains($page, 'warranty')) {
            return [
                "Can I register a product purchased from Amazon or Flipkart?",
                "What documents are required for warranty registration?",
                "What if I lose my purchase invoice?",
                "What is covered under warranty?",
                "How can I request warranty service?"
            ];
        }

        if ($productSlug || str_starts_with($page, 'product/')) {
            return [
                "What are the key features of this smart lock?",
                "Does this lock support fingerprint access?",
                "Will this fit my existing wooden door?",
                "What happens if the battery drains completely?",
                "Is professional installation included?"
            ];
        }

        if ($page === 'contact' || str_contains($page, 'contact')) {
            return [
                "How can I request a product callback?",
                "What are your customer support working hours?",
                "How do I book a doorstep installation slot?",
                "What is your customer care phone number?"
            ];
        }

        if ($page === 'order-tracking' || str_contains($page, 'track')) {
            return [
                "How can I track my order status?",
                "What are your delivery timelines?",
                "What is your 7-day return and refund policy?"
            ];
        }

        // Default storefront suggestions
        return [
            "Explore HOMEIQ Products",
            "Help Me Choose a Smart Lock",
            "Product Specifications",
            "Warranty Registration",
            "Warranty & Service Information",
            "Order & Delivery Assistance",
            "Returns & Refunds",
            "Installation Assistance",
            "Request a Callback",
            "Contact Customer Support"
        ];
    }

    /**
     * Clear active session memory
     */
    public function resetSession(): void
    {
        $this->sessionService->reset();
    }

    /**
     * Simple sliding window rate limiter
     */
    private function isRateLimited(): bool
    {
        $now = time();
        $timestamps = Session::get('hiq_chat_ratelimit', []);
        if (!is_array($timestamps)) $timestamps = [];

        // Retain only requests within last 60 seconds
        $timestamps = array_filter($timestamps, fn($t) => is_numeric($t) && ($now - $t) < 60);

        if (count($timestamps) >= 35) {
            return true;
        }

        $timestamps[] = $now;
        Session::set('hiq_chat_ratelimit', $timestamps);
        return false;
    }
}
