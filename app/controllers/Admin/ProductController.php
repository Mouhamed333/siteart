<?php

declare(strict_types=1);

namespace Admin;

class ProductController extends \Controller
{
    public function index(): void
    {
        \Auth::requireAdmin();
        $productModel = new \Product();

        $this->view('admin/products/index', [
            'title'    => 'Gestion Produits',
            'products' => $productModel->all(),
        ], 'admin');
    }

    public function create(): void
    {
        \Auth::requireAdmin();
        $categoryModel = new \Category();

        $this->view('admin/products/form', [
            'title'      => 'Ajouter un produit',
            'product'    => null,
            'categories' => $categoryModel->getActive(),
        ], 'admin');
    }

    public function store(): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();

        $productModel = new \Product();
        $slug = $this->slugify($_POST['name']);
        $images = $this->handleImageUpload();

        $productModel->create([
            'category_id'       => (int) $_POST['category_id'],
            'name'              => \Validator::sanitize($_POST['name']),
            'slug'              => $slug,
            'description'       => $_POST['description'] ?? '',
            'short_description' => \Validator::sanitize($_POST['short_description'] ?? ''),
            'price'             => (float) $_POST['price'],
            'sale_price'        => !empty($_POST['sale_price']) ? (float) $_POST['sale_price'] : null,
            'sku'               => \Validator::sanitize($_POST['sku'] ?? ''),
            'stock'             => (int) ($_POST['stock'] ?? 0),
            'material'          => \Validator::sanitize($_POST['material'] ?? ''),
            'color'             => \Validator::sanitize($_POST['color'] ?? ''),
            'sizes'             => json_encode($this->parseSizes($_POST['sizes'] ?? '')),
            'images'            => json_encode($images ?: ['placeholder.jpg']),
            'is_featured'       => isset($_POST['is_featured']) ? 1 : 0,
            'is_new'            => isset($_POST['is_new']) ? 1 : 0,
            'is_promo'          => isset($_POST['is_promo']) ? 1 : 0,
        ]);

