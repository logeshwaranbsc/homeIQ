<?php
declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Core\Request;
use App\Core\View;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\BannerRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\JsonRepository;

class HomeController
{
    private ProductRepository $productRepo;
    private CategoryRepository $categoryRepo;
    private BannerRepository $bannerRepo;
    private ReviewRepository $reviewRepo;
    private SettingsRepository $settingsRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->categoryRepo = new CategoryRepository();
        $this->bannerRepo = new BannerRepository();
        $this->reviewRepo = new ReviewRepository();
        $this->settingsRepo = new SettingsRepository();
    }

    public function index(Request $request): void
    {
        $banners = $this->bannerRepo->getActive();
        $categories = $this->categoryRepo->getActive();
        $featuredProducts = $this->productRepo->getFeatured(8);
        $settings = $this->settingsRepo->all();

        // Get reviews for verified display
        $recentReviews = array_slice($this->reviewRepo->filter(fn($r) => !empty($r['is_approved'])), 0, 6);

        echo View::render('storefront/home', [
            'title' => 'HOMEIQ | Advanced Smart Locks & Keyless Security Hardware',
            'banners' => $banners,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'recentReviews' => $recentReviews,
            'settings' => $settings,
        ]);
    }

    public function subscribeNewsletter(Request $request): void
    {
        $email = mb_strtolower(trim((string)$request->post('email', '')));
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Please enter a valid email address.');
            redirect('/');
        }

        $repo = new class('newsletter_subscribers.json', 'sub_') extends JsonRepository {};
        $existing = $repo->findBy('email', $email);

        if (!$existing) {
            $repo->create([
                'email' => $email,
                'ip' => $request->ip(),
                'subscribed_at' => date('Y-m-d H:i:s'),
            ]);
        }

        flash('success', 'Thank you for subscribing to HOMEIQ security updates and exclusive offers.');
        redirect('/');
    }
}

