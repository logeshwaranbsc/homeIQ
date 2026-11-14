<?php
declare(strict_types=1);

/**
 * HOMEIQ Automated Unit & Business Logic Test Suite
 * Run via CLI: php tests/run_tests.php
 */

$baseDir = dirname(__DIR__);
require_once $baseDir . '/app/Core/App.php';

use App\Core\App;
use App\Repositories\JsonRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CouponRepository;
use App\Repositories\OrderRepository;
use App\Repositories\VideoTestimonialRepository;
use App\Repositories\BannerRepository;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\AuthService;
use App\Core\Session;

App::init($baseDir);

$totalTests = 0;
$passedTests = 0;

function assertTest(string $description, bool $condition, string $detail = ''): void {
    global $totalTests, $passedTests;
    $totalTests++;
    if ($condition) {
        $passedTests++;
        echo "  [PASS] {$description}\n";
    } else {
        echo "  [FAIL] {$description}" . ($detail ? " — {$detail}" : "") . "\n";
    }
}

echo "====================================================\n";
echo "   HOMEIQ DIGITAL LOCKS — BUSINESS RULE TEST SUITE   \n";
echo "====================================================\n\n";

// TEST GROUP 1: JSON Persistence & Atomic Writes
echo "Test Group 1: JSON Repository Persistence & Atomic Operations\n";
$testRepo = new class('test_data.json', 'tst_') extends JsonRepository {};
$testRepo->atomicWrite([]);

$item1 = $testRepo->create(['name' => 'Biometric Smart Lock Test', 'sku' => 'TEST-001']);
assertTest("Creates record with unique ID and timestamp", !empty($item1['id']) && !empty($item1['created_at']));

$found = $testRepo->find($item1['id']);
assertTest("Reads record by ID", $found !== null && $found['name'] === 'Biometric Smart Lock Test');

$updated = $testRepo->update($item1['id'], ['name' => 'Updated Smart Lock']);
assertTest("Updates record without altering original ID or created_at", $updated !== null && $updated['name'] === 'Updated Smart Lock' && $updated['id'] === $item1['id']);

$deleted = $testRepo->delete($item1['id']);
assertTest("Deletes record by ID", $deleted && $testRepo->find($item1['id']) === null);

// Clean up test file
@unlink(App::getBasePath() . '/storage/test_data.json');

// TEST GROUP 2: Product Catalog & Flagship Product Verification
echo "\nTest Group 2: Digital Lock Catalog & Flagship Verification\n";
$productRepo = new ProductRepository();
$flagship = $productRepo->findBySlug('homeiq-black-ai-face-recognition-smart-door-lock');
assertTest("Flagship product 'HOMEIQ Black AI Face Recognition Smart Door Lock' exists", $flagship !== null);
assertTest("Flagship MRP is ₹24,999 and Selling Price is ₹19,999", (float)$flagship['price'] === 24999.0 && (float)$flagship['sale_price'] === 19999.0);
assertTest("Flagship includes 9-in-1 access methods", in_array('AI Face Recognition', $flagship['access_methods'] ?? [], true));

$categoryRepo = new CategoryRepository();
$allCategories = $categoryRepo->all();
assertTest("Catalog contains exactly 1 unified category ('digital-smart-locks')", count($allCategories) === 1 && ($allCategories[0]['slug'] ?? '') === 'digital-smart-locks');

$allProducts = $productRepo->all();
$allMappedToSingleCategory = count($allProducts) === 5 && count(array_filter($allProducts, fn($p) => ($p['category_slug'] ?? '') === 'digital-smart-locks')) === 5;
assertTest("Catalog maintains exactly 5 products mapped to the 1 category", $allMappedToSingleCategory);

// TEST GROUP 3: Cart Calculation & Server-Side Price Verification
echo "\nTest Group 3: Cart Pricing, Quantity & Shipping Rules\n";
Session::destroy();
Session::start();

$cartService = new CartService();
$cartService->clear();

