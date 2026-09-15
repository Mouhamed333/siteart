<?php

declare(strict_types=1);

namespace Admin;

class PartnerController extends \Controller
{
    public function index(): void
    {
        \Auth::requireAdmin();
        $partnerModel = new \Partner();

        $this->view('admin/partners/index', [
            'title'    => 'Nos partenaires',
            'partners' => $partnerModel->getAllOrdered(),
        ], 'admin');
    }

    public function store(): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();

        $name = \Validator::sanitize($_POST['name'] ?? '');
        if ($name === '') {
            \Session::flash('error', 'Le nom du partenaire est requis.');
            $this->redirect(APP_URL . '/admin/partenaires');
        }

        (new \Partner())->create([
            'name'        => $name,
            'website_url' => \Validator::sanitize($_POST['website_url'] ?? '') ?: null,
            'sort_order'  => (int) ($_POST['sort_order'] ?? 0),
            'is_active'   => 1,
        ]);

        \Session::flash('success', 'Partenaire ajouté.');
        $this->redirect(APP_URL . '/admin/partenaires');
    }

    public function toggle(string $id): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();

        $partnerModel = new \Partner();
        $partner = $partnerModel->find((int) $id);
        if ($partner) {
            $partnerModel->update((int) $id, ['is_active' => $partner['is_active'] ? 0 : 1]);
        }

        $this->redirect(APP_URL . '/admin/partenaires');
    }

    public function delete(string $id): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();

        (new \Partner())->delete((int) $id);

        \Session::flash('success', 'Partenaire supprimé.');
        $this->redirect(APP_URL . '/admin/partenaires');
    }
}
