<?php
declare(strict_types=1);

/**
 * HOMEIQ Vector Product & Banner Asset Generator
 * Generates photorealistic high-fidelity SVG graphics for digital smart locks
 */

$baseDir = dirname(__DIR__);
$imagesDir = $baseDir . '/public/assets/images';
$productsDir = $baseDir . '/public/uploads/products';
$categoriesDir = $baseDir . '/public/uploads/categories';
$bannersDir = $baseDir . '/public/uploads/banners';

foreach ([$imagesDir, $productsDir, $categoriesDir, $bannersDir] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 1. Flagship AI Face Recognition Lock SVG
$aiLockSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 1000" width="100%" height="100%">
  <defs>
    <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#121316"/>
      <stop offset="50%" stop-color="#1a1c23"/>
      <stop offset="100%" stop-color="#0c0d10"/>
    </linearGradient>
    <linearGradient id="chassisGrad" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#1c1d21"/>
      <stop offset="25%" stop-color="#2a2c33"/>
      <stop offset="70%" stop-color="#1f2127"/>
      <stop offset="100%" stop-color="#141518"/>
    </linearGradient>
    <linearGradient id="metalBevel" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#606676"/>
      <stop offset="50%" stop-color="#32353e"/>
      <stop offset="100%" stop-color="#1a1b20"/>
    </linearGradient>
    <linearGradient id="glassGrad" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#181a20"/>
      <stop offset="100%" stop-color="#090a0d"/>
    </linearGradient>
    <linearGradient id="accentCyan" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#00d2ff"/>
      <stop offset="100%" stop-color="#0077ff"/>
    </linearGradient>
    <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
      <feGaussianBlur stdDeviation="6" result="blur"/>
      <feComposite in="SourceGraphic" in2="blur" operator="over"/>
    </filter>
    <filter id="subtleShadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="25" stdDeviation="35" flood-color="#000000" flood-opacity="0.8"/>
    </filter>
  </defs>

  <rect width="800" height="1000" fill="url(#bgGrad)"/>
  
  <!-- Subtle architectural background door panel grid -->
  <rect x="100" y="50" width="600" height="900" rx="8" fill="#14161a" stroke="#242730" stroke-width="1.5"/>
  <line x1="400" y1="50" x2="400" y2="950" stroke="#1f2229" stroke-width="1"/>

  <!-- Lock Body Shadow -->
  <g filter="url(#subtleShadow)">
    <!-- Outer Bevel Rim -->
    <rect x="290" y="100" width="220" height="800" rx="28" fill="url(#metalBevel)"/>
    <!-- Lock Main Chassis -->
    <rect x="294" y="104" width="212" height="792" rx="25" fill="url(#chassisGrad)"/>
  </g>

  <!-- Front Black Tempered Glass Panel -->
  <rect x="312" y="125" width="176" height="520" rx="16" fill="url(#glassGrad)" stroke="#2b2e38" stroke-width="1"/>

  <!-- AI Face Recognition Camera & 3D Sensor Pod -->
  <circle cx="400" cy="180" r="26" fill="#0c0d10" stroke="#2a2d36" stroke-width="2"/>
  <circle cx="400" cy="180" r="16" fill="#040810" stroke="url(#accentCyan)" stroke-width="1.5"/>
  <circle cx="400" cy="180" r="6" fill="#00d2ff" filter="url(#glow)"/>
  <circle cx="398" cy="178" r="2" fill="#ffffff"/>
  <!-- Infrared 3D Projectors -->
  <circle cx="348" cy="180" r="4" fill="#323745"/>
  <circle cx="452" cy="180" r="4" fill="#323745"/>
  <text x="400" y="222" font-family="'Manrope', sans-serif" font-size="9" fill="#00d2ff" text-anchor="middle" letter-spacing="2">3D AI FACE SCANNER</text>

  <!-- Biometric Palm / Fingerprint Scanner Module -->
  <circle cx="400" cy="275" r="28" fill="#111317" stroke="url(#accentCyan)" stroke-width="2" filter="url(#glow)"/>
  <path d="M 388,275 C 388,268 412,268 412,275 C 412,284 394,284 394,290" fill="none" stroke="#00d2ff" stroke-width="2" stroke-linecap="round"/>
  <circle cx="400" cy="275" r="34" fill="none" stroke="#252a36" stroke-width="1" stroke-dasharray="4,4"/>

  <!-- Backlit Digital Keypad Grid -->
  <g fill="#e2e8f0" font-family="'Inter', sans-serif" font-weight="500" font-size="16" text-anchor="middle">
    <!-- Row 1 -->
    <text x="350" y="340">1</text>
    <text x="400" y="340">2</text>
    <text x="450" y="340">3</text>
    <!-- Row 2 -->
    <text x="350" y="380">4</text>
    <text x="400" y="380">5</text>
    <text x="450" y="380">6</text>
    <!-- Row 3 -->
    <text x="350" y="420">7</text>
    <text x="400" y="420">8</text>
    <text x="450" y="420">9</text>
    <!-- Row 4 -->
    <text x="350" y="460" font-size="14">✱</text>
    <text x="400" y="460">0</text>
    <text x="450" y="460" font-size="14">#</text>
  </g>

  <!-- RFID Card Tap Zone Indicator -->
  <rect x="360" y="490" width="80" height="24" rx="4" fill="#16181f" stroke="#2d313d" stroke-width="1"/>
  <text x="400" y="506" font-family="'Inter', sans-serif" font-size="10" fill="#94a3b8" text-anchor="middle" letter-spacing="1">CARD</text>

  <!-- HOMEIQ Brand Wordmark -->
  <text x="400" y="570" font-family="'Manrope', sans-serif" font-weight="800" font-size="18" fill="#f8fafc" text-anchor="middle" letter-spacing="4">HOMEIQ</text>
  <text x="400" y="586" font-family="'Inter', sans-serif" font-weight="600" font-size="8" fill="#00d2ff" text-anchor="middle" letter-spacing="2">AI SECURITY</text>

  <!-- Push-Pull Ergonomic Solid Metal Handle -->
  <g filter="url(#subtleShadow)">
    <rect x="340" y="660" width="120" height="190" rx="14" fill="url(#metalBevel)"/>
    <rect x="344" y="664" width="112" height="182" rx="12" fill="url(#chassisGrad)"/>
    <rect x="356" y="675" width="88" height="160" rx="8" fill="#121317"/>
    <!-- Handle grip texture lines -->
    <line x1="375" y1="720" x2="425" y2="720" stroke="#2b2e38" stroke-width="2"/>
    <line x1="375" y1="740" x2="425" y2="740" stroke="#2b2e38" stroke-width="2"/>
    <line x1="375" y1="760" x2="425" y2="760" stroke="#2b2e38" stroke-width="2"/>
    <line x1="375" y1="780" x2="425" y2="780" stroke="#2b2e38" stroke-width="2"/>
  </g>

  <!-- Bottom Emergency USB-C Port & Keyhole Cover -->
  <circle cx="400" cy="870" r="10" fill="#15171d" stroke="#2c303c" stroke-width="1.5"/>
  <rect x="394" y="868" width="12" height="4" rx="1.5" fill="#090a0d"/>
</svg>
SVG;

file_put_contents($productsDir . '/homeiq-ai-face-lock.svg', $aiLockSvg);
file_put_contents($imagesDir . '/placeholder-lock.svg', $aiLockSvg);

// 2. Generic helper to generate variant lock SVGs for each category
function generateLockSvg(string $name, string $accentColor, string $subtitle, string $badge): string {
    return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 1000" width="100%" height="100%">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#141519"/>
      <stop offset="100%" stop-color="#0a0b0d"/>
    </linearGradient>
    <linearGradient id="body" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#242730"/>
      <stop offset="50%" stop-color="#333742"/>
      <stop offset="100%" stop-color="#1d2027"/>
    </linearGradient>
    <linearGradient id="glass" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#191b22"/>
      <stop offset="100%" stop-color="#0a0c10"/>
    </linearGradient>
    <filter id="shadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="20" stdDeviation="30" flood-color="#000" flood-opacity="0.7"/>
    </filter>
  </defs>

  <rect width="800" height="1000" fill="url(#bg)"/>
  <rect x="120" y="60" width="560" height="880" rx="10" fill="#171920" stroke="#262933" stroke-width="1.5"/>

  <!-- Lock Chassis -->
  <g filter="url(#shadow)">
    <rect x="300" y="110" width="200" height="780" rx="24" fill="#3f4452"/>
    <rect x="304" y="114" width="192" height="772" rx="22" fill="url(#body)"/>
  </g>

  <!-- Front Touch Screen -->
  <rect x="320" y="140" width="160" height="480" rx="16" fill="url(#glass)" stroke="#2b2e38" stroke-width="1"/>

  <!-- Status / Sensor Area -->
  <circle cx="400" cy="200" r="30" fill="#101217" stroke="{$accentColor}" stroke-width="2.5"/>
  <circle cx="400" cy="200" r="14" fill="{$accentColor}" opacity="0.8"/>
  <text x="400" y="248" font-family="'Manrope', sans-serif" font-size="10" font-weight="600" fill="{$accentColor}" text-anchor="middle" letter-spacing="2">{$badge}</text>

  <!-- Keypad Numbers -->
  <g fill="#e2e8f0" font-family="'Inter', sans-serif" font-weight="500" font-size="16" text-anchor="middle">
    <text x="350" y="310">1</text><text x="400" y="310">2</text><text x="450" y="310">3</text>
    <text x="350" y="355">4</text><text x="400" y="355">5</text><text x="450" y="355">6</text>
    <text x="350" y="400">7</text><text x="400" y="400">8</text><text x="450" y="400">9</text>
    <text x="350" y="445">✱</text><text x="400" y="445">0</text><text x="450" y="445">#</text>
  </g>

  <!-- Brand Wordmark -->
  <text x="400" y="530" font-family="'Manrope', sans-serif" font-weight="800" font-size="17" fill="#ffffff" text-anchor="middle" letter-spacing="4">HOMEIQ</text>
  <text x="400" y="548" font-family="'Inter', sans-serif" font-weight="600" font-size="8" fill="#94a3b8" text-anchor="middle" letter-spacing="2">{$subtitle}</text>

  <!-- Lever / Handle -->
  <g filter="url(#shadow)">
    <rect x="330" y="640" width="140" height="60" rx="10" fill="#242730" stroke="#4a5061" stroke-width="1.5"/>
    <circle cx="400" cy="670" r="14" fill="#121317" stroke="{$accentColor}" stroke-width="2"/>
    <rect x="230" y="650" width="130" height="40" rx="8" fill="url(#body)" stroke="#4a5061" stroke-width="1.5"/>
  </g>

  <!-- Lock Type Header in Preview -->
  <rect x="250" y="820" width="300" height="40" rx="6" fill="#12141a" stroke="#252934" stroke-width="1"/>
  <text x="400" y="845" font-family="'Manrope', sans-serif" font-size="12" font-weight="700" fill="#f8fafc" text-anchor="middle" letter-spacing="1">{$name}</text>
</svg>
SVG;
}

// Generate models for active smart locks
$models = [
    'fingerprint-lock' => ['HOMEIQ TOUCH PRO', '#10b981', 'BIOMETRIC SCAN', 'FINGERPRINT UNLOCK'],
    'palm-vein-lock' => ['HOMEIQ VEINGUARD', '#a855f7', 'PALM VEIN SCAN', 'INFRARED VEIN'],
    'video-door-lock' => ['HOMEIQ VISION 2K', '#06b6d4', 'VIDEO INTERCOM', '2K HD CAMERA'],
];

foreach ($models as $filename => [$name, $color, $subtitle, $badge]) {
    $svg = generateLockSvg($name, $color, $subtitle, $badge);
    file_put_contents($productsDir . '/' . $filename . '.svg', $svg);
}

// 3. Hero Banners Generator (4 independent banners)
function generateBannerSvg(string $headline, string $subhead, string $badge, string $accent): string {
    return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 650" width="100%" height="100%">
  <defs>
    <linearGradient id="bannerBg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0a0b0d"/>
      <stop offset="45%" stop-color="#12141a"/>
      <stop offset="100%" stop-color="#181a24"/>
    </linearGradient>
    <linearGradient id="accentG" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="{$accent}"/>
      <stop offset="100%" stop-color="#3b82f6"/>
    </linearGradient>
    <filter id="softGlow">
      <feGaussianBlur stdDeviation="50" result="blur"/>
    </filter>
  </defs>

  <rect width="1600" height="650" fill="url(#bannerBg)"/>
  <!-- Ambient light orb -->
  <circle cx="1250" cy="300" r="280" fill="{$accent}" opacity="0.12" filter="url(#softGlow)"/>

  <!-- Geometric architectural lines -->
  <line x1="0" y1="180" x2="1600" y2="180" stroke="#1f232e" stroke-width="1" stroke-dasharray="10,10"/>
  <line x1="0" y1="480" x2="1600" y2="480" stroke="#1f232e" stroke-width="1" stroke-dasharray="10,10"/>
  <line x1="880" y1="0" x2="880" y2="650" stroke="#1c202a" stroke-width="1.5"/>

  <!-- Left Content Column -->
  <g transform="translate(140, 150)">
    <!-- Badge -->
    <rect x="0" y="0" width="220" height="34" rx="17" fill="#1b1f29" stroke="{$accent}" stroke-width="1"/>
    <circle cx="18" cy="17" r="4" fill="{$accent}"/>
    <text x="32" y="22" font-family="'Inter', sans-serif" font-weight="600" font-size="11" fill="#f8fafc" letter-spacing="2">{$badge}</text>

    <!-- Main Headline -->
    <text x="0" y="100" font-family="'Manrope', sans-serif" font-weight="800" font-size="52" fill="#ffffff" letter-spacing="-1">
      {$headline}
    </text>

    <!-- Subheading -->
    <text x="0" y="150" font-family="'Inter', sans-serif" font-size="20" fill="#94a3b8" font-weight="400">
      {$subhead}
    </text>

    <!-- Features strip -->
    <g transform="translate(0, 200)" font-family="'Inter', sans-serif" font-size="13" font-weight="500" fill="#cbd5e1">
      <circle cx="6" cy="6" r="3" fill="{$accent}"/>
      <text x="18" y="10">Free Professional Installation</text>
      <circle cx="240" cy="6" r="3" fill="{$accent}"/>
      <text x="252" y="10">2-Year Official Warranty</text>
      <circle cx="450" cy="6" r="3" fill="{$accent}"/>
      <text x="462" y="10">24/7 Technical Support</text>
    </g>
  </g>

  <!-- Right Visual Lock Graphic Silhouette -->
  <g transform="translate(1080, 80) scale(0.55)">
    <rect x="100" y="40" width="300" height="920" rx="35" fill="#1a1c24" stroke="#353b49" stroke-width="3"/>
    <rect x="130" y="80" width="240" height="600" rx="20" fill="#0c0e12" stroke="#252933" stroke-width="1.5"/>
    <circle cx="250" cy="180" r="45" fill="#141720" stroke="{$accent}" stroke-width="3"/>
    <circle cx="250" cy="180" r="20" fill="{$accent}" opacity="0.9"/>
    <text x="250" y="400" font-family="'Inter', sans-serif" font-size="28" fill="#e2e8f0" text-anchor="middle" letter-spacing="12">1 2 3</text>
    <text x="250" y="460" font-family="'Inter', sans-serif" font-size="28" fill="#e2e8f0" text-anchor="middle" letter-spacing="12">4 5 6</text>
    <text x="250" y="520" font-family="'Inter', sans-serif" font-size="28" fill="#e2e8f0" text-anchor="middle" letter-spacing="12">7 8 9</text>
    <text x="250" y="640" font-family="'Manrope', sans-serif" font-weight="800" font-size="28" fill="#ffffff" text-anchor="middle" letter-spacing="6">HOMEIQ</text>
    <rect x="160" y="740" width="180" height="180" rx="20" fill="#181a22" stroke="#353b49" stroke-width="2"/>
  </g>
</svg>
SVG;
}

file_put_contents($bannersDir . '/hero-banner-1.svg', generateBannerSvg("Advanced Security.<br/>Elegant Design.", "Give your home the smart upgrade it deserves.", "STARTS FROM ₹9,499", "#00d2ff"));
file_put_contents($bannersDir . '/hero-banner-2.svg', generateBannerSvg("Built for Security.<br/>Designed for Peace.", "Discover premium smart locks at special prices.", "UP TO 40% OFF", "#f59e0b"));
file_put_contents($bannersDir . '/hero-banner-3.svg', generateBannerSvg("AI Face & Biometric<br/>Smart Door Locks", "Protect the people and things you love with 9-in-1 access.", "AI-POWERED ACCESS", "#10b981"));
file_put_contents($bannersDir . '/hero-banner-4.svg', generateBannerSvg("Smart Home Upgrade<br/>Starts Right Here.", "Exclusive limited-time deals on HOMEIQ certified locks.", "FREE EXPRESS SHIPPING", "#8b5cf6"));

echo "Vector assets generated successfully.\n";

