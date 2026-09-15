<?php

declare(strict_types=1);

namespace Admin;

class MessageController extends \Controller
{
    public function index(): void
    {
        \Auth::requireAdmin();
        $db = \Database::getInstance();
        $messages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();

        $this->view('admin/messages/index', [
            'title'    => 'Messages',
            'messages' => $messages,
        ], 'admin');
    }
}
