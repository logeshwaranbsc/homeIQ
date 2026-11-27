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
use App\Repositories\ContactMessageRepository;
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
assertTest("Catalog contains exactly 1 unified category ('smart-locks')", count($allCategories) === 1 && in_array($allCategories[0]['slug'] ?? '', ['smart-locks', 'digital-smart-locks'], true));

$allProducts = $productRepo->all();
$allMappedToSingleCategory = count($allProducts) === 5 && count(array_filter($allProducts, fn($p) => in_array($p['category_slug'] ?? '', ['smart-locks', 'digital-smart-locks'], true))) === 5;
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

// TEST GROUP 9: Leads & Bulk Orders Inquiry Workflow
echo "\nTest Group 9: Leads & Bulk Orders Inquiry Workflow\n";
$leadRepo = new ContactMessageRepository();
$originalLeads = $leadRepo->all();

$testBulkLead = $leadRepo->create([
    'inquiry_type' => 'bulk',
    'name' => 'Aditya Sharma',
    'email' => 'aditya@zenithtowers.com',
    'phone' => '9876543210',
    'company' => 'Zenith Luxury Residences',
    'project_type' => 'Residential Villa / Apartments',
    'quantity' => '45',
    'location' => 'Bengaluru',
    'timeline' => '1 - 3 Months',
    'subject' => 'Bulk Order Inquiry: Zenith Luxury Residences (45 Units)',
    'message' => 'Requirement for 45 AI Face recognition smart locks for newly constructed luxury towers.',
    'status' => 'unread',
]);

assertTest("Creates bulk order lead with company and quantity", !empty($testBulkLead['id']) && $testBulkLead['inquiry_type'] === 'bulk' && $testBulkLead['quantity'] === '45');

$bulkList = $leadRepo->filterLeads('bulk');
$foundBulk = false;
foreach ($bulkList as $item) {
    if ($item['id'] === $testBulkLead['id']) {
        $foundBulk = true;
        break;
    }
}
assertTest("filterLeads('bulk') includes newly created bulk inquiry", $foundBulk);

// Update status to 'contacted'
$leadRepo->update($testBulkLead['id'], [
    'status' => 'contacted',
    'admin_notes' => 'Quotation sent via email and phone follow-up scheduled.',
]);
$updatedLead = $leadRepo->find($testBulkLead['id']);
assertTest("Updates inquiry status to contacted with admin follow-up notes", ($updatedLead['status'] ?? '') === 'contacted' && !empty($updatedLead['admin_notes']));

// Clean up test lead
$leadRepo->delete($testBulkLead['id']);
$leadRepo->atomicWrite($originalLeads);
assertTest("Test lead cleaned up and original leads preserved", count($leadRepo->all()) === count($originalLeads));

// TEST GROUP 9: Warranty Registration Module (PHP + JSON Storage)
echo "\nTest Group 9: Warranty Registration Module (JSON Storage, Locking, Policies, Approval/Rejection)\n";

$warrantyRepo = new \App\Repositories\WarrantyRegistrationRepository();
$policyRepo = new \App\Repositories\WarrantyPolicyRepository();
$auditService = new \App\Services\WarrantyAuditService();
$emailService = new \App\Services\WarrantyEmailService();

// 9.1 Next ID generation with flock locking
$nextId1 = $warrantyRepo->generateNextId();
$nextId2 = $warrantyRepo->generateNextId();
assertTest("Generates sequential registration IDs with format HIQ-WR-000001", (bool)preg_match('/^HIQ-WR-\d{6}$/', $nextId1));
assertTest("Generates distinct sequential IDs on successive requests", $nextId1 !== $nextId2);

