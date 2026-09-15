<?php

declare(strict_types=1);

class ApiController extends Controller
{
    public function search(): void
    {
        $query = trim($this->input('q', ''));
        if (strlen($query) < 2) {
            $this->json(['results' => []]);
        }

        $productModel = new Product();
        $results = $productModel->search($query);

        $formatted = array_map(function ($p) {
            $images = json_decode($p['images'] ?? '[]', true);
            return [
                'id'       => $p['id'],
                'name'     => $p['name'],
                'slug'     => $p['slug'],
                'price'    => $p['sale_price'] ?? $p['price'],
                'image'    => $images[0] ?? 'placeholder.jpg',
                'category' => $p['category_name'],
                'url'      => APP_URL . '/produit/' . $p['slug'],
            ];
        }, $results);

        $this->json(['results' => $formatted]);
    }

    public function toggleFavorite(): void
    {
        CSRF::verify();
        Auth::requireAuth();

        $productId = (int) $this->input('product_id');
        $favoriteModel = new Favorite();
        $added = $favoriteModel->toggle(Auth::id(), $productId);

        $this->json([
            'success'  => true,
            'added'    => $added,
            'message'  => $added ? 'Ajouté aux favoris.' : 'Retiré des favoris.',
        ]);
    }

    public function products(): void
    {
        $productModel = new Product();
        $filters = [
            'category' => $this->input('category'),
            'search'   => $this->input('q'),
            'sort'     => $this->input('sort', 'popular'),
            'promo'    => $this->input('promo'),
            'new'      => $this->input('new'),
        ];
        $page = max(1, (int) $this->input('page', 1));
        $result = $productModel->getAll(array_filter($filters), $page);

        $this->json($result);
    }

    public function addReview(): void
    {
        CSRF::verify();
        Auth::requireAuth();

        $reviewModel = new Review();
        $reviewModel->addReview([
            'product_id' => (int) $this->input('product_id'),
            'user_id'    => Auth::id(),
            'rating'     => max(1, min(5, (int) $this->input('rating'))),
            'title'      => Validator::sanitize($this->input('title', '')),
            'comment'    => Validator::sanitize($this->input('comment', '')),
            'is_approved'=> 0,
        ]);

        $this->json(['success' => true, 'message' => 'Avis soumis pour modération.']);
    }

    public function compare(): void
    {
        $ids = array_map('intval', explode(',', $this->input('ids', '')));
        $ids = array_filter($ids);

        if (count($ids) < 2 || count($ids) > 4) {
            $this->json(['error' => 'Sélectionnez entre 2 et 4 produits.'], 400);
        }

        $productModel = new Product();
        $products = [];
        foreach ($ids as $id) {
            $p = $productModel->find($id);
            if ($p) $products[] = $p;
        }

        $this->json(['products' => $products]);
    }
}
