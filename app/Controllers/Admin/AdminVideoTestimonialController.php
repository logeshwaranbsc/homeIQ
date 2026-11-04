<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\VideoTestimonialRepository;
use App\Repositories\AuditLogRepository;
use App\Helpers\FileUploader;

class AdminVideoTestimonialController
{
    private VideoTestimonialRepository $repo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->repo = new VideoTestimonialRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function index(): void
    {
        $testimonials = $this->repo->getAllSorted();
        $count = count($testimonials);
        $maxVideos = VideoTestimonialRepository::MAX_ITEMS;
        $canAdd = $this->repo->canAdd();
        $remainingSlots = $this->repo->getRemainingSlots();

        echo View::render('admin/video_testimonials/index', [
            'title' => 'Video Testimonials Management | HOMEIQ Admin',
            'testimonials' => $testimonials,
            'count' => $count,
            'maxVideos' => $maxVideos,
            'canAdd' => $canAdd,
            'remainingSlots' => $remainingSlots,
        ], 'admin');
    }

    public function create(): void
    {
        if (!$this->repo->canAdd()) {
            flash('error', 'Maximum limit of ' . VideoTestimonialRepository::MAX_ITEMS . ' video testimonials reached. You can edit or delete existing entries.');
            redirect('admin/video-testimonials');
        }

        echo View::render('admin/video_testimonials/form', [
            'title' => 'Upload Video Testimonial | HOMEIQ Admin',
            'testimonial' => null,
            'maxVideos' => VideoTestimonialRepository::MAX_ITEMS,
            'currentCount' => $this->repo->count(),
        ], 'admin');
    }

    public function store(Request $request): void
    {
        if (!$this->repo->canAdd()) {
            flash('error', 'Maximum limit of ' . VideoTestimonialRepository::MAX_ITEMS . ' video testimonials reached.');
            redirect('admin/video-testimonials');
        }

        $data = $this->extractInput($request);

        if (empty($data['customer_name'])) {
            flash('error', 'Customer / Homeowner name is required.');
            redirect('admin/video-testimonials/create');
        }

        // Upload Video File
        $videoFile = $request->file('video_file');
        if ($videoFile) {
            $upload = FileUploader::uploadVideo($videoFile, 'video_testimonials');
            if (!$upload['success']) {
                flash('error', $upload['error'] ?? 'Video upload failed.');
                redirect('admin/video-testimonials/create');
            }
            $data['video_path'] = $upload['filename'];
        }

        // Upload Thumbnail Image
        $thumbFile = $request->file('thumbnail');
        if ($thumbFile) {
            $thumbUpload = FileUploader::upload($thumbFile, 'video_testimonials');
            if (!$thumbUpload['success']) {
                // If video was uploaded in this step, delete it to avoid orphan files
                if (!empty($data['video_path'])) {
                    FileUploader::delete($data['video_path']);
                }
                flash('error', $thumbUpload['error'] ?? 'Thumbnail image upload failed.');
                redirect('admin/video-testimonials/create');
            }
            $data['thumbnail_path'] = $thumbUpload['filename'];
        }

        // Must have either an uploaded video or external video url
        if (empty($data['video_path']) && empty($data['video_url'])) {
            flash('error', 'Please upload a video file or provide an external video link.');
            redirect('admin/video-testimonials/create');
        }

        $created = $this->repo->create($data);
        $admin = auth_admin();
        $this->auditRepo->record('video_testimonial_created', $admin['username'] ?? 'Admin', "Uploaded video testimonial for {$created['customer_name']} (ID: {$created['id']})");

        flash('success', 'Video testimonial uploaded and published successfully (' . $this->repo->count() . '/' . VideoTestimonialRepository::MAX_ITEMS . ' slots used).');
        redirect('admin/video-testimonials');
    }

    public function edit(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $testimonial = $this->repo->find($id);

        if (!$testimonial) {
            flash('error', 'Video testimonial not found.');
            redirect('admin/video-testimonials');
        }

        echo View::render('admin/video_testimonials/form', [
            'title' => 'Edit Video Testimonial | HOMEIQ Admin',
            'testimonial' => $testimonial,
            'maxVideos' => VideoTestimonialRepository::MAX_ITEMS,
            'currentCount' => $this->repo->count(),
        ], 'admin');
    }