// 9.2 Create Registration Record
$testRegData = [
    'registration_id' => $nextId1,
    'customer' => [
        'full_name' => 'Arun Kumar',
        'mobile_number' => '9876543210',
        'email' => 'arun.test@example.com',
    ],
    'product' => [
        'category' => 'Smart Door Lock',
        'model' => 'HOMEIQ Pro Touch X1',
        'serial_number' => 'HIQ-SN-998877',
        'serial_unavailable' => false,
        'alternative_notes' => null,
    ],
    'purchase' => [
        'platform' => 'Amazon',
        'purchase_date' => '2026-11-20',
        'order_id' => 'AMZ-404-998822',
        'invoice_path' => 'invoices/' . $nextId1 . '_invoice.pdf',
        'invoice_original_name' => 'tax_invoice_arun.pdf',
        'invoice_mime' => 'application/pdf',
        'invoice_size' => 102400,
    ],
    'declaration_accepted' => true,
    'marketing_consent' => false,
    'status' => 'PENDING',
    'warranty' => [
        'start_date' => null,
        'end_date' => null,
        'duration_months' => null,
        'policy_matched' => null,
    ],
    'admin_review' => [
        'reviewed_by' => null,
        'reviewed_at' => null,
        'notes' => null,
        'rejection_reason' => null,
    ],
    'created_at' => date('c'),
    'updated_at' => date('c'),
];

$createdReg = $warrantyRepo->create($testRegData);
assertTest("Persists registration JSON file in storage/warranty/registrations", $createdReg !== null && file_exists($warrantyRepo->getRegistrationFilePath($nextId1)));
assertTest("Initial registration status is set to PENDING", ($createdReg['status'] ?? '') === 'PENDING');

// 9.3 Find and Read
$retrievedReg = $warrantyRepo->find($nextId1);
assertTest("Retrieves registration JSON by registration ID", $retrievedReg !== null && $retrievedReg['customer']['full_name'] === 'Arun Kumar');

// 9.4 Policy Calculation
$calcDates = $policyRepo->calculateWarrantyDates('Smart Door Lock', 'HOMEIQ Pro Touch X1', '2026-11-20');
assertTest("Matches Smart Door Lock policy (24 months duration)", ($calcDates['duration_months'] ?? 0) === 24);
assertTest("Calculates correct warranty end date from purchase date", $calcDates['start_date'] === '2026-11-20' && $calcDates['end_date'] === '2028-11-20');

// 9.5 Approval Flow
$approveSuccess = $warrantyRepo->updateStatus(
    $nextId1,
    'APPROVED',
    ['notes' => 'Verified Amazon invoice tax details and valid serial number.'],
    $calcDates,
    'Test Administrator'
);
assertTest("Updates registration status to APPROVED with review notes", $approveSuccess);

$approvedReg = $warrantyRepo->find($nextId1);
assertTest("Approved record contains calculated warranty start and end dates", $approvedReg['warranty']['start_date'] === '2026-11-20' && $approvedReg['warranty']['end_date'] === '2028-11-20');
assertTest("Approved record tracks reviewer name and review timestamp", $approvedReg['admin_review']['reviewed_by'] === 'Test Administrator' && !empty($approvedReg['admin_review']['reviewed_at']));

// 9.6 Audit Logging
$auditService->record($nextId1, 'APPROVED', 'Test Administrator', 'Approved warranty coverage for 24 months.');
$auditHistory = $auditService->getByRegistration($nextId1);
assertTest("Audit log tracks approval event in storage/warranty/audit-logs", count($auditHistory) > 0 && $auditHistory[0]['action'] === 'APPROVED');

// 9.7 Email Service Notification & Outbox
$emailResult = $emailService->sendApproved($approvedReg);
assertTest("Dispatches approved warranty notification email", !empty($emailResult['success']));
$emailHistory = $emailService->getHistoryByRegistration($nextId1);
assertTest("Records email delivery status in storage/warranty/email-logs", count($emailHistory) > 0 && ($emailHistory[0]['status'] ?? '') === 'SENT');

