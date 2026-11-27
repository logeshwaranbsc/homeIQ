<?php
declare(strict_types=1);

namespace App\Services\Chatbot;

class ChatbotIntentService
{
    public const INTENT_GREETING = 'GREETING';
    public const INTENT_ABOUT = 'ABOUT';
    public const INTENT_EXPLORE_PRODUCTS = 'EXPLORE_PRODUCTS';
    public const INTENT_PRODUCT_DETAIL = 'PRODUCT_DETAIL';
    public const INTENT_PRODUCT_FEATURE_CHECK = 'PRODUCT_FEATURE_CHECK';
    public const INTENT_RECOMMEND_PRODUCT = 'RECOMMEND_PRODUCT';
    public const INTENT_COMPARE_PRODUCTS = 'COMPARE_PRODUCTS';
    public const INTENT_WARRANTY_GENERAL = 'WARRANTY_GENERAL';
    public const INTENT_WARRANTY_AMAZON = 'WARRANTY_AMAZON';
    public const INTENT_WARRANTY_DOCUMENTS = 'WARRANTY_DOCUMENTS';
    public const INTENT_WARRANTY_LOST_INVOICE = 'WARRANTY_LOST_INVOICE';
    public const INTENT_WARRANTY_CLAIM = 'WARRANTY_CLAIM';
    public const INTENT_WARRANTY_COVERAGE = 'WARRANTY_COVERAGE';
    public const INTENT_INSTALLATION = 'INSTALLATION';
    public const INTENT_BATTERY_EMERGENCY = 'BATTERY_EMERGENCY';
    public const INTENT_SHIPPING_DELIVERY = 'SHIPPING_DELIVERY';
    public const INTENT_ORDER_TRACKING = 'ORDER_TRACKING';
    public const INTENT_RETURNS_REFUNDS = 'RETURNS_REFUNDS';
    public const INTENT_CALLBACK_REQUEST = 'CALLBACK_REQUEST';
    public const INTENT_CONTACT = 'CONTACT';
    public const INTENT_FALLBACK = 'FALLBACK';

    private ChatbotKnowledgeService $knowledgeService;
    private ChatbotSessionService $sessionService;

    public function __construct(
        ChatbotKnowledgeService $knowledgeService,
        ChatbotSessionService $sessionService
    ) {
        $this->knowledgeService = $knowledgeService;
        $this->sessionService = $sessionService;
    }

