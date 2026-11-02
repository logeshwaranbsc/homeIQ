<?php
declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Core\Request;
use App\Core\View;
use App\Repositories\JsonRepository;

class PageController
{
    public function about(): void
    {
        echo View::render('storefront/pages/about', [
            'title' => 'About HOMEIQ | Intelligent Smart Door Locks & Security Hardware',
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
        $name = trim((string)$request->post('name', ''));
        $email = mb_strtolower(trim((string)$request->post('email', '')));
        $phone = trim((string)$request->post('phone', ''));
        $subject = trim((string)$request->post('subject', ''));
        $message = trim((string)$request->post('message', ''));

        if (empty($name) || empty($email) || empty($message)) {
            flash('error', 'Please fill in all required fields.');
            redirect('contact');
        }

        $repo = new class('contact_messages.json', 'msg_') extends JsonRepository {};
        $repo->create([
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

