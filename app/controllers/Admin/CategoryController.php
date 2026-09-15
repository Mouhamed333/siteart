<?php

declare(strict_types=1);

namespace Admin;

class CategoryController extends \Controller
{
    public function index(): void
    {
        \Auth::requireAdmin();
        $categoryModel = new \Category();

        $this->view('admin/categories/index', [
            'title'      => 'Catégories',
            'categories' => $categoryModel->getActive(),
        ], 'admin');
    }

    public function store(): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();

        $name = \Validator::sanitize($_POST['name']);
        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $name));

        (new \Category())->create([
            'name'        => $name,
            'slug'        => trim($slug, '-'),
            'description' => \Validator::sanitize($_POST['description'] ?? ''),
        ]);

        \Session::flash('success', 'Catégorie créée.');
        $this->redirect(APP_URL . '/admin/categories');
    }
}