    /**
     * Analyze message and return intent, matched product, and entities
     */
    public function analyze(string $message, array $pageContext = []): array
    {
        $rawMessage = trim($message);
        $clean = mb_strtolower($rawMessage);
        $clean = preg_replace('/[?!,.\'"\-_]/', ' ', $clean);
        $clean = preg_replace('/\s+/', ' ', (string)$clean);

        // Check if user is referencing currently viewed product from page_context
        $contextProduct = null;
        if (!empty($pageContext['product_id'])) {
            $contextProduct = $this->knowledgeService->findProduct((string)$pageContext['product_id']);
        } elseif (!empty($pageContext['product_slug'])) {
            $contextProduct = $this->knowledgeService->findProduct((string)$pageContext['product_slug']);
        }

        // If no page product, check if session remembers an active product
        if (!$contextProduct) {
            $contextProduct = $this->sessionService->getActiveProduct();
        }

        // 1. Detect if message mentions a specific product from the catalog
        $mentionedProduct = $this->detectMentionedProduct($clean);
        $activeProduct = $mentionedProduct ?: $contextProduct;

        // 2. Detect Greeting
        if (preg_match('/^(hi|hello|hey|good morning|good afternoon|good evening|namaste|hola)(\b|$)/i', $clean)) {
            if (strlen($clean) < 25) {
                return [
                    'intent' => self::INTENT_GREETING,
                    'product' => $activeProduct,
                    'confidence' => 0.95,
                    'entities' => [],
                ];
            }
        }

        // 3. Follow-up detection: "what if i lose it / invoice / bill"
        if (preg_match('/(lose|lost|missing)\s+(it|my\s+bill|my\s+invoice|receipt|paper)/i', $clean) ||
            (str_contains($clean, 'lose') && str_contains($clean, 'invoice')) ||
            (str_contains($clean, 'lost') && str_contains($clean, 'invoice'))) {
            return [
                'intent' => self::INTENT_WARRANTY_LOST_INVOICE,
                'product' => $activeProduct,
                'confidence' => 0.9,
                'entities' => [],
            ];
        }

        // 4. Follow-up or direct: "does this support / have..." when product is active
        $isFeatureCheck = preg_match('/(does\s+(this|it)|does\s+the\s+lock|support|have|has)\s+(wifi|fingerprint|face|camera|app|bluetooth|rfid|battery|pin|passcode|remote|nfc|video|call)/i', $clean)
            || (preg_match('/(fingerprint|face|camera|app|wifi|pin|rfid)/i', $clean) && preg_match('/(support|feature|work|available|have)/i', $clean));

        if ($isFeatureCheck && $activeProduct) {
            return [
                'intent' => self::INTENT_PRODUCT_FEATURE_CHECK,
                'product' => $activeProduct,
                'confidence' => 0.9,
                'entities' => $this->extractFeatures($clean),
            ];
        }

        // 5. Amazon / Flipkart warranty registration
        if (str_contains($clean, 'amazon') || str_contains($clean, 'flipkart') || str_contains($clean, 'ecommerce')) {
            return [
                'intent' => self::INTENT_WARRANTY_AMAZON,
                'product' => $activeProduct,
                'confidence' => 0.95,
                'entities' => ['platform' => str_contains($clean, 'amazon') ? 'Amazon' : 'Flipkart'],
            ];
        }

        // 6. Warranty documents required
        if ((str_contains($clean, 'document') || str_contains($clean, 'documents') || str_contains($clean, 'proof') || str_contains($clean, 'invoice') || str_contains($clean, 'serial number'))
            && (str_contains($clean, 'require') || str_contains($clean, 'need') || str_contains($clean, 'warranty') || str_contains($clean, 'register'))) {
            return [
                'intent' => self::INTENT_WARRANTY_DOCUMENTS,
                'product' => $activeProduct,
                'confidence' => 0.9,
                'entities' => [],
            ];
        }

        // 7. Warranty claim / service request
        if ((str_contains($clean, 'claim') || str_contains($clean, 'service') || str_contains($clean, 'repair') || str_contains($clean, 'breakdown') || str_contains($clean, 'not working') || str_contains($clean, 'issue'))
            && (str_contains($clean, 'warranty') || str_contains($clean, 'technician') || str_contains($clean, 'lock') || str_contains($clean, 'door') || str_contains($clean, 'how'))) {
            return [
                'intent' => self::INTENT_WARRANTY_CLAIM,
                'product' => $activeProduct,
                'confidence' => 0.88,
                'entities' => [],
            ];
        }

        // 8. Warranty coverage / duration
        if ((str_contains($clean, 'cover') || str_contains($clean, 'coverage') || str_contains($clean, 'duration') || str_contains($clean, 'period') || str_contains($clean, 'years') || str_contains($clean, 'months'))
            && (str_contains($clean, 'warranty') || str_contains($clean, 'guarantee'))) {
            return [
                'intent' => self::INTENT_WARRANTY_COVERAGE,
                'product' => $activeProduct,
                'confidence' => 0.9,
                'entities' => [],
            ];
        }

        // General Warranty
        if (str_contains($clean, 'warranty') || str_contains($clean, 'guarantee') || str_contains($clean, 'register product')) {
            return [
                'intent' => self::INTENT_WARRANTY_GENERAL,
                'product' => $activeProduct,
                'confidence' => 0.85,
                'entities' => [],
            ];
        }

        // 9. Battery drain / emergency unlock
        if (str_contains($clean, 'battery') || str_contains($clean, 'power bank') || str_contains($clean, 'charge') || str_contains($clean, 'dead battery') || str_contains($clean, 'drains') || str_contains($clean, 'emergency key')) {
            return [
                'intent' => self::INTENT_BATTERY_EMERGENCY,
                'product' => $activeProduct,
                'confidence' => 0.88,
                'entities' => [],
            ];
        }

        // 10. Installation & door suitability
        if (str_contains($clean, 'install') || str_contains($clean, 'door thickness') || str_contains($clean, 'wooden door') || str_contains($clean, 'fit my door') || str_contains($clean, 'fitting') || str_contains($clean, 'mortise')) {
            return [
                'intent' => self::INTENT_INSTALLATION,
                'product' => $activeProduct,
                'confidence' => 0.88,
                'entities' => [],
            ];
        }

        // 11. Order tracking
        if (str_contains($clean, 'track') || str_contains($clean, 'where is my order') || str_contains($clean, 'order status') || preg_match('/hiq-ord/i', $clean)) {
            return [
                'intent' => self::INTENT_ORDER_TRACKING,
                'product' => $activeProduct,
                'confidence' => 0.9,
                'entities' => [],
            ];
        }

        // 12. Shipping & Delivery
        if (str_contains($clean, 'shipping') || str_contains($clean, 'delivery') || str_contains($clean, 'how long') || str_contains($clean, 'delivery time') || str_contains($clean, 'courier') || str_contains($clean, 'pincode')) {
            return [
                'intent' => self::INTENT_SHIPPING_DELIVERY,
                'product' => $activeProduct,
                'confidence' => 0.85,
                'entities' => [],
            ];
        }

        // 13. Returns & Refunds
        if (str_contains($clean, 'return') || str_contains($clean, 'refund') || str_contains($clean, 'replacement') || str_contains($clean, 'cancel') || str_contains($clean, 'damaged product')) {
            return [
                'intent' => self::INTENT_RETURNS_REFUNDS,
                'product' => $activeProduct,
                'confidence' => 0.9,
                'entities' => [],
            ];
        }

        // 14. Callback / Demo
        if (str_contains($clean, 'callback') || str_contains($clean, 'call me') || str_contains($clean, 'request a call') || str_contains($clean, 'speak to') || str_contains($clean, 'demo') || str_contains($clean, 'demonstration') || str_contains($clean, 'consultation')) {
            return [
                'intent' => self::INTENT_CALLBACK_REQUEST,
                'product' => $activeProduct,
                'confidence' => 0.9,
                'entities' => [],
            ];
        }

        // 15. Contact & Support
        if (str_contains($clean, 'contact') || str_contains($clean, 'customer care') || str_contains($clean, 'phone number') || str_contains($clean, 'email') || str_contains($clean, 'support') || str_contains($clean, 'office') || str_contains($clean, 'address')) {
            return [
                'intent' => self::INTENT_CONTACT,
                'product' => $activeProduct,
                'confidence' => 0.85,
                'entities' => [],
            ];
        }

        // 16. About HOMEIQ
        if (str_contains($clean, 'about') || str_contains($clean, 'who is homeiq') || str_contains($clean, 'what is homeiq') || str_contains($clean, 'company') || str_contains($clean, 'brand')) {
            return [
                'intent' => self::INTENT_ABOUT,
                'product' => $activeProduct,
                'confidence' => 0.85,
                'entities' => [],
            ];
        }

        // 17. Compare products
        if (str_contains($clean, 'difference') || str_contains($clean, 'compare') || str_contains($clean, 'vs') || str_contains($clean, 'versus') || str_contains($clean, 'better')) {
            return [
                'intent' => self::INTENT_COMPARE_PRODUCTS,
                'product' => $activeProduct,
                'confidence' => 0.85,
                'entities' => [],
            ];
        }

        // 18. Product Recommendation
        if (str_contains($clean, 'recommend') || str_contains($clean, 'which lock') || str_contains($clean, 'help me choose') || str_contains($clean, 'best lock') || str_contains($clean, 'suitable') || str_contains($clean, 'suggest')) {
            return [
                'intent' => self::INTENT_RECOMMEND_PRODUCT,
                'product' => $activeProduct,
                'confidence' => 0.85,
                'entities' => [],
            ];
        }

        // 19. If specific product was mentioned or asked about
        if ($mentionedProduct) {
            return [
                'intent' => self::INTENT_PRODUCT_DETAIL,
                'product' => $mentionedProduct,
                'confidence' => 0.85,
                'entities' => [],
            ];
        }

        // 20. Explore products / catalog
        if (str_contains($clean, 'product') || str_contains($clean, 'models') || str_contains($clean, 'locks') || str_contains($clean, 'catalog') || str_contains($clean, 'shop') || str_contains($clean, 'buy')) {
            return [
                'intent' => self::INTENT_EXPLORE_PRODUCTS,
                'product' => $activeProduct,
                'confidence' => 0.8,
                'entities' => [],
            ];
        }

        // 21. Check Knowledge Base Match
        $kbMatch = $this->knowledgeService->matchKnowledge($rawMessage);
        if ($kbMatch) {
            return [
                'intent' => 'KB_MATCH',
                'product' => $activeProduct,
                'confidence' => 0.8,
                'entities' => ['kb_entry' => $kbMatch],
            ];
        }

        return [
            'intent' => self::INTENT_FALLBACK,
            'product' => $activeProduct,
            'confidence' => 0.4,
            'entities' => [],
        ];
    }

