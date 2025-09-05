<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'Beezu',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => $_ENV['APP_DEBUG'] === 'true',
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'timezone' => 'UTC',
];