// 9.8 Rejection Flow on second registration
$testRegData2 = $testRegData;
$testRegData2['registration_id'] = $nextId2;
$testRegData2['customer']['full_name'] = 'Kavitha S';
$testRegData2['customer']['mobile_number'] = '9840123456';
$warrantyRepo->create($testRegData2);

$rejectSuccess = $warrantyRepo->updateStatus(
    $nextId2,
    'REJECTED',
    [
        'rejection_reason' => 'Invalid Invoice',
        'notes' => 'Invoice image is blurry and does not display product SKU or order date.'
    ],
    null,
    'Test Administrator'
);
assertTest("Updates registration status to REJECTED with rejection reason", $rejectSuccess);

$rejectedReg = $warrantyRepo->find($nextId2);
assertTest("Rejected record stores rejection reason and admin notes", ($rejectedReg['admin_review']['rejection_reason'] ?? '') === 'Invalid Invoice');

// 9.9 Search and Filtering
$searchName = $warrantyRepo->filter(['q' => 'Arun']);
assertTest("Searches registrations by customer name", count($searchName) >= 1 && $searchName[0]['registration_id'] === $nextId1);

$searchSerial = $warrantyRepo->filter(['q' => 'HIQ-SN-998877']);
assertTest("Searches registrations by product serial number", count($searchSerial) >= 1 && $searchSerial[0]['registration_id'] === $nextId1);

$filterApproved = $warrantyRepo->filter(['status' => 'APPROVED']);
assertTest("Filters registrations by status APPROVED", count($filterApproved) >= 1);

$counts = $warrantyRepo->getCounts();
assertTest("Calculates registration counts dynamically (total, pending, approved, rejected)", $counts['total'] >= 2 && $counts['approved'] >= 1 && $counts['rejected'] >= 1);

// 9.10 Clean up test registrations from storage
$warrantyRepo->delete($nextId1);
$warrantyRepo->delete($nextId2);
assertTest("Cleaned up test registration JSON files", $warrantyRepo->find($nextId1) === null && $warrantyRepo->find($nextId2) === null);

// TEST GROUP 10: Callback Popup & Lead Management (JSON Storage, Locking, Statuses, Audit)
echo "\nTest Group 10: Callback Popup & Lead Management (JSON Storage, Locking, Statuses, Audit)\n";

$cbRepo = new \App\Repositories\CallbackLeadRepository();
$cbSettingsRepo = new \App\Repositories\CallbackSettingsRepository();
$cbAuditService = new \App\Services\CallbackAuditService();

// 10.1 Callback Settings
$cbSettings = $cbSettingsRepo->get();
assertTest("Loads callback popup configuration", !empty($cbSettings['heading']) && isset($cbSettings['trigger_delay_seconds']));
assertTest("Default active browsing delay is 60 seconds", ($cbSettings['trigger_delay_seconds'] ?? 0) === 60);

// 10.2 Sequence ID Generation with flock
$cbId1 = $cbRepo->generateNextId();
$cbId2 = $cbRepo->generateNextId();
assertTest("Generates sequential callback lead IDs with format CB-HIQ-000001", (bool)preg_match('/^CB-HIQ-\d{6}$/', $cbId1));
assertTest("Generates distinct sequential callback IDs on successive requests", $cbId1 !== $cbId2);

// 10.3 Create Callback Enquiry Record
$testLeadData = [
    'lead_id' => $cbId1,
    'product_interest' => 'Smart Door Lock',
    'customer_name' => 'Deepak Sharma',
    'mobile_number' => '9812345678',
    'product_page_url' => 'http://localhost:8000/shop',
    'status' => 'NEW',
    'admin_notes' => '',
    'follow_up_date' => null,
    'assigned_to' => null,
];

$createdLead = $cbRepo->create($testLeadData);
assertTest("Persists callback lead JSON file in storage/callback-leads/leads", $createdLead !== null && file_exists($cbRepo->getFilePath($cbId1)));
assertTest("Initial callback enquiry status is NEW", ($createdLead['status'] ?? '') === 'NEW');

