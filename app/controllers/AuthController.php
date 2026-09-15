<?php

declare(strict_types=1);

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            $this->redirect(APP_URL . '/mon-compte');
        }
        $this->view('auth/login', ['title' => 'Connexion - Art\' Afric']);
    }

    public function login(): void
    {
        CSRF::verify();

        $email = trim($this->input('email', ''));
        $password = $this->input('password', '');

        if (!Auth::checkLoginAttempts($email)) {
            Session::flash('error', 'Trop de tentatives. Réessayez dans 15 minutes.');
            $this->redirect(APP_URL . '/connexion');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Auth::recordLoginAttempt($email, false);
            $userModel->logLogin(0, $email, false);
            Session::flash('error', 'Email ou mot de passe incorrect.');
            $this->redirect(APP_URL . '/connexion');
        }

        Auth::recordLoginAttempt($email, true);
        $userModel->logLogin((int) $user['id'], $email, true);
        Auth::login($user);

        $redirect = $user['role'] === 'admin' ? APP_URL . '/admin' : APP_URL . '/mon-compte';
        $this->redirect($redirect);
    }

    public function registerForm(): void
    {
        $this->view('auth/register', ['title' => 'Inscription - Art\' Afric']);
    }

    public function register(): void
    {
        CSRF::verify();

        $validator = new Validator($_POST);
        $validator->required('first_name')->required('last_name')
            ->required('email')->email('email')
            ->required('password')->min('password', 8)
            ->match('password', 'password_confirm', 'Les mots de passe ne correspondent pas.');

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/inscription');
        }

        $userModel = new User();
        if ($userModel->findByEmail($_POST['email'])) {
            Session::flash('error', 'Cet email est déjà utilisé.');
            $this->redirect(APP_URL . '/inscription');
        }

        $userId = $userModel->register([
            'first_name' => Validator::sanitize($_POST['first_name']),
            'last_name'  => Validator::sanitize($_POST['last_name']),
            'email'      => Validator::sanitize($_POST['email']),
            'password'   => $_POST['password'],
            'phone'      => Validator::sanitize($_POST['phone'] ?? ''),
        ]);

        $user = $userModel->find($userId);
        Auth::login($user);

        Session::flash('success', 'Bienvenue chez Art\' Afric !');
        $this->redirect(APP_URL . '/mon-compte');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect(APP_URL);
    }

    public function forgotForm(): void
    {
        $this->view('auth/forgot', ['title' => 'Mot de passe oublié - Art\' Afric']);
    }

    public function forgot(): void
    {
        CSRF::verify();
        Session::flash('success', 'Si cet email existe, un lien de réinitialisation a été envoyé.');
        $this->redirect(APP_URL . '/connexion');
    }
}
