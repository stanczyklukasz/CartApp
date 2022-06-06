<?php

declare(strict_types=1);

use App\Shared\Infrastructure\SymfonyKernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new SymfonyKernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
