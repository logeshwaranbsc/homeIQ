<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\BannerRepository;
use App\Repositories\AuditLogRepository;
use App\Helpers\FileUploader;

class AdminBannerController
{
    private BannerRepository $bannerRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->bannerRepo = new BannerRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function index(): void
    {
        $banners = $this->bannerRepo->all();
        usort($banners, fn($a, $b) => ($a['display_order'] ?? 0) <=> ($b['display_order'] ?? 0));

        echo View::render('admin/banners/index', [
            'title' => 'Hero Banner Management | HOMEIQ Admin',
            'banners' => $banners,
        ], 'admin');
    }

    public function create(): void
    {
        echo View::render('admin/banners/form', [
            'title' => 'Add Hero Slide | HOMEIQ Admin',
            'banner' => null,
        ], 'admin');
    }

    public function store(Request $request): void
    {
        $data = $this->extractBannerInput($request);

        $imageFile = $request->file('image');
        if ($imageFile) {
            $upload = FileUploader::upload($imageFile, 'banners');
            if ($upload['success']) {
                $data['image'] = $upload['filename'];
            }
        }

        $mobileImageFile = $request->file('mobile_image');
        if ($mobileImageFile) {
            $uploadMobile = FileUploader::upload($mobileImageFile, 'banners');
            if ($uploadMobile['success']) {
                $data['mobile_image'] = $uploadMobile['filename'];
            }
        }

        if (empty($data['title']) && empty($data['image']) && empty($data['mobile_image'])) {
            flash('error', 'Slide title or banner image is required.');
            redirect('admin/banners/create');
        }

        $created = $this->bannerRepo->create($data);
        $admin = auth_admin();
        $this->auditRepo->record('banner_created', $admin['username'] ?? 'Admin', "Created banner: " . ($created['title'] ?: 'Hero Slide'));

        flash('success', 'Hero banner added successfully.');
        redirect('admin/banners');
    }

    public function edit(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $banner = $this->bannerRepo->find($id);

        if (!$banner) {
            flash('error', 'Banner not found.');
            redirect('admin/banners');
        }

        echo View::render('admin/banners/form', [
            'title' => 'Edit Hero Slide | HOMEIQ Admin',
            'banner' => $banner,
        ], 'admin');
    }

    public function update(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $banner = $this->bannerRepo->find($id);

        if (!$banner) {
            flash('error', 'Banner not found.');
            redirect('admin/banners');
        }

        $data = $this->extractBannerInput($request);

        // Desktop banner image
        $imageFile = $request->file('image');
        if ($imageFile) {
            $upload = FileUploader::upload($imageFile, 'banners');
            if ($upload['success']) {
                FileUploader::delete($banner['image'] ?? null);
                $data['image'] = $upload['filename'];
            } else {
                $data['image'] = $banner['image'] ?? null;
            }
        } else {
            $data['image'] = $banner['image'] ?? null;
        }

        // Dedicated mobile screen banner image
        $mobileImageFile = $request->file('mobile_image');
        $deleteMobileImage = (bool)$request->post('delete_mobile_image', false);

        if ($mobileImageFile) {
            $uploadMobile = FileUploader::upload($mobileImageFile, 'banners');
            if ($uploadMobile['success']) {
                FileUploader::delete($banner['mobile_image'] ?? null);
                $data['mobile_image'] = $uploadMobile['filename'];
            } else {
                $data['mobile_image'] = $banner['mobile_image'] ?? null;
            }
        } elseif ($deleteMobileImage) {
            FileUploader::delete($banner['mobile_image'] ?? null);
            $data['mobile_image'] = null;
        } else {
            $data['mobile_image'] = $banner['mobile_image'] ?? null;
        }

        $this->bannerRepo->update($id, $data);
        $admin = auth_admin();
        $this->auditRepo->record('banner_updated', $admin['username'] ?? 'Admin', "Updated banner: " . ($data['title'] ?: 'Hero Slide'));

        flash('success', 'Hero banner updated successfully.');
        redirect('admin/banners');
    }

    public function delete(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $banner = $this->bannerRepo->find($id);

        if ($banner) {
            FileUploader::delete($banner['image'] ?? null);
            FileUploader::delete($banner['mobile_image'] ?? null);
            $this->bannerRepo->delete($id);
            $admin = auth_admin();
            $this->auditRepo->record('banner_deleted', $admin['username'] ?? 'Admin', "Deleted banner: " . ($banner['title'] ?? ($banner['headline'] ?? 'Hero Slide')));
            flash('success', 'Banner deleted.');
        }

        redirect('admin/banners');
    }

    private function extractBannerInput(Request $request): array
    {
        $title = trim((string)$request->post('title', ''));
        if (empty($title)) {
            $title = trim((string)$request->post('headline', 'Hero Slide'));
        }

        return [
            'title' => $title,
            'headline' => $title, // maintain compatibility with existing records
            'cta_url' => trim((string)$request->post('cta_url', '')),
            'display_order' => (int)$request->post('display_order', 1),
            'is_active' => (bool)$request->post('is_active', true),
        ];
    }
}

