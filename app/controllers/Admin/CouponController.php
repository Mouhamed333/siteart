<?php

declare(strict_types=1);

namespace Admin;

class CouponController extends \Controller
{
    public function index(): void
    {
        \Auth::requireAdmin();
        $couponModel = new \Coupon();

        $this->view('admin/coupons/index', [
            'title'   => 'Coupons',
            'coupons' => $couponModel->all(),
        ], 'admin');
    }

    public function store(): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();

        (new \Coupon())->create([
            'code'       => strtoupper(\Validator::sanitize($_POST['code'])),
            'type'       => $_POST['type'] ?? 'percentage',
            'value'      => (float) $_POST['value'],
            'min_order'  => (float) ($_POST['min_order'] ?? 0),
            'max_uses'   => !empty($_POST['max_uses']) ? (int) $_POST['max_uses'] : null,
            'expires_at' => $_POST['expires_at'] ?? null,
        ]);

        \Session::flash('success', 'Coupon créé.');
        $this->redirect(APP_URL . '/admin/coupons');
    }
}