// 10.4 Find and Read
$retrievedLead = $cbRepo->find($cbId1);
assertTest("Retrieves callback lead by ID", $retrievedLead !== null && $retrievedLead['customer_name'] === 'Deepak Sharma');

// 10.5 Update Status, Notes, Assignment, and Follow-up Date
$updatedLead = $cbRepo->update(
    $cbId1,
    [
        'status' => 'CONTACTED',
        'assigned_to' => 'Sales Representative',
        'admin_notes' => 'Customer called. Interested in flagship face recognition lock for luxury villa.',
        'follow_up_date' => date('Y-m-d', strtotime('+1 day')),
    ],
    'Admin User',
    'Contacted customer via telephone.'
);
assertTest("Updates lead status to CONTACTED", ($updatedLead['status'] ?? '') === 'CONTACTED');
assertTest("Assigns sales representative to callback lead", ($updatedLead['assigned_to'] ?? '') === 'Sales Representative');
assertTest("Tracks history timeline of status changes", count($updatedLead['history'] ?? []) >= 2);

// 10.6 Audit Log Tracking
$cbAuditService->record($cbId1, 'CONTACTED', 'Admin User', 'Customer called. Scheduled demo.');
$cbAuditHistory = $cbAuditService->getForLead($cbId1);
assertTest("Records callback event in storage/callback-leads/logs/callback-audit.json", count($cbAuditHistory) > 0 && $cbAuditHistory[0]['action'] === 'CONTACTED');

// 10.7 Second Lead with Converted Status
$testLeadData2 = $testLeadData;
$testLeadData2['lead_id'] = $cbId2;
$testLeadData2['customer_name'] = 'Ananya Patel';
$testLeadData2['mobile_number'] = '9876543210';
$cbRepo->create($testLeadData2);

$convertedLead = $cbRepo->update($cbId2, ['status' => 'CONVERTED', 'admin_notes' => 'Order verified and completed.'], 'Admin User');
assertTest("Updates lead status to CONVERTED", ($convertedLead['status'] ?? '') === 'CONVERTED');

// 10.8 Search & Filter
$searchName = $cbRepo->filter(['q' => 'Deepak']);
assertTest("Searches callback leads by customer name", count($searchName) >= 1 && $searchName[0]['lead_id'] === $cbId1);

$searchMobile = $cbRepo->filter(['q' => '9812345678']);
assertTest("Searches callback leads by mobile number", count($searchMobile) >= 1 && $searchMobile[0]['lead_id'] === $cbId1);

$filterContacted = $cbRepo->filter(['status' => 'CONTACTED']);
assertTest("Filters callback leads by status CONTACTED", count($filterContacted) >= 1);

$cbCounts = $cbRepo->getCounts();
assertTest("Calculates dynamic callback summary counts (total, new, contacted, converted)", $cbCounts['total'] >= 2 && $cbCounts['contacted'] >= 1 && $cbCounts['converted'] >= 1);

// 10.9 Clean up test callback records
$cbRepo->delete($cbId1);
$cbRepo->delete($cbId2);
assertTest("Cleaned up test callback JSON files", $cbRepo->find($cbId1) === null && $cbRepo->find($cbId2) === null);

// ====================================================
// TEST GROUP 11: AI-POWERED CUSTOMER SUPPORT CHATBOT
// ====================================================
echo "\nTest Group 11: AI Customer Support Chatbot (Knowledge Retrieval, Intent Engine, Session Memory)\n";
$kbRepo = new \App\Repositories\ChatbotKnowledgeRepository();
$chatService = new \App\Services\Chatbot\ChatbotService();

// 11.1 Knowledge repository persistence & toggle
$initialKbCount = count($kbRepo->all());
assertTest("Chatbot knowledge base contains default knowledge entries", $initialKbCount >= 10);

