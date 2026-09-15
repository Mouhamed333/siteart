<?php

declare(strict_types=1);

namespace Admin;

class ProfileController extends \Controller
{
    public function index(): void
    {
        \Auth::requireAdmin();
        $user = (new \User())->find((int) \Auth::id());

        $this->view('admin/profile/index', [
            'title' => 'Mon profil',
            'user'  => $user,
        ], 'admin');
    }

    public function update(): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();

        $userModel = new \User();
        $user = $userModel->find((int) \Auth::id());

        $currentPassword = $_POST['current_password'] ?? '';
        if (!password_verify($currentPassword, $user['password'])) {
            \Session::flash('error', 'Mot de passe actuel incorrect.');
            $this->redirect(APP_URL . '/admin/profil');
        }

        $firstName = \Validator::sanitize($_POST['first_name'] ?? '');
        $lastName = \Validator::sanitize($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($firstName === '' || $lastName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            \Session::flash('error', 'Merci de renseigner un nom, prénom et email valides.');
            $this->redirect(APP_URL . '/admin/profil');
        }

        $existing = $userModel->findByEmail($email);
        if ($existing && (int) $existing['id'] !== (int) $user['id']) {
            \Session::flash('error', 'Cet email est déjà utilisé par un autre compte.');
            $this->redirect(APP_URL . '/admin/profil');
        }

        $data = [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
        ];

        $newPassword = $_POST['new_password'] ?? '';
        if ($newPassword !== '') {
            if (strlen($newPassword) < 8) {
                \Session::flash('error', 'Le nouveau mot de passe doit contenir au moins 8 caractères.');
                $this->redirect(APP_URL . '/admin/profil');
            }
            if ($newPassword !== ($_POST['new_password_confirm'] ?? '')) {
                \Session::flash('error', 'Les deux mots de passe ne correspondent pas.');
                $this->redirect(APP_URL . '/admin/profil');
            }
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $userModel->update((int) $user['id'], $data);

        // Met à jour la session avec les nouvelles infos (email affiché, etc.)
        \Auth::login(array_merge($user, $data));

        \Session::flash('success', 'Profil mis à jour avec succès.');
        $this->redirect(APP_URL . '/admin/profil');
    }
}
