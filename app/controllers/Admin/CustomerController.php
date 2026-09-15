<?php

declare(strict_types=1);

namespace Admin;

class CustomerController extends \Controller
{
    public function index(): void
    {
        \Auth::requireAdmin();
        $userModel = new \User();

        $this->view('admin/customers/index', [
            'title'    => 'Clients',
            'customers'=> $userModel->getClients(),
        ], 'admin');
    }
}
