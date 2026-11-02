<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Repositories\CouponRepository;
use App\Repositories\AuditLogRepository;

class AdminCouponController
{
    private CouponRepository $couponRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->couponRepo = new CouponRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function index(): void
    {
        $coupons = $this->couponRepo->all();
        echo View::render('admin/coupons/index', [
            'title' => 'Discounts & Coupons | HOMEIQ Admin',
            'coupons' => $coupons,
        ], 'admin');
    }

    public function create(): void
    {
        echo View::render('admin/coupons/form', [
            'title' => 'Create Coupon | HOMEIQ Admin',
            'coupon' => null,
        ], 'admin');
    }

    public function store(Request $request): void
    {
        $code = strtoupper(trim((string)$request->post('code', '')));
        $type = (string)$request->post('type', 'percentage'); // 'percentage' or 'fixed'
        $value = (float)$request->post('value', 0);
        $minOrder = (float)$request->post('min_order_amount', 0);
        $maxDiscount = !empty($request->post('max_discount')) ? (float)$request->post('max_discount') : null;
        $expiresAt = trim((string)$request->post('expires_at', ''));
        $isActive = (bool)$request->post('is_active', true);

        if (empty($code) || $value <= 0) {
            flash('error', 'Coupon code and a valid positive value are required.');
            redirect('admin/coupons/create');
        }

        if ($this->couponRepo->findByCode($code)) {
            flash('error', 'A coupon with this code already exists.');
            redirect('admin/coupons/create');
        }

        $created = $this->couponRepo->create([
            'code' => $code,
            'type' => $type,
            'value' => $value,
            'min_order_amount' => $minOrder,
            'max_discount' => $maxDiscount,
            'expires_at' => $expiresAt ?: null,
            'is_active' => $isActive,
        ]);

        $admin = auth_admin();
        $this->auditRepo->record('coupon_created', $admin['username'] ?? 'Admin', "Created coupon {$code}");

        flash('success', "Coupon '{$code}' created successfully.");
        redirect('admin/coupons');
    }

    public function edit(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $coupon = $this->couponRepo->find($id);

        if (!$coupon) {
            flash('error', 'Coupon not found.');
            redirect('admin/coupons');
        }

        echo View::render('admin/coupons/form', [
            'title' => 'Edit Coupon ' . $coupon['code'] . ' | HOMEIQ Admin',
            'coupon' => $coupon,
        ], 'admin');
    }

    public function update(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $coupon = $this->couponRepo->find($id);

        if (!$coupon) {
            flash('error', 'Coupon not found.');
            redirect('admin/coupons');
        }

        $code = strtoupper(trim((string)$request->post('code', '')));
        $type = (string)$request->post('type', 'percentage');
        $value = (float)$request->post('value', 0);
        $minOrder = (float)$request->post('min_order_amount', 0);
        $maxDiscount = !empty($request->post('max_discount')) ? (float)$request->post('max_discount') : null;
        $expiresAt = trim((string)$request->post('expires_at', ''));
        $isActive = (bool)$request->post('is_active', true);

        $this->couponRepo->update($id, [
            'code' => $code,
            'type' => $type,
            'value' => $value,
            'min_order_amount' => $minOrder,
            'max_discount' => $maxDiscount,
            'expires_at' => $expiresAt ?: null,
            'is_active' => $isActive,
        ]);

        flash('success', "Coupon '{$code}' updated.");
        redirect('admin/coupons');
    }

    public function delete(Request $request, array $params): void
    {
        $id = $params['id'] ?? '';
        $this->couponRepo->delete($id);
        flash('success', 'Coupon deleted.');
        redirect('admin/coupons');
    }
}

