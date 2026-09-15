<?php

declare(strict_types=1);

namespace Admin;

class SettingsController extends \Controller
{
    private const KEYS = [
        'footer_description',
        'contact_address',
        'contact_phone',
        'contact_email',
        'social_facebook',
        'social_instagram',
        'social_whatsapp',
        'social_pinterest',
        'whatsapp_order_number',
        'about_story',
        'about_mission',
        'about_vision',
        'about_values',
        'about_team',
        'header_ad_text',
        'header_ad_link',
    ];

    public function index(): void
    {
        \Auth::requireAdmin();

        $this->view('admin/settings/index', [
            'title'    => 'Paramètres du site',
            'settings' => \Settings::getAll(),
        ], 'admin');
    }

    public function update(): void
    {
        \Auth::requireAdmin();
        \CSRF::verify();

        $values = [];
        foreach (self::KEYS as $key) {
            $values[$key] = \Validator::sanitize($_POST[$key] ?? '');
        }

        \Settings::setMany($values);

        \Session::flash('success', 'Paramètres du site mis à jour.');
        $this->redirect(APP_URL . '/admin/parametres');
    }
}
