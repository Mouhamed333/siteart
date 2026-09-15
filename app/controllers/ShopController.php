<?php

declare(strict_types=1);

class ShopController extends Controller
{
    public function index(): void
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $filters = [
            'category' => $this->input('categorie'),
            'min_price'=> $this->input('prix_min'),
            'max_price'=> $this->input('prix_max'),
            'color'    => $this->input('couleur'),
            'material' => $this->input('matiere'),
            'promo'    => $this->input('promo'),
            'new'      => $this->input('nouveaute'),
            'search'   => $this->input('q'),
            'sort'     => $this->input('tri', 'popular'),
        ];

        $page = max(1, (int) $this->input('page', 1));
        $result = $productModel->getAll(array_filter($filters), $page);

        $this->view('shop/index', [
            'title'      => 'Boutique - Art\' Afric',
            'products'   => $result['products'],
            'total'      => $result['total'],
            'pages'      => $result['pages'],
            'page'       => $result['page'],
            'categories' => $categoryModel->getActive(),
            'filters'    => $filters,
        ]);
    }

    public function category(string $slug): void
    {
        $categoryModel = new Category();
        $category = $categoryModel->findBySlug($slug);

        if (!$category) {
            http_response_code(404);
            require APP_PATH . '/views/errors/404.php';
            return;
        }

        $productModel = new Product();
        $page = max(1, (int) $this->input('page', 1));
        $result = $productModel->getAll(['category' => $slug], $page);

        $this->view('shop/category', [
            'title'    => $category['name'] . ' - Art\' Afric',
            'category' => $category,
            'products' => $result['products'],
            'pages'    => $result['pages'],
            'page'     => $result['page'],
        ]);
    }
}
