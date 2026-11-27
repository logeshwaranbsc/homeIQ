<?php
declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Core\Request;
use App\Services\Chatbot\ChatbotService;

class ChatbotController
{
    private ChatbotService $chatbotService;

    public function __construct()
    {
        $this->chatbotService = new ChatbotService();
    }

    /**
     * Handle incoming chat message
     * Route: POST /api/chat
     */
    public function chat(Request $request): void
    {
        header('Content-Type: application/json; charset=UTF-8');

        // Parse JSON body or form body
        $raw = file_get_contents('php://input');
        $json = json_decode((string)$raw, true);

        $message = '';
        $pageContext = [];

        if (is_array($json)) {
            $message = trim((string)($json['message'] ?? ''));
            $pageContext = is_array($json['page_context'] ?? null) ? $json['page_context'] : [];
        } else {
            $message = trim((string)$request->post('message', ''));
            $pageContext = [
                'page' => (string)$request->post('page', ''),
                'product_id' => (string)$request->post('product_id', ''),
                'product_slug' => (string)$request->post('product_slug', ''),
            ];
        }

        // Sanitize & length limit
        if (mb_strlen($message) > 600) {
            $message = mb_substr($message, 0, 600);
        }

        if ($message === '') {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Message is required.',
            ]);
            return;
        }

        $result = $this->chatbotService->handleChat($message, $pageContext);
        echo json_encode($result);
    }

    /**
     * Reset chat conversation
     * Route: POST /api/chat/reset
     */
    public function reset(): void
    {
        header('Content-Type: application/json; charset=UTF-8');
        $this->chatbotService->resetSession();
        echo json_encode([
            'success' => true,
            'message' => 'Conversation memory reset.',
        ]);
    }

    /**
     * Get suggested questions for current page context
     * Route: GET /api/chat/suggested
     */
    public function suggested(Request $request): void
    {
        header('Content-Type: application/json; charset=UTF-8');
        $page = (string)$request->query('page', '');
        $productSlug = (string)$request->query('product_slug', '');

        $suggestions = $this->chatbotService->getSuggestedQuestions($page, $productSlug ?: null);
        echo json_encode([
            'success' => true,
            'suggestions' => $suggestions,
        ]);
    }
}
