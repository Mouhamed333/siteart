<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/app/config/config.php';
require_once APP_PATH . '/core/App.php';

$app = new App();
$app->run();
