<?php

declare(strict_types=1);

class ContactController extends Controller
{
    public function index(): void
    {
        $this->view('contact/index', ['title' => 'Contact - Art\' Afric']);
    }

    public function send(): void
    {
        CSRF::verify();

        $validator = new Validator($_POST);
        $validator->required('name')->required('email')->email('email')
            ->required('subject')->required('message');

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect(APP_URL . '/contact');
        }

        $db = Database::getInstance();
        $stmt = $db->prepare(
            "INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (:name, :email, :phone, :subject, :message)"
        );
        $stmt->execute([
            'name'    => Validator::sanitize($_POST['name']),
            'email'   => Validator::sanitize($_POST['email']),
            'phone'   => Validator::sanitize($_POST['phone'] ?? ''),
            'subject' => Validator::sanitize($_POST['subject']),
            'message' => Validator::sanitize($_POST['message']),
        ]);

        Session::flash('success', 'Message envoyé ! Nous vous répondrons sous 24h.');
        $this->redirect(APP_URL . '/contact');
    }
}
