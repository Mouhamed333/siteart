<?php

declare(strict_types=1);

namespace Admin;

class DashboardController extends \Controller
{
    public function index(): void
    {
        \Auth::requireAdmin();
        $orderModel = new \Order();

        $this->view('admin/dashboard', [
            'title' => 'Tableau de bord - Admin',
            'stats' => $orderModel->getStats(),
        ], 'admin');
    }

    public function stats(): void
    {
        \Auth::requireAdmin();
        $orderModel = new \Order();

        $this->view('admin/dashboard', [
            'title' => 'Statistiques - Admin',
            'stats' => $orderModel->getStats(),
        ], 'admin');
    }
}
