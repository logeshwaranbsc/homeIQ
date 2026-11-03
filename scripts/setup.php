<?php
declare(strict_types=1);

/**
 * HOMEIQ System Initializer & Database Seeder
 * Run via CLI: php scripts/setup.php
 */

$baseDir = dirname(__DIR__);
require_once $baseDir . '/app/Core/App.php';

use App\Core\App;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\BannerRepository;
use App\Repositories\AdminRepository;
use App\Repositories\CouponRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\InventoryRepository;

App::init($baseDir);

echo "Starting HOMEIQ initialization...\n";

// 1. Categories (Exclusively 8 Allowed Digital Lock Categories)
$categoryRepo = new CategoryRepository();
$categoriesData = [
    [
        'name' => 'AI Face Recognition Smart Locks',
        'slug' => 'ai-face-recognition-smart-locks',
        'description' => 'Flagship locks featuring 3D structured-light infrared face scanning for hands-free instant door unlock.',
        'image' => 'products/homeiq-ai-face-lock.svg',
        'display_order' => 1,
        'is_active' => true,
    ],
    [
        'name' => 'Fingerprint Smart Locks',
        'slug' => 'fingerprint-smart-locks',
        'description' => 'Semiconductor biometric fingerprint door locks with rapid sub-0.5 second recognition speed.',
        'image' => 'products/fingerprint-lock.svg',
        'display_order' => 2,
        'is_active' => true,
    ],
    [
        'name' => 'Palm Vein Recognition Smart Locks',
        'slug' => 'palm-vein-recognition-smart-locks',
        'description' => 'Military-grade contactless vascular biometrics reading internal subcutaneous vein patterns.',
        'image' => 'products/palm-vein-lock.svg',
        'display_order' => 3,
        'is_active' => true,
    ],
    [
        'name' => 'Wi-Fi and App-Controlled Smart Locks',
        'slug' => 'wifi-and-app-controlled-smart-locks',
        'description' => 'Connect via Tuya Smart or Smart Life for real-time unlock logs, one-time passwords, and remote monitoring.',
        'image' => 'products/wifi-smart-lock.svg',
        'display_order' => 4,
        'is_active' => true,
    ],
    [
        'name' => 'PIN and RFID Digital Locks',
        'slug' => 'pin-and-rfid-digital-locks',
        'description' => 'Anti-peeping backlit touch keypads and encrypted high-frequency RFID cards for homes and offices.',
        'image' => 'products/pin-rfid-lock.svg',
        'display_order' => 5,
        'is_active' => true,
    ],
    [
        'name' => 'Video Door Smart Locks',
        'slug' => 'video-door-smart-locks',
        'description' => 'Integrated wide-angle HD camera with two-way audio intercom and video doorbell functionality.',
        'image' => 'products/video-door-lock.svg',
        'display_order' => 6,
        'is_active' => true,
    ],
    [
        'name' => 'Digital Mortise Locks',
        'slug' => 'digital-mortise-locks',
        'description' => 'Multi-point heavy-duty stainless steel security mortise locks engineered for main exterior doors.',
        'image' => 'products/mortise-lock.svg',
        'display_order' => 7,
        'is_active' => true,
    ],
    [
        'name' => 'Smart Lock Accessories',
        'slug' => 'smart-lock-accessories',
        'description' => 'OEM lithium-ion replacement battery packs, encrypted RFID key fobs, Wi-Fi gateways, and strike plates.',
        'image' => 'products/accessories-battery.svg',
        'display_order' => 8,
        'is_active' => true,
    ],
];

// Clean and populate categories
$categoryRepo->atomicWrite([]);
$savedCategories = [];
foreach ($categoriesData as $cat) {
    $created = $categoryRepo->create($cat);
    $savedCategories[$created['slug']] = $created;
}
echo "  [x] Seeded " . count($savedCategories) . " digital lock categories.\n";

