<?php

declare(strict_types=1);

namespace Admin;

class OrderController extends \Controller
{
    public function index(): void
    {
        \Auth::requireAdmin();
        $orderModel = new \Order();

        $this->view('admin/orders/index', [
            'title'  => 'Commandes',
            'orders' => $orderModel->getAllOrders(),
        ], 'admin');
    }

    public function show(string $id): void
    {
        \Auth::requireAdmin();
        $orderModel = new \Order();
        $order = $orderModel->getWithItems((int) $id);

        $this->view('admin/orders/show', [
            'title' => 'Commande #' . ($order['order_number'] ?? $id),
            'order' => $order,
        ], 'admin');
    }

    public function updateStatus(string $id): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();

        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
        $validPaymentStatuses = ['pending', 'paid', 'failed', 'refunded'];

        $status = $_POST['status'] ?? '';
        $paymentStatus = $_POST['payment_status'] ?? '';

        $data = [];
        if (in_array($status, $validStatuses, true)) {
            $data['status'] = $status;
        }
        if (in_array($paymentStatus, $validPaymentStatuses, true)) {
            $data['payment_status'] = $paymentStatus;
        }

        if (!empty($data)) {
            (new \Order())->update((int) $id, $data);
            \Session::flash('success', 'Statut de la commande mis à jour.');
        }

        $this->redirect(APP_URL . '/admin/commandes/' . $id);
    }
}
