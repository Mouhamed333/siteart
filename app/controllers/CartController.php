<?php

declare(strict_types=1);

class CartController extends Controller
{
    public function index(): void
    {
        $summary = Cart::getSummary();

        $this->view('cart/index', [
            'title'   => 'Mon Panier - Art\' Afric',
            'summary' => $summary,
        ]);
    }

    public function add(): void
    {
        CSRF::verify();

        $productId = (int) $this->input('product_id');
        $quantity = max(1, (int) $this->input('quantity', 1));
        $size = $this->input('size');
        $color = $this->input('color');

        Cart::add($productId, $quantity, $size, $color);

        if ($this->isAjax()) {
            $this->json(['success' => true, 'count' => Cart::count(), 'message' => 'Produit ajouté au panier.']);
        }

        Session::flash('success', 'Produit ajouté au panier.');
        $this->redirect($_SERVER['HTTP_REFERER'] ?? APP_URL . '/boutique');
    }

    public function update(): void
    {
        CSRF::verify();

        $key = $this->input('key');
        $quantity = (int) $this->input('quantity');

        Cart::update($key, $quantity);

        if ($this->isAjax()) {
            $this->json(['success' => true, 'summary' => Cart::getSummary()]);
        }

        $this->redirect(APP_URL . '/panier');
    }

    public function remove(): void
    {
        CSRF::verify();
        Cart::remove($this->input('key'));

        if ($this->isAjax()) {
            $this->json(['success' => true, 'summary' => Cart::getSummary()]);
        }

        Session::flash('success', 'Article retiré du panier.');
        $this->redirect(APP_URL . '/panier');
    }

    public function applyCoupon(): void
    {
        CSRF::verify();

        $code = strtoupper(trim($this->input('code', '')));
        $couponModel = new Coupon();
        $coupon = $couponModel->findByCode($code);

        if (!$coupon) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Code promo invalide ou expiré.'], 400);
            }
            Session::flash('error', 'Code promo invalide ou expiré.');
            $this->redirect(APP_URL . '/panier');
        }

        if (Cart::getSubtotal() < $coupon['min_order']) {
            $msg = 'Montant minimum requis : ' . number_format((float)$coupon['min_order'], 0, ',', ' ') . ' FCFA';
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => $msg], 400);
            }
            Session::flash('error', $msg);
            $this->redirect(APP_URL . '/panier');
        }

        Cart::applyCoupon($coupon);

        if ($this->isAjax()) {
            $this->json(['success' => true, 'summary' => Cart::getSummary(), 'message' => 'Code promo appliqué !']);
        }

        Session::flash('success', 'Code promo appliqué !');
        $this->redirect(APP_URL . '/panier');
    }
}
