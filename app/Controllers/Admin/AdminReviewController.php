<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\ReviewRepository;
use App\Repositories\ProductRepository;
use App\Repositories\AuditLogRepository;
use App\Helpers\FileUploader;

class AdminReviewController
{
    private ReviewRepository $reviewRepo;
    private ProductRepository $productRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->reviewRepo = new ReviewRepository();
        $this->productRepo = new ProductRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function index(): void
    {
        $reviews = $this->reviewRepo->all();
        usort($reviews, fn($a, $b) => strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now'));

        echo View::render('admin/reviews/index', [
            'title' => 'Customer Reviews & Testimonials | HOMEIQ Admin',
            'reviews' => $reviews,
        ], 'admin');
    }

    public function create(): void
    {
        $products = $this->productRepo->getActive();

        echo View::render('admin/reviews/form', [
            'title' => 'Add Customer Review / Testimonial | HOMEIQ Admin',
            'review' => null,
            'products' => $products,
        ], 'admin');
    }

    public function store(Request $request): void
    {
        $reviewerName = trim((string)$request->post('reviewer_name', ''));
        $title = trim((string)$request->post('title', ''));
        $comment = trim((string)$request->post('comment', ''));
        $rating = max(1, min(5, (int)$request->post('rating', 5)));
        $reviewType = $request->post('review_type', 'site') === 'product' ? 'product' : 'site';
        $productId = trim((string)$request->post('product_id', ''));
        $verifiedPurchase = !empty($request->post('verified_purchase'));
        $isApproved = !empty($request->post('is_approved'));

        if (empty($reviewerName) || empty($comment)) {
            flash('error', 'Reviewer name and review comment are required.');
            redirect('admin/reviews/create');
        }

        $productName = 'HOMEIQ Smart Locks';
        if (!empty($productId)) {
            $prod = $this->productRepo->find($productId);
            if ($prod) {
                $productName = $prod['name'];
            }
        }

        // Avatar upload handling
        $avatar = null;
        $avatarFile = $request->file('avatar');
        if ($avatarFile && !empty($avatarFile['tmp_name'])) {
            $upload = FileUploader::upload($avatarFile, 'reviews');
            if ($upload['success']) {
                $avatar = $upload['filename'];
            } else {
                flash('error', $upload['error'] ?? 'Avatar upload failed.');
                redirect('admin/reviews/create');
            }
        }

        $data = [
            'reviewer_name' => $reviewerName,
            'title' => $title,
            'comment' => $comment,
            'rating' => $rating,
            'review_type' => $reviewType,
            'is_site_review' => ($reviewType === 'site'),
            'product_id' => $productId,
            'product_name' => $productName,
            'verified_purchase' => $verifiedPurchase,
            'is_approved' => $isApproved,
        ];

        if (!empty($avatar)) {
            $data['avatar'] = $avatar;
        }

        $created = $this->reviewRepo->create($data);

        $admin = auth_admin();
        $this->auditRepo->record('review_created', $admin['username'] ?? 'Admin', "Created review #{$created['id']} for {$reviewerName}");

        flash('success', 'Customer review / testimonial created successfully.');
        redirect('admin/reviews');
    }

    public function edit(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $review = $this->reviewRepo->find($id);

        if (!$review) {
            flash('error', 'Review not found.');
            redirect('admin/reviews');
        }

        $products = $this->productRepo->getActive();

        echo View::render('admin/reviews/form', [
            'title' => 'Edit Review: ' . ($review['reviewer_name'] ?? 'Testimonial') . ' | HOMEIQ Admin',
            'review' => $review,
            'products' => $products,
        ], 'admin');
    }

    public function update(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $review = $this->reviewRepo->find($id);

        if (!$review) {
            flash('error', 'Review not found.');
            redirect('admin/reviews');
        }

        $reviewerName = trim((string)$request->post('reviewer_name', ''));
        $title = trim((string)$request->post('title', ''));
        $comment = trim((string)$request->post('comment', ''));
        $rating = max(1, min(5, (int)$request->post('rating', 5)));
        $reviewType = $request->post('review_type', 'site') === 'product' ? 'product' : 'site';
        $productId = trim((string)$request->post('product_id', ''));
        $verifiedPurchase = !empty($request->post('verified_purchase'));
        $isApproved = !empty($request->post('is_approved'));

        if (empty($reviewerName) || empty($comment)) {
            flash('error', 'Reviewer name and review comment are required.');
            redirect('admin/reviews/edit/' . $id);
        }

        $productName = $review['product_name'] ?? 'HOMEIQ Smart Locks';
        if (!empty($productId)) {
            $prod = $this->productRepo->find($productId);
            if ($prod) {
                $productName = $prod['name'];
            }
        } elseif ($reviewType === 'site') {
            $productName = 'HOMEIQ Smart Locks';
        }

        $avatar = $review['avatar'] ?? null;
        if (!empty($request->post('remove_avatar'))) {
            $avatar = null;
        }

        $avatarFile = $request->file('avatar');
        if ($avatarFile && !empty($avatarFile['tmp_name'])) {
            $upload = FileUploader::upload($avatarFile, 'reviews');
            if ($upload['success']) {
                $avatar = $upload['filename'];
            } else {
                flash('error', $upload['error'] ?? 'Avatar upload failed.');
                redirect('admin/reviews/edit/' . $id);
            }
        }

        $updateData = [
            'reviewer_name' => $reviewerName,
            'title' => $title,
            'comment' => $comment,
            'rating' => $rating,
            'review_type' => $reviewType,
            'is_site_review' => ($reviewType === 'site'),
            'product_id' => $productId,
            'product_name' => $productName,
            'verified_purchase' => $verifiedPurchase,
            'is_approved' => $isApproved,
            'avatar' => $avatar,
        ];

        $this->reviewRepo->update($id, $updateData);

        $admin = auth_admin();
        $this->auditRepo->record('review_updated', $admin['username'] ?? 'Admin', "Updated review #{$id} by {$reviewerName}");

        flash('success', 'Customer review / testimonial updated successfully.');
        redirect('admin/reviews');
    }

    public function delete(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $review = $this->reviewRepo->find($id);

        if ($review) {
            $this->reviewRepo->delete($id);
            $admin = auth_admin();
            $this->auditRepo->record('review_deleted', $admin['username'] ?? 'Admin', "Deleted review #{$id}");
            flash('success', 'Review deleted successfully.');
        }

        redirect('admin/reviews');
    }

    public function approve(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $review = $this->reviewRepo->find($id);

        if ($review) {
            $this->reviewRepo->update($id, ['is_approved' => true]);
            $admin = auth_admin();
            $this->auditRepo->record('review_approved', $admin['username'] ?? 'Admin', "Approved review #{$id} by {$review['reviewer_name']}");
            flash('success', 'Review approved and published.');
        }

        redirect('admin/reviews');
    }

    public function reject(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $review = $this->reviewRepo->find($id);

        if ($review) {
            $this->reviewRepo->delete($id);
            $admin = auth_admin();
            $this->auditRepo->record('review_rejected', $admin['username'] ?? 'Admin', "Rejected/deleted review #{$id}");
            flash('success', 'Review rejected and removed.');
        }

        redirect('admin/reviews');
    }

    public function toggleSiteReview(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $review = $this->reviewRepo->find($id);

        if ($review) {
            $isCurrentlySite = !empty($review['is_site_review']) || ($review['review_type'] ?? '') === 'site';
            $newIsSite = !$isCurrentlySite;

            $this->reviewRepo->update($id, [
                'is_site_review' => $newIsSite,
                'review_type' => $newIsSite ? 'site' : 'product',
            ]);

            $admin = auth_admin();
            $label = $newIsSite ? 'Site Review (Homepage)' : 'Product Review Only';
            $this->auditRepo->record('review_type_changed', $admin['username'] ?? 'Admin', "Changed review #{$id} type to {$label}");
            flash('success', "Review updated to {$label}.");
        }

        redirect('admin/reviews');
    }
}
