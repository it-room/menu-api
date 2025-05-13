<?php

use App\Kernel;
// Disable HTTPS redirection - stay on HTTP only
$_SERVER['HTTPS'] = 'off';
$_SERVER['SERVER_PORT'] = 80;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
