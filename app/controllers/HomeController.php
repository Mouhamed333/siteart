<?php

declare(strict_types=1);

class HomeController extends Controller
{
    public function index(): void
    {
        $productModel = new Product();
        $categoryModel = new Category();
        $partnerModel = new Partner();

        $this->view('home/index', [
            'title'      => "Art' Afric - Découvrez toute la richesse de l'art africain",
            'featured'   => $productModel->getFeatured(8),
            'newProducts'=> $productModel->getNew(8),
            'promos'     => $productModel->getPromo(8),
            'categories' => $categoryModel->getActive(),
            'partners'   => $partnerModel->getActive(),
        ]);
    }
}
