<?php

declare(strict_types=1);

namespace Admin;

class BlogController extends \Controller
{
    public function index(): void
    {
        \Auth::requireAdmin();
        $stmt = \Database::getInstance()->query(
            "SELECT b.*, u.first_name, u.last_name FROM blog_posts b
             JOIN users u ON b.author_id = u.id ORDER BY b.created_at DESC"
        );

        $this->view('admin/blog/index', [
            'title' => 'Blog',
            'posts' => $stmt->fetchAll(),
        ], 'admin');
    }

    public function createForm(): void
    {
        \Auth::requireAdmin();
        $this->view('admin/blog/form', [
            'title' => 'Nouvel article',
            'post'  => null,
        ], 'admin');
    }

    public function editForm(string $id): void
    {
        \Auth::requireAdmin();
        $post = (new \Blog())->find((int) $id);
        if (!$post) {
            http_response_code(404);
            require APP_PATH . '/views/errors/404.php';
            return;
        }
        $this->view('admin/blog/form', [
            'title' => 'Modifier l\'article',
            'post'  => $post,
        ], 'admin');
    }

    public function store(): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();

        $title = \Validator::sanitize($_POST['title'] ?? '');
        if ($title === '') {
            \Session::flash('error', 'Le titre est requis.');
            $this->redirect(APP_URL . '/admin/blog/nouveau');
        }

        (new \Blog())->create([
            'author_id'    => \Auth::id(),
            'title'        => $title,
            'slug'         => $this->slugify($title),
            'excerpt'      => \Validator::sanitize($_POST['excerpt'] ?? ''),
            'content'      => $_POST['content'] ?? '',
            'category'     => \Validator::sanitize($_POST['category'] ?? 'Culture'),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
            'published_at' => isset($_POST['is_published']) ? date('Y-m-d H:i:s') : null,
        ]);

        \Session::flash('success', 'Article créé.');
        $this->redirect(APP_URL . '/admin/blog');
    }

    public function update(string $id): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();

        $blogModel = new \Blog();
        $existing = $blogModel->find((int) $id);
        if (!$existing) {
            http_response_code(404);
            require APP_PATH . '/views/errors/404.php';
            return;
        }

        $wasPublished = (bool) $existing['is_published'];
        $isPublished = isset($_POST['is_published']);

        $blogModel->update((int) $id, [
            'title'        => \Validator::sanitize($_POST['title'] ?? $existing['title']),
            'excerpt'      => \Validator::sanitize($_POST['excerpt'] ?? ''),
            'content'      => $_POST['content'] ?? $existing['content'],
            'category'     => \Validator::sanitize($_POST['category'] ?? 'Culture'),
            'is_published' => $isPublished ? 1 : 0,
            'published_at' => (!$wasPublished && $isPublished) ? date('Y-m-d H:i:s') : $existing['published_at'],
        ]);

        \Session::flash('success', 'Article mis à jour.');
        $this->redirect(APP_URL . '/admin/blog');
    }

    public function delete(string $id): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();

        (new \Blog())->delete((int) $id);
        \Session::flash('success', 'Article supprimé.');
        $this->redirect(APP_URL . '/admin/blog');
    }

    private function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-') . '-' . substr(uniqid(), -4);
    }
}