$addResult = $cartService->addItem($flagship['id'], 1);
assertTest("Adds product to shopping cart", $addResult['success']);

$cart = $cartService->getCartDetails(false);
assertTest("Cart subtotal calculated from server sale price (₹19,999)", $cart['subtotal'] === 19999.0);
assertTest("Orders ₹5,000+ qualify for Free Express Shipping", $cart['shipping'] === 0.0);
assertTest("Grand total equals verified subtotal", $cart['grand_total'] === 19999.0);

// TEST GROUP 4: Coupon Discount Application & Validation
echo "\nTest Group 4: Promotional Coupon Calculations\n";
$couponRepo = new CouponRepository();
$coupon = $couponRepo->findByCode('WELCOME10');
assertTest("WELCOME10 coupon exists in database", $coupon !== null);

$couponApply = $cartService->applyCoupon('WELCOME10');
assertTest("Applies WELCOME10 coupon to eligible cart", $couponApply['success']);

$cartWithDiscount = $cartService->getCartDetails(true);
// 10% of 19,999 is 1,999.9 capped at max discount 2,000
assertTest("Calculates 10% discount correctly", (float)$cartWithDiscount['discount'] === 1999.9);
assertTest("Grand total reflects discounted subtotal", round($cartWithDiscount['grand_total'], 2) === round(19999.0 - 1999.9, 2));

// Test invalid coupon
$invalidCoupon = $cartService->applyCoupon('FAKE_CODE_123');
assertTest("Rejects invalid coupon code", !$invalidCoupon['success']);

// TEST GROUP 5: Order Creation, Inventory Deduction & Restock
echo "\nTest Group 5: Order Creation & Inventory Movement Trail\n";
$orderService = new OrderService();
$stockBefore = (int)($productRepo->find($flagship['id'])['stock'] ?? 0);

$customerData = [
    'name' => 'Test Purchaser',
    'email' => 'purchaser@example.com',
    'phone' => '9876543210',
    'address' => '402 High-Tech Residency',
    'city' => 'Bengaluru',
    'state' => 'Karnataka',
    'pincode' => '560100',
];

$orderResult = $orderService->createOrder($customerData, $cartWithDiscount, 'cod');
assertTest("Creates order with unique HIQ order number", $orderResult['success'] && str_starts_with($orderResult['order']['order_number'], 'HIQ-'));

$stockAfter = (int)($productRepo->find($flagship['id'])['stock'] ?? 0);
assertTest("Atomically decrements product stock by purchased quantity", $stockAfter === $stockBefore - 1);

// Test Cancellation & Stock Restoral
$orderId = $orderResult['order']['id'];
$cancelResult = $orderService->updateStatus($orderId, 'Cancelled', 'Test cancellation', 'Unit Test');
assertTest("Cancels order successfully", $cancelResult['success']);

$stockRestored = (int)($productRepo->find($flagship['id'])['stock'] ?? 0);
assertTest("Restores inventory stock upon order cancellation", $stockRestored === $stockBefore);

// Clean up test order
$orderRepo = new OrderRepository();
$orderRepo->delete($orderId);

// TEST GROUP 6: Authentication & Password Security
echo "\nTest Group 6: Authentication Security & Hashing\n";
$authService = new AuthService();
$hashed = password_hash('SecureLock2026!', PASSWORD_DEFAULT);
assertTest("Generates secure password hash", password_verify('SecureLock2026!', $hashed));
assertTest("Rejects invalid password attempt", !password_verify('WrongPassword', $hashed));

$adminLogin = $authService->loginAdmin('admin', 'Admin@HOMEIQ2026!', '127.0.0.1');
assertTest("Authenticates valid administrator login", $adminLogin['success']);

$adminBadLogin = $authService->loginAdmin('admin', 'WrongPass', '127.0.0.1');
assertTest("Denies invalid administrator password", !$adminBadLogin['success']);