// 2. Products (Including Flagship HOMEIQ Black AI Face Lock + models in all 8 categories)
$productRepo = new ProductRepository();
$inventoryRepo = new InventoryRepository();
$productRepo->atomicWrite([]);
$inventoryRepo->atomicWrite([]);

$products = [
    // 1. Flagship AI Face Lock
    [
        'name' => 'HOMEIQ Black AI Face Recognition Smart Door Lock',
        'slug' => 'homeiq-black-ai-face-recognition-smart-door-lock',
        'sku' => 'HIQ-AI-F900-BLK',
        'category' => 'AI Face Recognition Smart Locks',
        'category_slug' => 'ai-face-recognition-smart-locks',
        'price' => 24999.00,
        'sale_price' => 19999.00,
        'stock' => 42,
        'color' => 'Black',
        'lock_type' => 'Mortise Lock',
        'dimensions' => '40 × 17 × 9 cm',
        'weight' => '1.5 kg',
        'warranty' => '2-Year Official Manufacturer Warranty',
        'installation' => 'Free Professional Door Installation Included',
        'is_featured' => true,
        'is_active' => true,
        'rating' => 4.8,
        'reviews_count' => 244,
        'main_image' => 'products/homeiq-ai-face-lock.svg',
        'short_description' => 'HOMEIQ Black AI Smart Door Lock — 9-in-1 keyless smart door lock with AI Face Recognition, Palm Vein, Fingerprint, PIN, RFID Card, Mechanical Key, Temporary Password and Tuya App Remote Access.',
        'full_description' => "Smart Security. Keyless Convenience. Complete Access Control.\n\nThe HOMEIQ Black Smart Door Lock is a premium biometric digital door lock designed for modern homes, apartments, villas, offices and rental properties.\n\nWith 9-in-1 keyless access, users can unlock their doors using AI Face Recognition, Palm Vein Recognition, Fingerprint, PIN Password, RFID Card, Mechanical Key, Temporary Password, Tuya App Remote Access or Combination Unlock.\n\nIts biometric access methods, smart app control, automatic locking, access history and security alerts provide a convenient and connected door security experience.",
        'features' => [
            'AI Face Recognition with 3D Structured Light',
            'Palm Vein and Fingerprint Unlock (< 0.5s)',
            'PIN and RFID Card Access',
            'Tuya App Remote Access & History Log',
            'Temporary & One-Time Password Support',
            'HD Camera and Two-Way Video Calling',
            'Automatic Locking and Tamper Alerts',
            'Rechargeable Battery with Emergency USB-C Charging',
            'Premium Black Metal Aerospace Alloy Finish'
        ],
        'whats_included' => [
            'Front and Back Lock Body',
            '6068 Stainless Steel Security Mortise',
            'High-Capacity Rechargeable Lithium Battery',
            '2x Encrypted RFID Smart Cards',
            '2x Mechanical Emergency Keys',
            'Installation Template & Heavy-Duty Screw Pack',
            'User Guide & Quick Setup Manual'
        ],
        'access_methods' => ['AI Face Recognition', 'Palm Vein', 'Fingerprint', 'PIN Password', 'RFID Card', 'Tuya App', 'Mechanical Key', 'Temporary OTP', 'Combination'],
        'connectivity' => ['Wi-Fi 2.4GHz', 'Tuya Smart App', 'Bluetooth BLE'],
        'specifications' => [
            'Brand' => 'HOMEIQ',
            'Lock Type' => 'Mortise Lock',
            'Chassis Material' => 'Aviation Zinc Alloy & Tempered Glass',
            'Colour' => 'Obsidian Black',
            'Access Methods' => '9-in-1 Keyless Access',
            'Fingerprint Sensor' => 'Semiconductor 3D Live Recognition',
            'Recognition Speed' => '< 0.5 seconds',
            'App Support' => 'Tuya Smart App (iOS & Android)',
            'Battery' => 'Rechargeable 4200mAh Lithium-ion',
            'Emergency Power' => 'USB-C External Port',
            'Door Thickness Suitability' => '35mm - 90mm',
            'Dimensions' => '40 × 17 × 9 cm',
            'Weight' => '1.5 kg'
        ]
    ],
    // 2. Touch Pro Fingerprint
    [
        'name' => 'HOMEIQ Touch Pro Biometric Fingerprint Smart Lock',
        'slug' => 'homeiq-touch-pro-biometric-fingerprint-smart-lock',
        'sku' => 'HIQ-FP-T700-BLK',
        'category' => 'Fingerprint Smart Locks',
        'category_slug' => 'fingerprint-smart-locks',
        'price' => 18999.00,
        'sale_price' => 14499.00,
        'stock' => 28,
        'color' => 'Space Grey',
        'lock_type' => 'Mortise Lock',
        'dimensions' => '38 × 15 × 8 cm',
        'weight' => '1.4 kg',
        'warranty' => '2-Year Official Manufacturer Warranty',
        'installation' => 'Free Professional Installation',
        'is_featured' => true,
        'is_active' => true,
        'rating' => 4.9,
        'reviews_count' => 118,
        'main_image' => 'products/fingerprint-lock.svg',
        'short_description' => 'Fast touch fingerprint digital door lock with integrated semiconductor scanner on handle and 100-fingerprint storage.',
        'full_description' => 'Unlock in one natural fluid motion. The handle-integrated semiconductor fingerprint sensor scans your biometric profile in under 0.4 seconds as you grip the handle.',
        'features' => [
            'Semiconductor Fingerprint on Handle',
            'Anti-Peep Virtual Password Security',
            'Auto-Locking Mechanism with Sensor Bolt',
            'Up to 100 User Fingerprints Storage',
            'Emergency Mechanical Key Override'
        ],
        'whats_included' => ['Front & Rear Panels', 'Mortise Lock Body', '4x AA Alkaline Batteries', '2x Physical Keys', 'Fitting Kit'],
        'access_methods' => ['Fingerprint', 'PIN Password', 'RFID Card', 'Mechanical Key'],
        'connectivity' => ['Bluetooth BLE', 'Tuya Smart App'],
        'specifications' => [
            'Brand' => 'HOMEIQ',
            'Finish' => 'Matte Gunmetal / Space Grey',
            'Fingerprint Capacity' => '100 Biometric Profiles',
            'Battery' => '4x AA Standard Alkaline (12-Month Life)',
            'Door Compatibility' => 'Wooden, Flush & Metal Doors (38-75mm)'
        ]
    ],
    // 3. Palm Vein Lock
    [
        'name' => 'HOMEIQ VeinGuard Contactless Palm Vein Smart Lock',
        'slug' => 'homeiq-veinguard-contactless-palm-vein-smart-lock',
        'sku' => 'HIQ-PV-V800-TIT',
        'category' => 'Palm Vein Recognition Smart Locks',
        'category_slug' => 'palm-vein-recognition-smart-locks',
        'price' => 28999.00,
        'sale_price' => 22999.00,
        'stock' => 19,
        'color' => 'Titanium Grey',
        'lock_type' => 'Push-Pull Mortise',
        'dimensions' => '42 × 18 × 9 cm',
        'weight' => '1.8 kg',
        'warranty' => '3-Year Premium Warranty',
        'installation' => 'Free Professional Installation',
        'is_featured' => true,
        'is_active' => true,
        'rating' => 5.0,
        'reviews_count' => 64,
        'main_image' => 'products/palm-vein-lock.svg',
        'short_description' => 'Ultra-secure near-infrared biometric palm vein sensor that cannot be spoofed, duplicated, or smudged.',
        'full_description' => 'Near-infrared light detects the unique deoxidized hemoglobin flow inside your palm veins. Completely hygienic, contactless, and unaffected by worn fingerprints or wet hands.',
        'features' => [
            'Contactless Palm Vein Vascular Recognition',
            'Impossible to Replicate or Spoof Biometrics',
            'Integrated Push-Pull Automatic Deadbolt',
            'Tuya Remote Authorization',
            'Dual Verification Mode'
        ],
        'whats_included' => ['VeinGuard Exterior & Interior Lock', 'Multi-Latch Mortise', 'Rechargeable 5000mAh Battery', '2x Backup Keys', 'Cutout Template'],
        'access_methods' => ['Palm Vein', 'Fingerprint', 'PIN Password', 'RFID Card', 'Tuya App', 'Mechanical Key'],
        'connectivity' => ['Wi-Fi 2.4GHz', 'Tuya Smart App'],
        'specifications' => [
            'Brand' => 'HOMEIQ',
            'Biometric Technology' => 'Subcutaneous Palm Vein Infrared Scan',
            'False Acceptance Rate' => '< 0.00001%',
            'Battery Life' => 'Approx. 8-10 Months per Charge'
        ]
    ],
    // 4. Wi-Fi Smart Deadbolt
    [
        'name' => 'HOMEIQ Connect Wi-Fi Smart Deadbolt Lock',
        'slug' => 'homeiq-connect-wifi-smart-deadbolt-lock',
        'sku' => 'HIQ-WF-D300-BLK',
        'category' => 'Wi-Fi and App-Controlled Smart Locks',
        'category_slug' => 'wifi-and-app-controlled-smart-locks',
        'price' => 15999.00,
        'sale_price' => 11999.00,
        'stock' => 35,
        'color' => 'Black',
        'lock_type' => 'Deadbolt',
        'dimensions' => '25 × 12 × 7 cm',
        'weight' => '1.1 kg',
        'warranty' => '2-Year Official Manufacturer Warranty',
        'installation' => 'Free Professional Installation',
        'is_featured' => false,
        'is_active' => true,
        'rating' => 4.7,
        'reviews_count' => 86,
        'main_image' => 'products/wifi-smart-lock.svg',
        'short_description' => 'Direct Wi-Fi connected smart deadbolt with built-in Tuya remote access, access logs, and one-time visitor passwords.',
        'full_description' => 'Retrofits cleanly onto standard deadbolt-prepped residential doors. Directly connects to your home Wi-Fi network without requiring any third-party bridge or hub.',
        'features' => [
            'Direct Wi-Fi Connection (No Hub Required)',
            'Real-Time Smartphone Entry Notifications',
            'Timed One-Time Passwords for Guests & Cleaners',
            'Low Battery Warning via App & Beep Alert'
        ],
        'whats_included' => ['Smart Deadbolt Escutcheon', 'Adjustable 60/70mm Backset Deadbolt', 'Strike Plate', '4x AA Batteries', '2x Keys'],
        'access_methods' => ['Tuya App', 'PIN Password', 'Fingerprint', 'Mechanical Key'],
        'connectivity' => ['Direct Wi-Fi 2.4GHz', 'Tuya Smart'],
        'specifications' => [
            'Brand' => 'HOMEIQ',
            'Standard Backset' => 'Adjustable 60mm or 70mm',
            'Connectivity' => 'Built-in Wi-Fi 802.11 b/g/n'
        ]
    ],
    // 5. PIN & RFID Digital Lock
    [
        'name' => 'HOMEIQ Keyless PIN & RFID Commercial Door Lock',
        'slug' => 'homeiq-keyless-pin-rfid-commercial-door-lock',
        'sku' => 'HIQ-CR-P400-SIL',
        'category' => 'PIN and RFID Digital Locks',
        'category_slug' => 'pin-and-rfid-digital-locks',
        'price' => 12999.00,
        'sale_price' => 9499.00,
        'stock' => 50,
        'color' => 'Satin Silver',
        'lock_type' => 'Mortise Lock',
        'dimensions' => '32 × 14 × 7 cm',
        'weight' => '1.3 kg',
        'warranty' => '2-Year Official Warranty',
        'installation' => 'Free Professional Installation',
        'is_featured' => false,
        'is_active' => true,
        'rating' => 4.6,
        'reviews_count' => 52,
        'main_image' => 'products/pin-rfid-lock.svg',
        'short_description' => 'Heavy-duty digital keypad lock with encrypted RFID card access designed for Airbnb, co-working, and residential rentals.',
        'full_description' => 'Simple, dependable security. Features anti-peeping scramble keypad code entry so observers cannot decipher your passcode.',
        'features' => [
            'Anti-Peep Scramble Code Technology',
            '13.56MHz High-Security RFID Cards',
            'Passage Mode for High-Traffic Office Hours',
            'IP54 Weather Resistant External Escutcheon'
        ],
        'whats_included' => ['Digital Keypad Handle Lock', 'Mortise', '4x Encrypted RFID Cards', '2x Computer Keys', 'Mounting Hardware'],
        'access_methods' => ['PIN Password', 'RFID Card', 'Mechanical Key'],
        'connectivity' => ['Standalone Keyless'],
        'specifications' => [
            'Brand' => 'HOMEIQ',
            'RFID Frequency' => '13.56MHz Mifare Classic Encrypted',
            'Water Resistance' => 'IP54 Rated External Unit'
        ]
    ],
    // 6. Video Door Smart Lock
    [
        'name' => 'HOMEIQ Vision 2K Video Doorbell Smart Lock',
        'slug' => 'homeiq-vision-2k-video-doorbell-smart-lock',
        'sku' => 'HIQ-VD-V950-BLK',
        'category' => 'Video Door Smart Locks',
        'category_slug' => 'video-door-smart-locks',
        'price' => 27499.00,
        'sale_price' => 21499.00,
        'stock' => 14,
        'color' => 'Black',
        'lock_type' => 'Push-Pull Mortise',
        'dimensions' => '42 × 18 × 9 cm',
        'weight' => '1.9 kg',
        'warranty' => '2-Year Official Warranty',
        'installation' => 'Free Professional Installation',
        'is_featured' => true,
        'is_active' => true,
        'rating' => 4.9,
        'reviews_count' => 97,
        'main_image' => 'products/video-door-lock.svg',
        'short_description' => 'Integrated 2K HD wide-angle camera with two-way audio talk and motion-activated security video doorbell.',
        'full_description' => 'Combines a premium automatic deadbolt lock with an ultra-clear 2K smart video doorbell. When visitors ring your bell, receive a live video call directly on your smartphone.',
        'features' => [
            '2K Ultra HD Camera with 160° Wide Angle',
            'Two-Way Live Audio Intercom',
            'PIR Motion Detection Video Recording',
            'Built-In 4-Inch Interior IPS Color Screen',
            'Semiconductor Fingerprint & Face Scan'
        ],
        'whats_included' => ['Vision 2K Smart Lock (Front & Rear with Screen)', 'Electronic Mortise', '5000mAh Battery', '2x RFID Cards', '2x Emergency Keys'],
        'access_methods' => ['Fingerprint', 'Face Scan', 'PIN Password', 'RFID Card', 'Tuya App Video Call', 'Mechanical Key'],
        'connectivity' => ['Wi-Fi 2.4GHz', 'Tuya Smart / Smart Life'],
        'specifications' => [
            'Camera' => '2K QHD (2560x1440) Night Vision',
            'Screen' => '4.0-inch Color IPS Display inside',
            'Storage' => 'Cloud & MicroSD Slot Support (up to 128GB)'
        ]
    ],
    // 7. Digital Mortise Lock
    [
        'name' => 'HOMEIQ Heavy Duty Smart Mortise 6068 Lock',
        'slug' => 'homeiq-heavy-duty-smart-mortise-6068-lock',
        'sku' => 'HIQ-MT-M600-STL',
        'category' => 'Digital Mortise Locks',
        'category_slug' => 'digital-mortise-locks',
        'price' => 21999.00,
        'sale_price' => 16999.00,
        'stock' => 22,
        'color' => 'Brushed Gunmetal',
        'lock_type' => 'Mortise Lock',
        'dimensions' => '39 × 16 × 8 cm',
        'weight' => '2.1 kg',
        'warranty' => '3-Year Commercial Warranty',
        'installation' => 'Free Professional Installation',
        'is_featured' => false,
        'is_active' => true,
        'rating' => 4.8,
        'reviews_count' => 41,
        'main_image' => 'products/mortise-lock.svg',
        'short_description' => 'Stainless steel triple deadbolt mortise body engineered for extreme tamper resistance and maximum break-in prevention.',
        'full_description' => 'Built with high-tensile 304 stainless steel solid deadbolts that resist sawing, prying, and heavy impact force.',
        'features' => [
            '304 Stainless Steel Triple Latch Mortise (6068)',
            'Anti-Tamper Physical Intrusion Alarm',
            'Biometric Semiconductor Fingerprint Sensor',
            'Class-C Anti-Drill Key Cylinder'
        ],
        'whats_included' => ['Heavy Duty Mortise Lockset', '6068 Stainless Steel Mortise', 'Hardware Pack', 'Batteries', 'Keys'],
        'access_methods' => ['Fingerprint', 'PIN Password', 'RFID Card', 'Mechanical Key'],
        'connectivity' => ['Bluetooth BLE', 'Tuya App'],
        'specifications' => [
            'Mortise Standard' => '6068 European Mortise Profile',
            'Deadbolt Strength' => 'Grade-1 Certified High-Security'
        ]
    ],
    // 8. Accessories Battery
    [
        'name' => 'HOMEIQ High-Capacity Rechargeable Li-Ion Battery Pack',
        'slug' => 'homeiq-rechargeable-li-ion-battery-pack-5000mah',
        'sku' => 'HIQ-ACC-BAT-5K',
        'category' => 'Smart Lock Accessories',
        'category_slug' => 'smart-lock-accessories',
        'price' => 1999.00,
        'sale_price' => 1499.00,
        'stock' => 75,
        'color' => 'Black',
        'lock_type' => 'Battery Accessory',
        'dimensions' => '10 × 6 × 2 cm',
        'weight' => '0.2 kg',
        'warranty' => '1-Year Warranty',
        'installation' => 'Plug & Play Self-Installation',
        'is_featured' => false,
        'is_active' => true,
        'rating' => 4.9,
        'reviews_count' => 38,
        'main_image' => 'products/accessories-battery.svg',
        'short_description' => 'Original spare 5000mAh rechargeable lithium battery pack with direct USB-C charging port for HOMEIQ AI & Video locks.',
        'full_description' => 'Keep a charged spare ready so your smart lock never runs out of power. Features direct USB-C fast recharging and internal over-voltage protection.',
        'features' => [
            '5000mAh Real Lithium-Ion Capacity',
            'Direct USB-C Charging Port with LED Indicator',
            'Over-charge and Short-Circuit Protection Circuitry',
            'Compatible with HOMEIQ AI Face & Vision Models'
        ],
        'whats_included' => ['5000mAh Li-ion Battery Pack', 'USB-A to USB-C Charging Cable'],
        'access_methods' => [],
        'connectivity' => [],
        'specifications' => [
            'Capacity' => '5000mAh 7.4V',
            'Charging Port' => 'USB Type-C (5V/2A)'
        ]
    ],
    // 9. Accessories Key Fobs
    [
        'name' => 'HOMEIQ Encrypted NFC RFID Smart Key Fobs (Pack of 4)',
        'slug' => 'homeiq-encrypted-nfc-rfid-smart-key-fobs-4-pack',
        'sku' => 'HIQ-ACC-FOB-4PK',
        'category' => 'Smart Lock Accessories',
        'category_slug' => 'smart-lock-accessories',
        'price' => 1199.00,
        'sale_price' => 799.00,
        'stock' => 120,
        'color' => 'Black & Gunmetal',
        'lock_type' => 'RFID Accessory',
        'dimensions' => '4 × 3 × 0.5 cm',
        'weight' => '0.05 kg',
        'warranty' => '1-Year Warranty',
        'installation' => 'One-Touch Pairing via App/Keypad',
        'is_featured' => false,
        'is_active' => true,
        'rating' => 4.8,
        'reviews_count' => 45,
        'main_image' => 'products/accessories-fobs.svg',
        'short_description' => 'Compact encrypted high-frequency RFID smart tags suitable for children and elderly family members.',
        'full_description' => 'High-security encrypted anti-cloning key fobs. Pair in seconds via your HOMEIQ door lock or app.',
        'features' => [
            'Military-Grade Rolling Encryption Protocol',
            'Waterproof & Scratch-Resistant Epoxy Coated',
            'Convenient Keychain Hole Included'
        ],
        'whats_included' => ['4x Encrypted RFID Smart Key Fobs'],
        'access_methods' => ['RFID Card'],
        'connectivity' => [],
        'specifications' => [
            'Frequency' => '13.56MHz Anti-Cloning Encryption',
            'Quantity' => '4 Units per Pack'
        ]
    ]
];

