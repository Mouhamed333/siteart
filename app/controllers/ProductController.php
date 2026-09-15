<?php

declare(strict_types=1);

class ProductController extends Controller
{
    public function show(string $slug): void
    {
        $productModel = new Product();
        $product = $productModel->findBySlug($slug);

        if (!$product) {
            http_response_code(404);
            require APP_PATH . '/views/errors/404.php';
            return;
        }

        $productModel->incrementViews((int) $product['id']);

        $reviewModel = new Review();
        $favoriteModel = new Favorite();

        $recentlyViewed = Session::get('recently_viewed', []);
        array_unshift($recentlyViewed, $product['id']);
        $recentlyViewed = array_unique(array_slice($recentlyViewed, 0, 10));
        Session::set('recently_viewed', $recentlyViewed);

        $images = json_decode($product['images'] ?? '[]', true) ?: ['placeholder.jpg'];
        $sizes = json_decode($product['sizes'] ?? '[]', true) ?: [];

        $this->view('product/show', [
            'title'     => $product['name'] . ' - Art\' Afric',
            'product'   => $product,
            'images'    => $images,
            'sizes'     => $sizes,
            'reviews'   => $reviewModel->getByProduct((int) $product['id']),
            'similar'   => $productModel->getSimilar((int) $product['category_id'], (int) $product['id']),
            'isFavorite'=> Auth::check() ? $favoriteModel->isFavorite(Auth::id(), (int) $product['id']) : false,
        ]);
    }
}
