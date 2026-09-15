<?php

declare(strict_types=1);

class BlogController extends Controller
{
    public function index(): void
    {
        $blogModel = new Blog();
        $page = max(1, (int) $this->input('page', 1));
        $perPage = 6;
        $offset = ($page - 1) * $perPage;

        $this->view('blog/index', [
            'title' => 'Blog - Art\' Afric',
            'posts' => $blogModel->getPublished($perPage, $offset),
            'page'  => $page,
        ]);
    }

    public function show(string $slug): void
    {
        $blogModel = new Blog();
        $post = $blogModel->findBySlug($slug);

        if (!$post) {
            http_response_code(404);
            require APP_PATH . '/views/errors/404.php';
            return;
        }

        $this->view('blog/show', [
            'title' => $post['title'] . ' - Blog Art\' Afric',
            'post'  => $post,
        ]);
    }
}
