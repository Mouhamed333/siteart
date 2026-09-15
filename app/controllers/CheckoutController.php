<?php

declare(strict_types=1);

class CheckoutController extends Controller
{
    public function index(): void
    {
        if (empty(Cart::getItems())) {
            Session::flash('error', 'Votre panier est vide.');
            $this->redirect(APP_URL . '/boutique');
        }

        $this->view('checkout/index', [
            'title'   => 'Finaliser ma commande - Art\' Afric',
            'summary' => Cart::getSummary(),
            'user'    => Auth::user(),
        ]);
    }

    public function process(): void
    {
        CSRF::verify();

        if (empty(Cart::getItems())) {
            $this->redirect(APP_URL . '/boutique');
        }

        $validator = new Validator($_POST);
        $validator->required('first_name')->required('last_name')
            ->required('email', 'Email requis.')->email('email')
            ->required('phone')->required('address')
            ->required('city');

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/commande');
        }

        $orderModel = new Order();
        $summary = Cart::getSummary();

        $shippingAddress = [
            'first_name'   => Validator::sanitize($_POST['first_name']),
            'last_name'    => Validator::sanitize($_POST['last_name']),
            'email'        => Validator::sanitize($_POST['email']),
            'phone'        => Validator::sanitize($_POST['phone']),
            'address'      => Validator::sanitize($_POST['address']),
            'city'         => Validator::sanitize($_POST['city']),
            'region'       => Validator::sanitize($_POST['region'] ?? ''),
            'country'      => Validator::sanitize($_POST['country'] ?? 'Sénégal'),
        ];

        $orderData = [
            'user_id'          => Auth::id(),
            'order_number'     => $orderModel->generateOrderNumber(),
            'status'           => 'pending',
            'subtotal'         => $summary['subtotal'],
            'shipping_cost'    => $summary['shipping'],
            'discount'         => $summary['discount'],
            'tax'              => $summary['tax'],
            'total'            => $summary['total'],
            'coupon_code'      => $summary['coupon']['code'] ?? null,
            'payment_method'   => 'cash_on_delivery',
            'payment_status'   => 'pending',
            'shipping_address' => json_encode($shippingAddress),
        ];

        try {
            $orderId = $orderModel->createOrder($orderData, array_values($summary['items']));

            if ($summary['coupon']) {
                (new Coupon())->incrementUsage((int) $summary['coupon']['id']);
            }

            Cart::clear();
            $this->redirect(APP_URL . '/commande/confirmation/' . $orderId);
        } catch (Exception $e) {
            Session::flash('error', 'Erreur lors de la commande. Veuillez réessayer.');
            $this->redirect(APP_URL . '/commande');
        }
    }

    public function confirmation(string $id): void
    {
        $orderModel = new Order();
        $order = $orderModel->getWithItems((int) $id);

        if (!$order) {
            http_response_code(404);
            require APP_PATH . '/views/errors/404.php';
            return;
        }

        $this->view('checkout/confirmation', [
            'title' => 'Commande confirmée - Art\' Afric',
            'order' => $order,
        ]);
    }
}