        \Session::flash('success', 'Produit créé.');
        $this->redirect(APP_URL . '/admin/produits');
    }

    public function edit(string $id): void
    {
        \Auth::requireAdmin();
        $productModel = new \Product();
        $categoryModel = new \Category();

        $this->view('admin/products/form', [
            'title'      => 'Modifier le produit',
            'product'    => $productModel->find((int) $id),
            'categories' => $categoryModel->getActive(),
        ], 'admin');
    }

    public function update(string $id): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();

        $productModel = new \Product();
        $data = [
            'category_id'       => (int) $_POST['category_id'],
            'name'              => \Validator::sanitize($_POST['name']),
            'description'       => $_POST['description'] ?? '',
            'short_description' => \Validator::sanitize($_POST['short_description'] ?? ''),
            'price'             => (float) $_POST['price'],
            'sale_price'        => !empty($_POST['sale_price']) ? (float) $_POST['sale_price'] : null,
            'stock'             => (int) ($_POST['stock'] ?? 0),
            'material'          => \Validator::sanitize($_POST['material'] ?? ''),
            'color'             => \Validator::sanitize($_POST['color'] ?? ''),
            'sizes'             => json_encode($this->parseSizes($_POST['sizes'] ?? '')),
            'is_featured'       => isset($_POST['is_featured']) ? 1 : 0,
            'is_new'            => isset($_POST['is_new']) ? 1 : 0,
            'is_promo'          => isset($_POST['is_promo']) ? 1 : 0,
        ];

        $images = $this->handleImageUpload();
        if (!empty($images)) {
            $data['images'] = json_encode($images);
        }

        $productModel->update((int) $id, $data);

        \Session::flash('success', 'Produit mis à jour.');
        $this->redirect(APP_URL . '/admin/produits');
    }

    /**
     * Transforme la chaîne "S,M,L" saisie dans le formulaire en tableau propre,
     * en retirant les espaces et les entrées vides. Retourne un tableau vide
     * (et non [""]) quand le champ est laissé vide, pour ne pas afficher un
     * sélecteur de taille obligatoire sans aucune option valide sur la fiche produit.
     */
    private function parseSizes(string $raw): array
    {
        $parts = array_map('trim', explode(',', $raw));
        return array_values(array_filter($parts, fn($s) => $s !== ''));
    }

    /**
     * Traite les fichiers envoyés via le champ images[] et retourne
     * la liste des noms de fichiers enregistrés dans UPLOAD_PATH.
     * Retourne un tableau vide si aucun fichier valide n'a été envoyé.
     * Les erreurs rencontrées sont stockées dans un message flash
     * pour que l'admin comprenne pourquoi une image n'a pas été prise en compte.
     */
    private function handleImageUpload(): array
    {
        if (empty($_FILES['images']) || empty($_FILES['images']['name'][0])) {
            return [];
        }

        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
        $saved = [];
        $errors = [];

        if (!is_dir(UPLOAD_PATH)) {
            mkdir(UPLOAD_PATH, 0755, true);
        }
        if (!is_writable(UPLOAD_PATH)) {
            \Session::flash('error', "Le dossier d'upload n'est pas accessible en écriture : " . UPLOAD_PATH);
            return [];
        }

        $count = count($_FILES['images']['name']);
        for ($i = 0; $i < $count; $i++) {
            $originalName = $_FILES['images']['name'][$i];
            if ($originalName === '') {
                continue;
            }

            $error = $_FILES['images']['error'][$i];
            if ($error !== UPLOAD_ERR_OK) {
                $errors[] = $originalName . ' : ' . $this->uploadErrorMessage($error);
                continue;
            }

            $tmpPath = $_FILES['images']['tmp_name'][$i];
            $size = $_FILES['images']['size'][$i];
            if ($size <= 0) {
                $errors[] = $originalName . ' : fichier vide.';
                continue;
            }
            if ($size > MAX_UPLOAD_SIZE) {
                $errors[] = $originalName . ' : trop volumineux (max ' . (int)(MAX_UPLOAD_SIZE / 1024 / 1024) . ' Mo).';
                continue;
            }

            $mime = function_exists('mime_content_type') ? mime_content_type($tmpPath) : null;
            if (!$mime || !isset($allowed[$mime])) {
                $errors[] = $originalName . ' : format non supporté (JPEG, PNG, WEBP ou GIF uniquement).';
                continue;
            }

            $filename = uniqid('prod_', true) . '.' . $allowed[$mime];
            if (move_uploaded_file($tmpPath, UPLOAD_PATH . '/' . $filename)) {
                $saved[] = $filename;
            } else {
                $errors[] = $originalName . " : échec de l'enregistrement sur le serveur.";
            }
        }

        if (!empty($errors)) {
            \Session::flash('error', 'Certaines images n\'ont pas pu être ajoutées : ' . implode(' | ', $errors));
        }

        return $saved;
    }

    private function uploadErrorMessage(int $error): string
    {
        switch ($error) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'fichier trop volumineux pour la configuration du serveur (upload_max_filesize/post_max_size dans php.ini)';
            case UPLOAD_ERR_PARTIAL:
                return 'envoi interrompu, réessayez';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'dossier temporaire manquant sur le serveur';
            case UPLOAD_ERR_CANT_WRITE:
                return 'impossible d\'écrire le fichier sur le disque';
            case UPLOAD_ERR_EXTENSION:
                return 'upload bloqué par une extension PHP';
            default:
                return 'erreur inconnue (code ' . $error . ')';
        }
    }

    public function delete(string $id): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();
        (new \Product())->delete((int) $id);
        \Session::flash('success', 'Produit supprimé.');
        $this->redirect(APP_URL . '/admin/produits');
    }

    private function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-') . '-' . substr(uniqid(), -4);
    }
}
