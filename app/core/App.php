<?php

declare(strict_types=1);

class App
{
    private Router $router;

    public function __construct()
    {
        $this->loadCore();
        Session::start();
        $this->router = new Router();
        $this->registerRoutes();
    }

    private function loadCore(): void
    {
        spl_autoload_register(function (string $class): void {
            $paths = [
                APP_PATH . '/core/' . $class . '.php',
                APP_PATH . '/models/' . $class . '.php',
            ];

            foreach ($paths as $path) {
                if (file_exists($path)) {
                    require_once $path;
                    return;
                }
            }
        });

        $coreFiles = glob(APP_PATH . '/core/*.php') ?: [];
        foreach ($coreFiles as $file) {
            require_once $file;
        }

        require_once APP_PATH . '/helpers/functions.php';
    }

    private function registerRoutes(): void
    {
        // Accueil
        $this->router->get('', 'Home');
        $this->router->get('accueil', 'Home');

        // Boutique
        $this->router->get('boutique', 'Shop', 'index');
        $this->router->get('boutique/categorie/{slug}', 'Shop', 'category');
        $this->router->get('produit/{slug}', 'Product', 'show');

        // Panier & Checkout
        $this->router->get('panier', 'Cart', 'index');
        $this->router->post('panier/ajouter', 'Cart', 'add');
        $this->router->post('panier/modifier', 'Cart', 'update');
        $this->router->post('panier/supprimer', 'Cart', 'remove');
        $this->router->post('panier/coupon', 'Cart', 'applyCoupon');
        $this->router->get('commande', 'Checkout', 'index');
        $this->router->post('commande', 'Checkout', 'process');
        $this->router->get('commande/confirmation/{id}', 'Checkout', 'confirmation');

        // Auth
        $this->router->get('connexion', 'Auth', 'loginForm');
        $this->router->post('connexion', 'Auth', 'login');
        $this->router->get('inscription', 'Auth', 'registerForm');
        $this->router->post('inscription', 'Auth', 'register');
        $this->router->get('deconnexion', 'Auth', 'logout');
        $this->router->get('mot-de-passe-oublie', 'Auth', 'forgotForm');
        $this->router->post('mot-de-passe-oublie', 'Auth', 'forgot');

        // Compte
        $this->router->get('mon-compte', 'Account', 'index');
        $this->router->get('mon-compte/commandes', 'Account', 'orders');
        $this->router->get('mon-compte/favoris', 'Account', 'favorites');
        $this->router->get('mon-compte/adresses', 'Account', 'addresses');
        $this->router->post('mon-compte/profil', 'Account', 'updateProfile');

        // Pages statiques
        $this->router->get('contact', 'Contact', 'index');
        $this->router->post('contact', 'Contact', 'send');
        $this->router->get('a-propos', 'About', 'index');
        $this->router->get('blog', 'Blog', 'index');
        $this->router->get('blog/{slug}', 'Blog', 'show');
        $this->router->post('newsletter', 'Newsletter', 'subscribe');

        // API AJAX
        $this->router->get('api/recherche', 'Api', 'search');
        $this->router->post('api/favoris', 'Api', 'toggleFavorite');
        $this->router->get('api/produits', 'Api', 'products');
        $this->router->post('api/avis', 'Api', 'addReview');
        $this->router->get('api/comparer', 'Api', 'compare');

        // Admin
        $this->router->get('admin', 'Admin\\Dashboard', 'index');
        $this->router->get('admin/connexion', 'Admin\\Auth', 'loginForm');
        $this->router->post('admin/connexion', 'Admin\\Auth', 'login');
        $this->router->get('admin/produits', 'Admin\\Product', 'index');
        $this->router->get('admin/produits/ajouter', 'Admin\\Product', 'create');
        $this->router->post('admin/produits/ajouter', 'Admin\\Product', 'store');
        $this->router->get('admin/produits/modifier/{id}', 'Admin\\Product', 'edit');
        $this->router->post('admin/produits/modifier/{id}', 'Admin\\Product', 'update');
        $this->router->post('admin/produits/supprimer/{id}', 'Admin\\Product', 'delete');
        $this->router->get('admin/commandes', 'Admin\\Order', 'index');
        $this->router->get('admin/commandes/{id}', 'Admin\\Order', 'show');
        $this->router->post('admin/commandes/{id}/statut', 'Admin\\Order', 'updateStatus');
        $this->router->get('admin/clients', 'Admin\\Customer', 'index');
        $this->router->get('admin/categories', 'Admin\\Category', 'index');
        $this->router->post('admin/categories', 'Admin\\Category', 'store');
        $this->router->get('admin/coupons', 'Admin\\Coupon', 'index');
        $this->router->post('admin/coupons', 'Admin\\Coupon', 'store');
        $this->router->get('admin/parametres', 'Admin\\Settings', 'index');
        $this->router->post('admin/parametres', 'Admin\\Settings', 'update');
        $this->router->get('admin/profil', 'Admin\\Profile', 'index');
        $this->router->post('admin/profil', 'Admin\\Profile', 'update');
        $this->router->get('admin/partenaires', 'Admin\\Partner', 'index');
        $this->router->post('admin/partenaires', 'Admin\\Partner', 'store');
        $this->router->post('admin/partenaires/{id}/toggle', 'Admin\\Partner', 'toggle');
        $this->router->post('admin/partenaires/{id}/supprimer', 'Admin\\Partner', 'delete');
        $this->router->get('admin/blog', 'Admin\\Blog', 'index');
        $this->router->get('admin/blog/nouveau', 'Admin\\Blog', 'createForm');
        $this->router->post('admin/blog', 'Admin\\Blog', 'store');
        $this->router->get('admin/blog/{id}/modifier', 'Admin\\Blog', 'editForm');
        $this->router->post('admin/blog/{id}/modifier', 'Admin\\Blog', 'update');
        $this->router->post('admin/blog/{id}/supprimer', 'Admin\\Blog', 'delete');
        $this->router->get('admin/messages', 'Admin\\Message', 'index');
        $this->router->get('admin/statistiques', 'Admin\\Dashboard', 'stats');
    }

    public function run(): void
    {
        $url = $_GET['url'] ?? '';
        $this->router->dispatch($url);
    }
}