foreach ($products as $p) {
    $created = $productRepo->create($p);
    // Log initial inventory
    $inventoryRepo->logMovement(
        productId: $created['id'],
        productName: $created['name'],
        type: 'Initial',
        quantityChange: $created['stock'],
        stockBefore: 0,
        stockAfter: $created['stock'],
        reference: 'Initial Setup',
        notes: 'Initial stock intake upon catalog seed',
        performedBy: 'System Setup'
    );
}
echo "  [x] Seeded " . count($products) . " smart locks and accessories with inventory records.\n";

// 3. Hero Banners (Matching Section 5 exactly)
$bannerRepo = new BannerRepository();
$bannerRepo->atomicWrite([]);
$banners = [
    [
        'headline' => 'Advanced Security. Elegant Design.',
        'subheading' => 'Give your home the smart upgrade it deserves.',
        'supporting_text' => 'Starts from ₹9,499 | Free Installation | Technical Support | Warranty Protection',
        'offer_badge' => 'STARTS FROM ₹9,499',
        'cta_label' => 'SHOP SMART LOCKS',
        'cta_url' => 'shop',
        'display_order' => 1,
        'is_active' => true,
        'image' => 'banners/hero-banner-1.svg',
    ],
    [
        'headline' => 'Built for Security. Designed for Peace.',
        'subheading' => 'Discover premium smart locks at special prices.',
        'supporting_text' => 'Biometric precision meets architectural elegance.',
        'offer_badge' => 'UP TO 40% OFF',
        'cta_label' => 'SHOP NOW',
        'cta_url' => 'shop',
        'display_order' => 2,
        'is_active' => true,
        'image' => 'banners/hero-banner-2.svg',
    ],
    [
        'headline' => 'AI-Enabled Door Locks for Smarter Security',
        'subheading' => 'Protect the people and things you love.',
        'supporting_text' => 'AI-Powered Access | Biometric Access | Auto Lock Security',
        'offer_badge' => 'AI-POWERED ACCESS',
        'cta_label' => 'EXPLORE SMART LOCKS',
        'cta_url' => 'category/ai-face-recognition-smart-locks',
        'display_order' => 3,
        'is_active' => true,
        'image' => 'banners/hero-banner-3.svg',
    ],
    [
        'headline' => 'Your Smart Home Upgrade Starts Here.',
        'subheading' => 'Exclusive deals on HOMEIQ smart locks.',
        'supporting_text' => 'Free express shipping and nationwide door installation support.',
        'offer_badge' => 'FREE SHIPPING',
        'cta_label' => 'SHOP NOW',
        'cta_url' => 'shop',
        'display_order' => 4,
        'is_active' => true,
        'image' => 'banners/hero-banner-4.svg',
    ],
];

