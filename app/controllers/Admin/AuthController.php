<?php

declare(strict_types=1);

namespace Admin;

class AuthController extends \Controller
{
    public function loginForm(): void
    {
        if (\Auth::isAdmin()) {
            $this->redirect(APP_URL . '/admin');
        }
        $this->view('admin/login', ['title' => 'Admin - Connexion'], 'admin');
    }

    public function login(): void
    {
        \CSRF::verify();

        $userModel = new \User();
        $user = $userModel->findByEmail(trim($_POST['email'] ?? ''));

        if (!$user || !in_array($user['role'], ['admin', 'employee'], true) || !password_verify($_POST['password'] ?? '', $user['password'])) {
            \Session::flash('error', 'Identifiants incorrects.');
            $this->redirect(APP_URL . '/admin/connexion');
        }

        \Auth::login($user);
        $this->redirect(APP_URL . '/admin');
    }
}
