<?php

use Core\Application;

// Get the router from the application container
$router = app()->getRouter();

// Home routes
$router->get('/', 'HomeController@index');

// API examples
$router->get('/api/ping', 'HomeController@api');

// Users
$router->get('/users', 'UserController@index');
$router->get('/users/{id}', 'UserController@show');

// Admin routes (auto-detected for admin layout)
$router->get('/admin', 'AdminController@index');
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/settings', 'AdminController@settings');
$router->get('/admin/api/stats', 'AdminController@apiStats');

// Create user (protected by CSRF + RateLimit)
$router
    ->middleware([
        \App\Middlewares\CsrfMiddleware::class,
        \App\Middlewares\RateLimitMiddleware::class,
    ])
    ->post('/users', 'UserController@store');

// Update user (protected by CSRF + RateLimit)
$router
    ->middleware([
        \App\Middlewares\CsrfMiddleware::class,
        \App\Middlewares\RateLimitMiddleware::class,
    ])
    ->put('/users/{id}', 'UserController@update');

// Delete user (protected by CSRF + RateLimit)
$router
    ->middleware([
        \App\Middlewares\CsrfMiddleware::class,
        \App\Middlewares\RateLimitMiddleware::class,
    ])
    ->delete('/users/{id}', 'UserController@destroy');

// ------------------------------------------------------------------
// Additional example routes compatible with current code/middlewares
// ------------------------------------------------------------------

// Simple healthcheck (callable handler)
$router->get('/health', function () {
    echo 'OK';
});

// Rate-limited, JSON endpoint example
$router
    ->middleware([\App\Middlewares\RateLimitMiddleware::class])
    ->get('/api/limited', function () {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok', 'limited' => true, 'time' => time()]);
    });

// CSRF-protected form submit example
$router
    ->middleware([\App\Middlewares\CsrfMiddleware::class])
    ->post('/contact/submit', function () {
        echo 'Form submitted successfully';
    });