    /**
     * Identify if user message specifies a particular product from database
     */
    private function detectMentionedProduct(string $clean): ?array
    {
        $products = $this->knowledgeService->getProducts();

        // Specific alias checks
        $aliasMap = [
            'black ai' => 'homeiq-black-ai-face-recognition-smart-door-lock',
            'face recognition' => 'homeiq-black-ai-face-recognition-smart-door-lock',
            'face lock' => 'homeiq-black-ai-face-recognition-smart-door-lock',
            'touch pro' => 'homeiq-touch-pro-biometric-fingerprint-smart-lock',
            'veinguard' => 'homeiq-veinguard-contactless-palm-vein-smart-lock',
            'palm vein' => 'homeiq-veinguard-contactless-palm-vein-smart-lock',
            'vision 2k' => 'homeiq-vision-2k-video-doorbell-smart-lock',
            'video doorbell' => 'homeiq-vision-2k-video-doorbell-smart-lock',
            'video lock' => 'homeiq-vision-2k-video-doorbell-smart-lock',
            'fingerprint & pin' => 'homeiq-fingerprint-pin-digital-smart-door-lock',
            'fingerprint and pin' => 'homeiq-fingerprint-pin-digital-smart-door-lock',
        ];

        foreach ($aliasMap as $alias => $slug) {
            if (str_contains($clean, $alias)) {
                return $this->knowledgeService->findProduct($slug);
            }
        }

        // Full name / slug match
        foreach ($products as $p) {
            $name = mb_strtolower($p['name'] ?? '');
            $slug = str_replace('-', ' ', $p['slug'] ?? '');
            if (str_contains($clean, $name) || str_contains($clean, $slug)) {
                return $p;
            }
        }

        return null;
    }

    private function extractFeatures(string $clean): array
    {
        $features = [];
        $candidates = ['wifi', 'fingerprint', 'face recognition', 'palm vein', 'camera', 'app', 'bluetooth', 'rfid', 'battery', 'pin', 'emergency key', 'usb-c'];
        foreach ($candidates as $c) {
            if (str_contains($clean, $c)) {
                $features[] = $c;
            }
        }
        return $features;
    }
}
