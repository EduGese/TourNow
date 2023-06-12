<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Carga las variables de entorno
(new Dotenv())->loadEnv(dirname(__DIR__).'/.env');

// Establece la zona horaria
date_default_timezone_set('Europe/Madrid');

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
