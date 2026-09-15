<?php

declare(strict_types=1);

// Compatibilité PHP < 8.0 (fonctions non disponibles sur PHP 7.x)
if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        return $needle === '' || strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return $needle === '' || strpos($haystack, $needle) !== false;
    }
}
if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool
    {
        return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
    }
}

function e(?string $str): string
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function formatPrice(float $price): string
{
    return number_format($price, 0, ',', ' ') . ' ' . CURRENCY_SYMBOL;
}

function productImage(array $product, int $index = 0): string
{
    $images = json_decode($product['images'] ?? '[]', true) ?: [];
    $img = $images[$index] ?? 'placeholder.jpg';
    return APP_URL . '/assets/images/products/' . $img;
}

function stars(float $rating): string
{
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $class = $i <= round($rating) ? 'fas fa-star' : ($i - 0.5 <= $rating ? 'fas fa-star-half-alt' : 'far fa-star');
        $html .= '<i class="' . $class . '"></i>';
    }
    return $html;
}

function flashMessages(): void
{
    $success = Session::flash('success');
    $error = Session::flash('error');
    if ($success) echo '<div class="alert alert-success">' . e($success) . '</div>';
    if ($error) echo '<div class="alert alert-error">' . e($error) . '</div>';
}

function activeLink(string $path): string
{
    $current = trim($_GET['url'] ?? '', '/');
    return str_starts_with($current, trim($path, '/')) ? 'active' : '';
}
