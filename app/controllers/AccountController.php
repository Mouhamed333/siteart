<?php

declare(strict_types=1);

class AccountController extends Controller
{
    public function __construct()
    {
        Auth::requireAuth();
    }

    public function index(): void
    {
        $this->view('account/index', [
            'title' => 'Mon Compte - Art\' Afric',
            'user'  => Auth::user(),
        ]);
    }

    public function orders(): void
    {
        $orderModel = new Order();
        $this->view('account/orders', [
            'title'  => 'Mes Commandes - Art\' Afric',
            'orders' => $orderModel->getByUser(Auth::id()),
        ]);
    }

    public function favorites(): void
    {
        $favoriteModel = new Favorite();
        $this->view('account/favorites', [
            'title'     => 'Mes Favoris - Art\' Afric',
            'favorites' => $favoriteModel->getByUser(Auth::id()),
        ]);
    }

    public function addresses(): void
    {
        $this->view('account/addresses', [
            'title' => 'Mes Adresses - Art\' Afric',
        ]);
    }

    public function updateProfile(): void
    {
        CSRF::verify();

        $userModel = new User();
        $userModel->update(Auth::id(), [
            'first_name' => Validator::sanitize($_POST['first_name']),
            'last_name'  => Validator::sanitize($_POST['last_name']),
            'phone'      => Validator::sanitize($_POST['phone'] ?? ''),
        ]);

        $user = $userModel->find(Auth::id());
        Auth::login($user);

        Session::flash('success', 'Profil mis à jour.');
        $this->redirect(APP_URL . '/mon-compte');
    }
}
