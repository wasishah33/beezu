<?php

use Core\Application;

// Get the router from the application container
$router = app()->getRouter();

////////////////////  FRONT ROUTES //////////////////////////
// Home routes
$router->middleware([\App\Middlewares\AuthMiddleware::class])
->get('/', 'HomeController@index');

// API examples
$router->get('/api/ping', 'HomeController@api');

// Users
$router->get('/users', 'UserController@index');
$router->get('/users/{id}', 'UserController@show');


////////////////////  ADMIN ROUTES //////////////////////////

// Admin routes (auto-detected for admin layout)
$router->get('/admin', 'AdminController@index');
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/settings', 'AdminController@settings');
$router->get('/admin/api/stats', 'AdminController@apiStats');

// Authentication (admin)
$router->get('/admin/login', 'AdminController@showLogin');
$router
    ->middleware([\App\Middlewares\CsrfMiddleware::class, \App\Middlewares\RateLimitMiddleware::class])
    ->post('/admin/login', 'AdminController@login');

$router
    ->middleware([\App\Middlewares\AuthMiddleware::class])
    ->get('/admin/profile', 'AdminController@profile');

$router
    ->middleware([\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class])
    ->post('/admin/profile', 'AdminController@updateUser');

$router
    ->middleware([\App\Middlewares\AuthMiddleware::class, \App\Middlewares\CsrfMiddleware::class])
    ->post('/admin/profile/password', 'AdminController@changePassword');

$router->get('/admin/logout', 'AdminController@logout');


//////////////////// EXAMPLE USAGE //////////////////////////
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


