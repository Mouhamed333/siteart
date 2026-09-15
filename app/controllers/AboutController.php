<?php

declare(strict_types=1);

class AboutController extends Controller
{
    public function index(): void
    {
        $this->view('about/index', ['title' => 'À propos - Art\' Afric']);
    }}