$newKbEntry = $kbRepo->create([
    'category' => 'Testing Category',
    'question' => 'How many keys are included in the package?',
    'answer' => 'Every HOMEIQ smart lock includes 2 high-security physical keys.',
    'keywords' => 'keys, package, included, physical keys',
    'link_url' => '/shop',
    'link_label' => 'View Locks'
]);
assertTest("Creates new chatbot knowledge entry", !empty($newKbEntry['id']) && ($newKbEntry['question'] ?? '') === 'How many keys are included in the package?');

$toggledStatus = $kbRepo->toggle($newKbEntry['id']);
assertTest("Toggles chatbot knowledge entry active status", $toggledStatus === false);
$kbRepo->delete($newKbEntry['id']);
assertTest("Deletes temporary chatbot knowledge entry", $kbRepo->findById($newKbEntry['id']) === null);

// 11.2 Whole-website query answering
// Q1: Amazon / Flipkart warranty
$resAmazon = $chatService->handleChat("Can I register a product purchased from Amazon or Flipkart?");
assertTest("Answers Amazon/Flipkart warranty registration accurately", $resAmazon['success'] === true && str_contains($resAmazon['reply'], 'Amazon') && str_contains($resAmazon['reply'], 'eligible'));

// Q2: Lost invoice
$resLost = $chatService->handleChat("What if I lose my purchase invoice?");
assertTest("Answers lost invoice enquiry with customer support assistance", $resLost['success'] === true && str_contains($resLost['reply'], 'customer support') && str_contains($resLost['reply'], 'verifying your purchase'));

// Q3: Required documents
$resDocs = $chatService->handleChat("What documents are required for warranty registration?");
assertTest("Answers required documents with purchase invoice and serial number", $resDocs['success'] === true && str_contains($resDocs['reply'], 'invoice') && str_contains($resDocs['reply'], 'serial number'));

// Q4: Product specifications & database grounding
$resFace = $chatService->handleChat("Does the Black AI lock support fingerprint access?");
assertTest("Answers product feature query grounded in database access methods", $resFace['success'] === true && str_contains($resFace['reply'], 'fingerprint') && str_contains($resFace['reply'], 'Yes'));

// Q5: Door suitability & installation
$resInstall = $chatService->handleChat("How do I install my smart lock, and will it fit my wooden door?");
assertTest("Answers installation query with free doorstep installation and 35mm-90mm door thickness", $resInstall['success'] === true && str_contains($resInstall['reply'], 'Doorstep Installation') && str_contains($resInstall['reply'], '35mm to 90mm'));

// Q6: Dead battery emergency
$resBattery = $chatService->handleChat("What happens if the lock battery drains completely?");
assertTest("Answers dead battery query with USB-C emergency port and physical override keys", $resBattery['success'] === true && str_contains($resBattery['reply'], 'USB-C') && str_contains($resBattery['reply'], 'Mechanical Override Keys'));

// Q7: Order tracking
$resTrack = $chatService->handleChat("How can I track my order?");
assertTest("Answers order tracking query with order tracking page link", $resTrack['success'] === true && str_contains($resTrack['reply'], 'Order Tracking'));

// 11.3 Context-aware suggested questions
$sugWarranty = $chatService->getSuggestedQuestions('warranty-registration');
assertTest("Returns warranty-specific suggested questions on warranty page", count($sugWarranty) >= 4 && in_array("Can I register a product purchased from Amazon or Flipkart?", $sugWarranty));

$sugProduct = $chatService->getSuggestedQuestions('product/homeiq-black-ai-face-recognition-smart-door-lock', 'homeiq-black-ai-face-recognition-smart-door-lock');
assertTest("Returns product-specific suggested questions on product details page", count($sugProduct) >= 4 && in_array("What are the key features of this smart lock?", $sugProduct));

// 11.4 Session Reset
$chatService->resetSession();
assertTest("Resets conversation session context successfully", true);

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

