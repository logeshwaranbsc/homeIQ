<?php
declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Core\Request;
use App\Core\View;
use App\Repositories\JsonRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\ContactMessageRepository;

class PageController
{
    private SettingsRepository $settingsRepo;

    public function __construct()
    {
        $this->settingsRepo = new SettingsRepository();
    }

    public function about(): void
    {
        echo View::render('storefront/pages/about', [
            'title' => 'About HOMEIQ | Intelligent Smart Door Locks & Security Hardware',
            'settings' => $this->settingsRepo->all(),
        ]);
    }

    public function faq(): void
    {
        echo View::render('storefront/pages/faq', [
            'title' => 'Frequently Asked Questions | HOMEIQ Smart Security',
        ]);
    }

    public function contact(): void
    {
        echo View::render('storefront/pages/contact', [
            'title' => 'Contact Us | HOMEIQ Customer Support & Inquiries',
        ]);
    }

    public function submitContact(Request $request): void
    {
        $inquiryType = trim((string)$request->post('inquiry_type', 'general'));

        if ($inquiryType === 'bulk') {
            $name = trim((string)$request->post('bulk_name', ''));
            $phone = trim((string)$request->post('bulk_phone', ''));
            $company = trim((string)$request->post('company', ''));
            $projectType = trim((string)$request->post('project_type', ''));
            $quantity = trim((string)$request->post('quantity', ''));
            $location = trim((string)$request->post('location', ''));
            $timeline = trim((string)$request->post('timeline', ''));
            $message = trim((string)$request->post('bulk_message', ''));
            $qtyText = $quantity ? " ({$quantity} Units)" : "";
            $subject = "Bulk Order Inquiry: " . ($company ?: $name) . $qtyText;

            if (empty($name) || empty($phone) || empty($company) || empty($quantity) || empty($message)) {
                flash('error', 'Please fill in all required fields for your bulk inquiry.');
                redirect('contact?type=bulk');
            }

            $repo = new ContactMessageRepository();
            $repo->create([
                'inquiry_type' => 'bulk',
                'name' => $name,
                'email' => '',
                'phone' => $phone,
                'company' => $company,
                'project_type' => $projectType,
                'quantity' => $quantity,
                'location' => $location,
                'timeline' => $timeline,
                'subject' => $subject,
                'message' => $message,
                'ip' => $request->ip(),
                'status' => 'unread',
            ]);

            flash('success', 'Thank you for your bulk inquiry! Our B2B commercial accounts manager will contact you within 24 hours with custom volume pricing.');
            redirect('contact?type=bulk');
        }

        // Standard General Inquiry
        $name = trim((string)$request->post('name', ''));
        $email = mb_strtolower(trim((string)$request->post('email', '')));
        $phone = trim((string)$request->post('phone', ''));
        $subject = trim((string)$request->post('subject', 'General Inquiry'));
        $message = trim((string)$request->post('message', ''));

        if (empty($name) || empty($email) || empty($message)) {
            flash('error', 'Please fill in all required fields.');
            redirect('contact');
        }

        $repo = new ContactMessageRepository();
        $repo->create([
            'inquiry_type' => 'general',
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'ip' => $request->ip(),
            'status' => 'unread',
        ]);

        flash('success', 'Thank you for reaching out! Our smart security specialists will get back to you shortly.');
        redirect('contact');
    }

    public function shippingPolicy(): void
    {
        echo View::render('storefront/pages/shipping', [
            'title' => 'Shipping & Delivery Policy | HOMEIQ',
        ]);
    }

    public function returnsPolicy(): void
    {
        echo View::render('storefront/pages/returns', [
            'title' => 'Return & Refund Policy | HOMEIQ',
        ]);
    }

    public function privacyPolicy(): void
    {
        echo View::render('storefront/pages/privacy', [
            'title' => 'Privacy Policy | HOMEIQ',
        ]);
    }

    public function terms(): void
    {
        echo View::render('storefront/pages/terms', [
            'title' => 'Terms & Conditions | HOMEIQ',
        ]);
    }
}

