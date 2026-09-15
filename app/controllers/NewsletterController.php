<?php

declare(strict_types=1);

class NewsletterController extends Controller
{
    public function subscribe(): void
    {
        CSRF::verify();

        $email = filter_var(trim($this->input('email', '')), FILTER_VALIDATE_EMAIL);
        if (!$email) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Email invalide.'], 400);
            }
            Session::flash('error', 'Email invalide.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? APP_URL);
        }

        $db = Database::getInstance();
        try {
            $stmt = $db->prepare("INSERT INTO newsletter_subscribers (email) VALUES (:email)");
            $stmt->execute(['email' => $email]);
        } catch (PDOException) {
            // Already subscribed
        }

        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => 'Inscription réussie !']);
        }

        Session::flash('success', 'Merci pour votre inscription à notre newsletter !');
        $this->redirect($_SERVER['HTTP_REFERER'] ?? APP_URL);
    }
}