// TEST GROUP 7: Video Testimonials 10-Limit Enforcement & Ordering
echo "\nTest Group 7: Video Testimonials Strict 10-Item Limit & Ordering\n";
$videoRepo = new VideoTestimonialRepository();
// Ensure clean state
$originalData = $videoRepo->all();
$videoRepo->atomicWrite([]);

assertTest("Repository canAdd() is true when empty", $videoRepo->canAdd());
assertTest("Remaining slots is 10 when empty", $videoRepo->getRemainingSlots() === 10);

$createdList = [];
for ($i = 1; $i <= 10; $i++) {
    $createdList[] = $videoRepo->create([
        'customer_name' => "User {$i}",
        'display_order' => ($i === 1) ? 2 : (($i === 2) ? 1 : $i),
        'is_active' => ($i !== 3), // 9 active, 1 inactive
    ]);
}

assertTest("Count equals 10 after 10 creations", $videoRepo->count() === 10);
assertTest("canAdd() is false after reaching 10 items limit", !$videoRepo->canAdd());
assertTest("Remaining slots is 0 when 10 items exist", $videoRepo->getRemainingSlots() === 0);

$active = $videoRepo->getActive();
assertTest("getActive() excludes inactive item and returns 9 active items", count($active) === 9);
assertTest("getActive() sorts correctly by display_order", $active[0]['customer_name'] === 'User 2' && $active[1]['customer_name'] === 'User 1');

$videoRepo->delete($createdList[0]['id']);
assertTest("canAdd() returns true after deleting an item (slot freed)", $videoRepo->canAdd());
assertTest("Count is 9 after deletion", $videoRepo->count() === 9);

// Restore original empty state as user requested no pre-uploaded dummy data
$videoRepo->atomicWrite($originalData);
assertTest("Video testimonials storage reset to pristine empty state", $videoRepo->count() === count($originalData));

// TEST GROUP 8: Hero Banners & Mobile Screen Banner Art Direction
echo "\nTest Group 8: Hero Banners & Mobile Screen Banner Art Direction\n";
$bannerRepo = new BannerRepository();
$originalBanners = $bannerRepo->all();

$testBanner = $bannerRepo->create([
    'headline' => 'Test Next-Gen AI Lock',
    'subheading' => 'Engineered for Luxury Homes',
    'image' => 'banners/desktop_test_1920x600.png',
    'mobile_image' => 'banners/mobile_test_750x1000.png',
    'display_order' => 99,
    'is_active' => true,
]);

assertTest("Banner created with both desktop image and separate mobile_image", !empty($testBanner['id']) && $testBanner['image'] === 'banners/desktop_test_1920x600.png' && $testBanner['mobile_image'] === 'banners/mobile_test_750x1000.png');

$retrievedBanner = $bannerRepo->find($testBanner['id']);
assertTest("Banner repository accurately stores and retrieves mobile_image", ($retrievedBanner['mobile_image'] ?? '') === 'banners/mobile_test_750x1000.png');

// Update to replace mobile image
$bannerRepo->update($testBanner['id'], ['mobile_image' => 'banners/mobile_updated_1080x1350.png']);
$updatedBanner = $bannerRepo->find($testBanner['id']);
assertTest("Banner update updates dedicated mobile_image independently", ($updatedBanner['mobile_image'] ?? '') === 'banners/mobile_updated_1080x1350.png');

// Delete test banner to leave repository in original state
$bannerRepo->delete($testBanner['id']);
$bannerRepo->atomicWrite($originalBanners);
assertTest("Test banner removed and original banners preserved", count($bannerRepo->all()) === count($originalBanners));

// SUMMARY
echo "\n====================================================\n";
echo "   TEST RUN COMPLETE: {$passedTests} / {$totalTests} PASSED\n";
echo "====================================================\n";

if ($passedTests === $totalTests) {
    echo "All core business rules and storage tests PASSED successfully!\n";
    exit(0);
} else {
    echo "Some tests failed. Please review the output above.\n";
    exit(1);
}

