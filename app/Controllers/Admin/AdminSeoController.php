<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Core\App;
use App\Repositories\SettingsRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;

class AdminSeoController
{
    private SettingsRepository $settingsRepo;
    private ProductRepository $productRepo;
    private CategoryRepository $categoryRepo;

    public function __construct()
    {
        $this->settingsRepo = new SettingsRepository();
        $this->productRepo = new ProductRepository();
        $this->categoryRepo = new CategoryRepository();
    }

    public function index(): void
    {
        $settings = $this->settingsRepo->all();

        echo View::render('admin/seo/index', [
            'title' => 'SEO & Sitemap Management | HOMEIQ Admin',
            'settings' => $settings,
        ], 'admin');
    }

    public function update(Request $request): void
    {
        $metaTitle = trim((string)$request->post('meta_title', ''));
        $metaDescription = trim((string)$request->post('meta_description', ''));

        $this->settingsRepo->save([
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
        ]);

        // Regenerate XML sitemap and robots.txt
        $this->generateSitemap();
        $this->generateRobotsTxt();

        flash('success', 'SEO configuration saved and sitemap.xml regenerated.');
        redirect('admin/seo');
    }

    public function generateSitemap(): void
    {
        $products = $this->productRepo->getActive();
        $categories = $this->categoryRepo->getActive();
        $baseUrl = App::getBaseUrl();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static routes
        $staticPages = ['', 'shop', 'categories', 'about', 'faq', 'contact', 'shipping-policy', 'returns-policy', 'privacy-policy', 'terms'];
        foreach ($staticPages as $page) {
            $loc = $baseUrl . ($page ? '/' . $page : '');
            $xml .= "  <url>\n    <loc>{$loc}</loc>\n    <changefreq>weekly</changefreq>\n    <priority>0.8</priority>\n  </url>\n";
        }

        // Categories
        foreach ($categories as $cat) {
            $loc = $baseUrl . '/category/' . $cat['slug'];
            $xml .= "  <url>\n    <loc>{$loc}</loc>\n    <changefreq>weekly</changefreq>\n    <priority>0.9</priority>\n  </url>\n";
        }

        // Products
        foreach ($products as $p) {
            $loc = $baseUrl . '/product/' . $p['slug'];
            $xml .= "  <url>\n    <loc>{$loc}</loc>\n    <changefreq>daily</changefreq>\n    <priority>1.0</priority>\n  </url>\n";
        }

        $xml .= '</urlset>';

        $sitemapPath = App::getBasePath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'sitemap.xml';
        file_put_contents($sitemapPath, $xml, LOCK_EX);
    }

    public function generateRobotsTxt(): void
    {
        $baseUrl = App::getBaseUrl();
        $robots = "User-agent: *\nDisallow: /admin/\nDisallow: /cart\nDisallow: /checkout\nDisallow: /account\n\nSitemap: {$baseUrl}/sitemap.xml\n";
        
        $robotsPath = App::getBasePath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'robots.txt';
        file_put_contents($robotsPath, $robots, LOCK_EX);
    }
}

