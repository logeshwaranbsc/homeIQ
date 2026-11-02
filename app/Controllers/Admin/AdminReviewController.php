<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\ReviewRepository;
use App\Repositories\AuditLogRepository;

class AdminReviewController
{
    private ReviewRepository $reviewRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->reviewRepo = new ReviewRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function index(): void
    {
        $reviews = $this->reviewRepo->all();
        usort($reviews, fn($a, $b) => strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now'));

        echo View::render('admin/reviews/index', [
            'title' => 'Customer Reviews Moderation | HOMEIQ Admin',
            'reviews' => $reviews,
        ], 'admin');
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
}

