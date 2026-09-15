<?php

declare(strict_types=1);

define('APP_NAME', "Art' Afric");
define('APP_URL', 'http://localhost/art-afric/public');
define('APP_ROOT', dirname(__DIR__, 2));
define('APP_PATH', APP_ROOT . '/app');
define('PUBLIC_PATH', APP_ROOT . '/public');

define('DB_HOST', 'localhost');
define('DB_NAME', 'art_afric');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('SESSION_LIFETIME', 7200);
define('CSRF_TOKEN_NAME', '_csrf_token');
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900);


define('DEFAULT_LANG', 'fr');
define('DEFAULT_CURRENCY', 'XOF');
define('CURRENCY_SYMBOL', 'FCFA');

define('UPLOAD_PATH', PUBLIC_PATH . '/assets/images/products');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);

date_default_timezone_set('Africa/Dakar');

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', APP_ROOT . '/storage/logs/error.log');
