<?php
declare(strict_types=1);

namespace App\Services\Chatbot;

class ChatbotAiService
{
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
     * Generate grounded response based on analyzed intent and context
     */
    public function generateResponse(string $message, array $intentData, array $pageContext = []): array
    {
        $intent = $intentData['intent'] ?? ChatbotIntentService::INTENT_FALLBACK;
        $activeProduct = $intentData['product'] ?? null;
        $settings = $this->knowledgeService->getSettings();
        $supportPhone = $settings['support_phone'] ?? '+91 98765 43210';
        $supportEmail = $settings['support_email'] ?? 'support@homeiq-security.com';

        // Update active product in session if found
        if ($activeProduct) {
            $this->sessionService->setActiveProduct($activeProduct);
        }

        switch ($intent) {
            case ChatbotIntentService::INTENT_GREETING:
                return [
                    'reply' => "Hi there! Welcome to **HOMEIQ**. 👋\n\nI'm your virtual assistant. How can I assist you with our smart door locks, technical specifications, or warranty services today?",
                    'suggestions' => [
                        "Explore HOMEIQ Products",
                        "Help Me Choose a Smart Lock",
                        "Warranty Registration",
                        "Request a Callback"
                    ],
                    'actions' => [
                        ['label' => 'Explore Smart Locks', 'url' => url('shop')],
                        ['label' => 'Warranty Registration', 'url' => url('warranty-registration')],
                    ],
                ];

            case ChatbotIntentService::INTENT_ABOUT:
                return [
                    'reply' => "### About HOMEIQ\n\nHOMEIQ is India's leading smart door lock technology brand, dedicated to bringing **uncompromising biometric security** and **architectural elegance** to modern residences and commercial spaces.\n\n- **Flagship Biometrics**: 3D AI Face Recognition, Contactless Palm Vein, and Semiconductor 3D Fingerprint scanners.\n- **Built to Last**: 304 Stainless Steel heavy-duty mortise locks with 500,000-cycle durability.\n- **Full Service**: Complimentary doorstep installation by certified technicians and up to 2-Year official manufacturer warranty.",
                    'suggestions' => [
                        "Explore HOMEIQ Products",
                        "Which smart lock is suitable for my home?",
                        "What is your warranty coverage?"
                    ],
                    'actions' => [
                        ['label' => 'Read Our Full Story', 'url' => url('about')],
                        ['label' => 'View Smart Locks', 'url' => url('shop')],
                    ],
                ];

            case ChatbotIntentService::INTENT_EXPLORE_PRODUCTS:
                $products = $this->knowledgeService->getProducts();
                $list = "";
                foreach ($products as $p) {
                    $price = isset($p['sale_price']) ? '₹' . number_format((float)$p['sale_price']) : '';
                    $list .= "- **[{$p['name']}](url('product/' . {$p['slug']}))** — {$price}\n  _{$p['short_description']}_\n";
                }

                return [
                    'reply' => "Here are our current high-security smart door locks:\n\n" . $list . "\nEvery lock comes with **Free Professional Doorstep Installation** and an official manufacturer warranty.",
                    'suggestions' => [
                        "Tell me about the Black AI lock",
                        "Which smart lock is suitable for my home?",
                        "Can I install on a wooden door?"
                    ],
                    'actions' => [
                        ['label' => 'Browse Full Catalog', 'url' => url('shop')],
                        ['label' => 'Request Consultation Callback', 'url' => url('contact')],
                    ],
                ];

            case ChatbotIntentService::INTENT_PRODUCT_DETAIL:
                if (!$activeProduct) {
                    return $this->generateResponse($message, ['intent' => ChatbotIntentService::INTENT_EXPLORE_PRODUCTS]);
                }

                $name = $activeProduct['name'] ?? 'Smart Door Lock';
                $price = isset($activeProduct['sale_price']) ? '₹' . number_format((float)$activeProduct['sale_price']) : '';
                $mrp = isset($activeProduct['price']) ? '₹' . number_format((float)$activeProduct['price']) : '';
                $slug = $activeProduct['slug'] ?? 'shop';
                $warranty = $activeProduct['warranty'] ?? '2-Year Official Manufacturer Warranty';
                $installation = $activeProduct['installation'] ?? 'Free Professional Installation';

                $features = array_slice($activeProduct['features'] ?? [], 0, 5);
                $featList = "";
                foreach ($features as $f) {
                    $featList .= "  ✓ {$f}\n";
                }

                $accessMethods = implode(', ', array_slice($activeProduct['access_methods'] ?? [], 0, 6));

                return [
                    'reply' => "### {$name}\n\n**Price**: **{$price}** " . ($mrp ? "<del class='text-muted'>{$mrp}</del>" : "") . " (Inclusive of all taxes)\n\n**Key Features**:\n{$featList}\n**Supported Access Methods**: {$accessMethods}\n\n🛡️ **Warranty**: {$warranty}\n🛠️ **Installation**: {$installation}",
                    'suggestions' => [
                        "Does this support fingerprint access?",
                        "Will this fit my door?",
                        "What is included in the box?"
                    ],
                    'actions' => [
                        ['label' => 'View Full Product Details', 'url' => url('product/' . $slug)],
                        ['label' => 'Order Now', 'url' => url('product/' . $slug)],
                    ],
                ];

            case ChatbotIntentService::INTENT_PRODUCT_FEATURE_CHECK:
                if (!$activeProduct) {
                    return [
                        'reply' => "Could you please specify which smart lock model you are referring to? You can view all our models on our [Products Page](" . url('shop') . ").",
                        'suggestions' => ["HOMEIQ Black AI Face Recognition Lock", "HOMEIQ Vision 2K Video Lock", "HOMEIQ Touch Pro Biometric Lock"],
                    ];
                }

                $productName = $activeProduct['name'];
                $cleanMsg = mb_strtolower($message);

                $checks = [];
                // Fingerprint
                if (str_contains($cleanMsg, 'fingerprint')) {
                    $hasFp = false;
                    foreach ($activeProduct['access_methods'] ?? [] as $am) {
                        if (str_contains(mb_strtolower($am), 'fingerprint')) $hasFp = true;
                    }
                    $checks[] = $hasFp 
                        ? "✅ **Yes**, the {$productName} supports high-speed semiconductor 3D fingerprint recognition (<0.5s response time)."
                        : "❌ No, this specific model does not have a fingerprint sensor.";
                }

                // Face Recognition
                if (str_contains($cleanMsg, 'face')) {
                    $hasFace = false;
                    foreach ($activeProduct['access_methods'] ?? [] as $am) {
                        if (str_contains(mb_strtolower($am), 'face')) $hasFace = true;
                    }
                    $checks[] = $hasFace
                        ? "✅ **Yes**, the {$productName} features 3D structured light AI Face Recognition that works accurately even in total darkness."
                        : "ℹ️ Face recognition is available on our flagship **[HOMEIQ Black AI Face Recognition Smart Lock](" . url('product/homeiq-black-ai-face-recognition-smart-door-lock') . ")**.";
                }

                // Wi-Fi / App
                if (str_contains($cleanMsg, 'wifi') || str_contains($cleanMsg, 'app') || str_contains($cleanMsg, 'remote')) {
                    $checks[] = "✅ **Yes**, it connects to the Tuya Smart mobile app (iOS & Android) via 2.4 GHz Wi-Fi for remote unlocking, real-time activity alerts, and temporary OTP guest codes.";
                }

                // Camera / Video
                if (str_contains($cleanMsg, 'camera') || str_contains($cleanMsg, 'video')) {
                    $hasCam = str_contains(mb_strtolower($productName), 'vision') || str_contains(mb_strtolower($productName), 'video') || str_contains(mb_strtolower($productName), 'face');
                    $checks[] = $hasCam
                        ? "✅ **Yes**, this model includes an integrated HD camera with two-way audio talk and motion detection recording."
                        : "ℹ️ Built-in video camera intercom is featured on our **[HOMEIQ Vision 2K Video Doorbell Lock](" . url('product/homeiq-vision-2k-video-doorbell-smart-lock') . ")**.";
                }

                // Fallback feature answer
                if (empty($checks)) {
                    $allAccess = implode(', ', $activeProduct['access_methods'] ?? []);
                    $checks[] = "The **{$productName}** supports the following verified access modes: **{$allAccess}**.";
                }

                return [
                    'reply' => implode("\n\n", $checks),
                    'suggestions' => [
                        "How long does the battery last?",
                        "What is the warranty period?",
                        "Will this fit my existing wooden door?"
                    ],
                    'actions' => [
                        ['label' => 'View Specifications', 'url' => url('product/' . ($activeProduct['slug'] ?? 'shop'))],
                    ],
                ];

            case ChatbotIntentService::INTENT_RECOMMEND_PRODUCT:
            case ChatbotIntentService::INTENT_COMPARE_PRODUCTS:
                return [
                    'reply' => "### Choosing the Right Smart Lock for Your Home:\n\n1. **HOMEIQ Black AI Face Recognition Lock (₹19,999)**: Best for total hands-free entry and families with children.\n2. **HOMEIQ Vision 2K Video Doorbell Lock (₹21,499)**: Best if you want a built-in security camera, indoor IPS monitor screen, and live smartphone intercom.\n3. **HOMEIQ VeinGuard Palm Vein Lock (₹22,999)**: Best for contactless hygiene and elderly members with faded or worn fingerprints.\n4. **HOMEIQ Touch Pro Biometric Lock (₹14,499)**: Best value handle-integrated fingerprint lock for modern apartments.\n\nAll models fit standard residential doors (35mm-90mm) and include **Free Doorstep Installation**.",
                    'suggestions' => [
                        "Tell me about the Vision 2K lock",
                        "What is your warranty coverage?",
                        "Request a free callback from an expert"
                    ],
                    'actions' => [
                        ['label' => 'Compare on Shop Page', 'url' => url('shop')],
                        ['label' => 'Request Expert Callback', 'url' => url('contact')],
                    ],
                ];

            case ChatbotIntentService::INTENT_WARRANTY_AMAZON:
                return [
                    'reply' => "Yes! Products purchased through **Amazon**, **Flipkart**, or the official **HOMEIQ website** are 100% eligible for official manufacturer warranty registration right here.\n\nPlease keep your **purchase invoice** and **product serial number** ready before starting the registration process.",
                    'suggestions' => [
                        "What documents are required?",
                        "What if I lose my purchase invoice?",
                        "How can I request warranty service?"
                    ],
                    'actions' => [
                        ['label' => 'Register Your Warranty Now', 'url' => url('warranty-registration')],
                    ],
                ];

            case ChatbotIntentService::INTENT_WARRANTY_DOCUMENTS:
                return [
                    'reply' => "Please keep your **purchase invoice** (PDF or photo) and **product serial number** ready before starting your registration.\n\n- The invoice can be downloaded directly from your Amazon, Flipkart, or HOMEIQ order account.\n- The serial number is printed on the product packaging and on the lock label (format `HIQ-XXXX-XXXX`).",
                    'suggestions' => [
                        "What if I lose my purchase invoice?",
                        "Can I register a product purchased from Amazon?",
                        "What is covered under warranty?"
                    ],
                    'actions' => [
                        ['label' => 'Go to Warranty Registration', 'url' => url('warranty-registration')],
                    ],
                ];

            case ChatbotIntentService::INTENT_WARRANTY_LOST_INVOICE:
                return [
                    'reply' => "If you have lost your purchase invoice, please contact HOMEIQ customer support for assistance in verifying your purchase.\n\nIf you purchased via Amazon or Flipkart, you can easily re-download your tax invoice under *Your Orders → Download Invoice*. Our support team can also verify your purchase using your platform Order ID.",
                    'suggestions' => [
                        "How can I contact customer support?",
                        "How can I request warranty service?",
                        "What documents are required?"
                    ],
                    'actions' => [
                        ['label' => 'Contact Support Team', 'url' => url('contact')],
                        ['label' => 'Register Warranty', 'url' => url('warranty-registration')],
                    ],
                ];

            case ChatbotIntentService::INTENT_WARRANTY_CLAIM:
                return [
                    'reply' => "To request warranty service, contact our customer support team with your **registration ID** (e.g. `HIQ-WR-000001`) and product details.\n\nA certified security technician will be scheduled to visit your doorstep for on-site diagnostic testing, repair, or component replacement.",
                    'suggestions' => [
                        "What is covered under warranty?",
                        "What if I lose my purchase invoice?",
                        "Contact customer support"
                    ],
                    'actions' => [
                        ['label' => 'Contact Support Desk', 'url' => url('contact')],
                    ],
                ];

            case ChatbotIntentService::INTENT_WARRANTY_COVERAGE:
            case ChatbotIntentService::INTENT_WARRANTY_GENERAL:
                return [
                    'reply' => "Warranty coverage depends on the product category and the warranty terms applicable to your purchase. Please refer to your product warranty documentation.\n\n- **Smart Door Locks**: 24 Months (2 Years) Comprehensive Manufacturer Warranty covering the electronic mortise, AI sensors, motherboard, and lock body.\n- **Video Door Phones & Modules**: 12 Months (1 Year) Warranty.\n- Products from Amazon, Flipkart, and our official store are all eligible.",
                    'suggestions' => [
                        "Can I register an Amazon or Flipkart purchase?",
                        "What documents are required?",
                        "How can I request warranty service?"
                    ],
                    'actions' => [
                        ['label' => 'Register Your Warranty', 'url' => url('warranty-registration')],
                    ],
                ];

            case ChatbotIntentService::INTENT_INSTALLATION:
                return [
                    'reply' => "Every HOMEIQ smart door lock includes **Free Professional Doorstep Installation**!\n\n- **Door Compatibility**: Our standard mortise fits door thicknesses from **35mm to 90mm**, which accommodates over 98% of residential wooden, teak, veneer, steel security, and composite doors.\n- **How It Works**: When your lock is delivered, our scheduling team assigns a certified technician in your city who visits your home, precision-cuts the mortise pocket, mounts the lock, and sets up your mobile app and biometrics.",
                    'suggestions' => [
                        "What happens if the battery drains?",
                        "Which smart lock is suitable for my home?",
                        "Request a callback from an expert"
                    ],
                    'actions' => [
                        ['label' => 'Explore Smart Locks', 'url' => url('shop')],
                        ['label' => 'Request Callback', 'url' => url('contact')],
                    ],
                ];

            case ChatbotIntentService::INTENT_BATTERY_EMERGENCY:
                return [
                    'reply' => "HOMEIQ smart locks feature a dual emergency fail-safe backup system:\n\n1. **External USB-C Emergency Power Port**: At the bottom of the front lock body, you can plug in any standard smartphone power bank for instantaneous power to unlock via fingerprint, PIN, or app.\n2. **Mechanical Override Keys**: Every lock includes 2 high-security physical keys that operate mechanically without electrical power.\n\nOur lithium-ion batteries typically last **8 to 12 months** on a single charge and notify you with audio alarms and app alerts when battery power drops below 20%.",
                    'suggestions' => [
                        "How do I install the lock?",
                        "What is covered under warranty?",
                        "Explore HOMEIQ Products"
                    ],
                    'actions' => [
                        ['label' => 'View Technical FAQs', 'url' => url('faq')],
                    ],
                ];

            case ChatbotIntentService::INTENT_SHIPPING_DELIVERY:
                return [
                    'reply' => "We offer **Free Express Shipping on all orders above ₹5,000** across India.\n\n- **Metro Cities**: Delivered within **3 to 5 business days**.\n- **Rest of India**: Delivered within **5 to 7 business days**.\n- All shipments are insured and handled by premium courier partners with live tracking.",
                    'suggestions' => [
                        "How can I track my order?",
                        "What is your return and refund policy?",
                        "Explore HOMEIQ Products"
                    ],
                    'actions' => [
                        ['label' => 'Track Your Order', 'url' => url('order-tracking')],
                        ['label' => 'View Shipping Policy', 'url' => url('shipping-policy')],
                    ],
                ];

            case ChatbotIntentService::INTENT_ORDER_TRACKING:
                return [
                    'reply' => "You can track your order live using your **Order Number** (e.g. `HIQ-ORD-12345`) and registered email or phone number on our [Order Tracking page](" . url('order-tracking') . ").\n\nIf you have an active HOMEIQ customer account, you can also log in to view all your past and active orders.",
                    'suggestions' => [
                        "What are your delivery timelines?",
                        "What is your return policy?",
                        "Contact customer support"
                    ],
                    'actions' => [
                        ['label' => 'Open Order Tracking', 'url' => url('order-tracking')],
                        ['label' => 'Customer Sign In', 'url' => url('login')],
                    ],
                ];

            case ChatbotIntentService::INTENT_RETURNS_REFUNDS:
                return [
                    'reply' => "HOMEIQ provides a **7-Day Hassle-Free Replacement Policy** for any product delivered with manufacturing defects or transit damage.\n\n- If a replacement cannot be provided, a **100% full refund** is processed back to your original payment method within 5–7 business days.\n- Orders can also be cancelled free of charge prior to shipping dispatch.",
                    'suggestions' => [
                        "How do I contact customer support?",
                        "What is covered under warranty?",
                        "Track my order"
                    ],
                    'actions' => [
                        ['label' => 'Read Full Returns Policy', 'url' => url('returns-policy')],
                        ['label' => 'Contact Support', 'url' => url('contact')],
                    ],
                ];

            case ChatbotIntentService::INTENT_CALLBACK_REQUEST:
                return [
                    'reply' => "We'd love to help! You can request a **Free Product Consultation & Callback** from our security team.\n\nOur expert will call you to discuss your door thickness, recommend the best smart lock model, and arrange doorstep installation at your convenience.",
                    'suggestions' => [
                        "Help me choose a smart lock",
                        "Can I register a product from Amazon?",
                        "What is your contact number?"
                    ],
                    'actions' => [
                        ['label' => 'Submit Callback Request', 'url' => url('contact')],
                    ],
                ];

            case ChatbotIntentService::INTENT_CONTACT:
                return [
                    'reply' => "### HOMEIQ Customer Support\n\nWe are here to assist you with sales, installation, and technical support:\n\n- 📞 **Phone / WhatsApp**: {$supportPhone}\n- 📧 **Email**: {$supportEmail}\n- 🕒 **Hours**: Monday – Saturday, 9:00 AM – 8:00 PM IST\n- 📍 **Headquarters**: HOMEIQ Technologies, 402 Smart Tech Park, Cyber City, Bengaluru 560100",
                    'suggestions' => [
                        "Request a Callback",
                        "Warranty Registration",
                        "Explore HOMEIQ Products"
                    ],
                    'actions' => [
                        ['label' => 'Open Contact Page', 'url' => url('contact')],
                    ],
                ];

            case 'KB_MATCH':
                $kb = $intentData['entities']['kb_entry'] ?? null;
                if ($kb) {
                    $actions = [];
                    if (!empty($kb['link_url'])) {
                        $actions[] = [
                            'label' => $kb['link_label'] ?: 'Learn More',
                            'url' => url(ltrim($kb['link_url'], '/')),
                        ];
                    }
                    return [
                        'reply' => $kb['answer'],
                        'suggestions' => [
                            "Explore HOMEIQ Products",
                            "Warranty Registration",
                            "Request a Callback"
                        ],
                        'actions' => $actions,
                    ];
                }
                // Fallthrough to fallback

            case ChatbotIntentService::INTENT_FALLBACK:
            default:
                return [
                    'reply' => "I'm sorry, I couldn't find confirmed information about that specific request in our knowledge base.\n\nOur HOMEIQ support team is readily available to answer your technical questions or check compatibility. Would you like to **request a callback** or contact our support team directly?",
                    'suggestions' => [
                        "Explore HOMEIQ Products",
                        "Help Me Choose a Smart Lock",
                        "Warranty Registration",
                        "Request a Callback",
                        "Contact Customer Support"
                    ],
                    'actions' => [
                        ['label' => 'Request a Callback', 'url' => url('contact')],
                        ['label' => 'Contact Support Desk', 'url' => url('contact')],
                    ],
                ];
        }
    }
}