foreach ($banners as $b) {
    $bannerRepo->create($b);
}
echo "  [x] Seeded " . count($banners) . " configurable hero banners.\n";

// 4. Coupons
$couponRepo = new CouponRepository();
$couponRepo->atomicWrite([]);
$coupons = [
    [
        'code' => 'WELCOME10',
        'type' => 'percentage',
        'value' => 10,
        'min_order_amount' => 10000,
        'max_discount' => 2000,
        'expires_at' => date('Y-m-d', strtotime('+1 year')),
        'is_active' => true,
    ],
    [
        'code' => 'HOMEIQ500',
        'type' => 'fixed',
        'value' => 500,
        'min_order_amount' => 8000,
        'max_discount' => 500,
        'expires_at' => date('Y-m-d', strtotime('+1 year')),
        'is_active' => true,
    ]
];
foreach ($coupons as $c) {
    $couponRepo->create($c);
}
echo "  [x] Seeded active promotional coupons.\n";

// 5. Approved Customer Reviews for Authentic Experience
$reviewRepo = new ReviewRepository();
$reviewRepo->atomicWrite([]);
$sampleReviews = [
    [
        'product_id' => 'prod_1',
        'product_name' => 'HOMEIQ Black AI Face Recognition Smart Door Lock',
        'reviewer_name' => 'Vikramaditya S.',
        'rating' => 5,
        'title' => 'Exceptional face unlock speed and build quality',
        'comment' => 'The face recognition works even in low hallway lighting. Hands-free entry when carrying groceries is a game changer. The technician arrived on time for installation.',
        'verified_purchase' => true,
        'is_approved' => true,
        'is_site_review' => true,
        'review_type' => 'site',
    ],
    [
        'product_id' => 'prod_1',
        'product_name' => 'HOMEIQ Black AI Face Recognition Smart Door Lock',
        'reviewer_name' => 'Dr. Meera Nambiar',
        'rating' => 5,
        'title' => 'Solid aerospace build and great Tuya app integration',
        'comment' => 'Installed on our main teak wood villa door. The mortise is heavy duty stainless steel. The app sends instant alerts if someone rings the doorbell.',
        'verified_purchase' => true,
        'is_approved' => true,
        'is_site_review' => true,
        'review_type' => 'site',
    ],
    [
        'product_id' => 'prod_2',
        'product_name' => 'HOMEIQ Touch Pro Biometric Fingerprint Smart Lock',
        'reviewer_name' => 'Arun Kumar',
        'rating' => 5,
        'title' => 'Fingerprint on the handle is so natural',
        'comment' => 'Immediate unlock as you grip the handle. Battery life has been excellent after 4 months of daily use.',
        'verified_purchase' => true,
        'is_approved' => true,
        'is_site_review' => true,
        'review_type' => 'site',
    ]
];
foreach ($sampleReviews as $r) {
    $reviewRepo->create($r);
}
echo "  [x] Seeded authentic verified reviews.\n";

// 6. Settings Repository
$settingsRepo = new SettingsRepository();
$settingsRepo->save($settingsRepo->getDefaults());
echo "  [x] Initialized store settings & announcement bar.\n";

// 7. Initial Super Admin Account
$adminRepo = new AdminRepository();
$adminRepo->atomicWrite([]);
$adminUser = $adminRepo->create([
    'name' => 'Super Administrator',
    'username' => 'admin',
    'email' => 'admin@homeiq.com',
    'role' => 'Super Admin',
    'password_hash' => password_hash('Admin@HOMEIQ2026!', PASSWORD_DEFAULT),
    'is_active' => true,
]);
echo "  [x] Seeded primary administrator: username 'admin' / password 'Admin@HOMEIQ2026!'\n";

echo "\nHOMEIQ initialization complete! All data stored safely in storage/*.json\n";