    public function update(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $testimonial = $this->repo->find($id);

        if (!$testimonial) {
            flash('error', 'Video testimonial not found.');
            redirect('admin/video-testimonials');
        }

        $data = $this->extractInput($request);

        if (empty($data['customer_name'])) {
            flash('error', 'Customer / Homeowner name is required.');
            redirect('admin/video-testimonials/edit/' . $id);
        }

        // Process new video file upload if provided
        $videoFile = $request->file('video_file');
        if ($videoFile) {
            $upload = FileUploader::uploadVideo($videoFile, 'video_testimonials');
            if (!$upload['success']) {
                flash('error', $upload['error'] ?? 'Video upload failed.');
                redirect('admin/video-testimonials/edit/' . $id);
            }
            if (!empty($testimonial['video_path'])) {
                FileUploader::delete($testimonial['video_path']);
            }
            $data['video_path'] = $upload['filename'];
        } else {
            $data['video_path'] = $testimonial['video_path'] ?? null;
        }

        // Process new thumbnail image upload if provided
        $thumbFile = $request->file('thumbnail');
        if ($thumbFile) {
            $thumbUpload = FileUploader::upload($thumbFile, 'video_testimonials');
            if (!$thumbUpload['success']) {
                flash('error', $thumbUpload['error'] ?? 'Thumbnail upload failed.');
                redirect('admin/video-testimonials/edit/' . $id);
            }
            if (!empty($testimonial['thumbnail_path'])) {
                FileUploader::delete($testimonial['thumbnail_path']);
            }
            $data['thumbnail_path'] = $thumbUpload['filename'];
        } else {
            $data['thumbnail_path'] = $testimonial['thumbnail_path'] ?? null;
        }

        if (empty($data['video_path']) && empty($data['video_url'])) {
            flash('error', 'A video file or external video link is required.');
            redirect('admin/video-testimonials/edit/' . $id);
        }

        $this->repo->update($id, $data);
        $admin = auth_admin();
        $this->auditRepo->record('video_testimonial_updated', $admin['username'] ?? 'Admin', "Updated video testimonial for {$data['customer_name']} (ID: {$id})");

        flash('success', 'Video testimonial updated successfully.');
        redirect('admin/video-testimonials');
    }

    public function delete(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $testimonial = $this->repo->find($id);

        if ($testimonial) {
            if (!empty($testimonial['video_path'])) {
                FileUploader::delete($testimonial['video_path']);
            }
            if (!empty($testimonial['thumbnail_path'])) {
                FileUploader::delete($testimonial['thumbnail_path']);
            }

            $this->repo->delete($id);
            $admin = auth_admin();
            $this->auditRepo->record('video_testimonial_deleted', $admin['username'] ?? 'Admin', "Deleted video testimonial: {$testimonial['customer_name']}");
            flash('success', 'Video testimonial deleted. An upload slot has been freed up (' . $this->repo->count() . '/' . VideoTestimonialRepository::MAX_ITEMS . ' used).');
        } else {
            flash('error', 'Video testimonial not found.');
        }

        redirect('admin/video-testimonials');
    }

    public function toggleStatus(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $testimonial = $this->repo->find($id);

        if ($testimonial) {
            $newState = empty($testimonial['is_active']);
            $this->repo->update($id, ['is_active' => $newState]);
            $admin = auth_admin();
            $this->auditRepo->record('video_testimonial_status', $admin['username'] ?? 'Admin', "Changed video testimonial status for {$testimonial['customer_name']} to " . ($newState ? 'Active' : 'Disabled'));
            flash('success', 'Video status updated.');
        }

        redirect('admin/video-testimonials');
    }

    private function extractInput(Request $request): array
    {
        $desc = trim((string)$request->post('description', $request->post('review_text', '')));
        return [
            'customer_name' => trim((string)$request->post('customer_name', '')),
            'description' => $desc,
            'review_text' => $desc, // backwards-compatible alias
            'video_url' => trim((string)$request->post('video_url', '')),
            'display_order' => max(1, min(4, (int)$request->post('display_order', 1))),
            'is_active' => (bool)$request->post('is_active', true),
        ];
    }
}

