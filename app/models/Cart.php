<?php

declare(strict_types=1);

class Cart
{
    private const SESSION_KEY = 'cart';
    private const COUPON_KEY = 'cart_coupon';

    public static function getItems(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public static function add(int $productId, int $quantity = 1, ?string $size = null, ?string $color = null): void
    {
        $cart = self::getItems();
        $key = $productId . '_' . ($size ?? '') . '_' . ($color ?? '');

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $productModel = new Product();
            $product = $productModel->find($productId);
            if (!$product) return;

            $cart[$key] = [
                'product_id' => $productId,
                'name'       => $product['name'],
                'price'      => $product['sale_price'] ?? $product['price'],
                'image'      => json_decode($product['images'] ?? '[]', true)[0] ?? 'placeholder.jpg',
                'quantity'   => $quantity,
                'size'       => $size,
                'color'      => $color,
                'stock'      => $product['stock'],
            ];
        }

        Session::set(self::SESSION_KEY, $cart);
    }

    public static function update(string $key, int $quantity): void
    {
        $cart = self::getItems();
        if (isset($cart[$key])) {
            if ($quantity <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = min($quantity, $cart[$key]['stock']);
            }
            Session::set(self::SESSION_KEY, $cart);
        }
    }

    public static function remove(string $key): void
    {
        $cart = self::getItems();
        unset($cart[$key]);
        Session::set(self::SESSION_KEY, $cart);
    }

    public static function clear(): void
    {
        Session::remove(self::SESSION_KEY);
        Session::remove(self::COUPON_KEY);
    }

    public static function count(): int
    {
        return array_sum(array_column(self::getItems(), 'quantity'));
    }

    public static function getSubtotal(): float
    {
        $total = 0;
        foreach (self::getItems() as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public static function getShipping(): float
    {
        // Livraison retirée du calcul : plus de frais ni de mode de livraison facturé.
        return 0.0;
    }

    public static function getDiscount(): float
    {
        $coupon = Session::get(self::COUPON_KEY);
        if (!$coupon) return 0;

        $subtotal = self::getSubtotal();
        if ($subtotal < ($coupon['min_order'] ?? 0)) return 0;

        if ($coupon['type'] === 'percentage') {
            return $subtotal * ($coupon['value'] / 100);
        }
        return min((float) $coupon['value'], $subtotal);
    }

    public static function getTax(): float
    {
        // TVA retirée : total = sous-total - remise + livraison, sans taxe ajoutée.
        return 0.0;
    }

    public static function getTotal(): float
    {
        return self::getSubtotal() - self::getDiscount() + self::getShipping();
    }

    public static function applyCoupon(array $coupon): void
    {
        Session::set(self::COUPON_KEY, $coupon);
    }

    public static function getCoupon(): ?array
    {
        return Session::get(self::COUPON_KEY);
    }

    public static function getSummary(): array
    {
        return [
            'items'     => self::getItems(),
            'count'     => self::count(),
            'subtotal'  => self::getSubtotal(),
            'shipping'  => self::getShipping(),
            'discount'  => self::getDiscount(),
            'tax'       => self::getTax(),
            'total'     => self::getTotal(),
            'coupon'    => self::getCoupon(),
        ];
    }
}
