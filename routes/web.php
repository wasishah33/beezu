<?php

use Core\Application;
use App\Middlewares\AuthMiddleware;

// Get the router from the application container
$router = app()->getRouter();




////////////////////  FRONT ROUTES //////////////////////////
// Home routes
$router->get('/', 'HomeController@index');

// API examples
$router->get('/api/ping', 'HomeController@api');

// Users
$router->get('/users', 'UserController@index');
$router->get('/users/{id}', 'UserController@show');




 ////////////////////  ADMIN ROUTES //////////////////////////
$router->get('/admin/login', 'AdminController@showLogin');
$router->middleware([\App\Middlewares\CsrfMiddleware::class])
        ->post('/admin/login', 'AdminController@login');
// Blog routes (admin)
$router->middleware([AuthMiddleware::class])->prefix('/admin')->group(function ($router) {
    // Admin routes (auto-detected for admin layout)
    $router->get('/', 'AdminController@index');
    $router->get('/users', 'AdminController@users');
    $router->get('/settings', 'AdminController@settings');
    $router->get('/api/stats', 'AdminController@apiStats');
    $router->get('/profile', 'AdminController@profile');
    $router->post('/profile', 'AdminController@updateUser');
    $router->post('/profile/password', 'AdminController@changePassword');
    
    ////////////////////  BLOG ROUTES //////////////////////////
    $router->get('/blog', 'BlogController@index');
    // Posts
    $router->get('/blog/posts', 'BlogController@posts');
    $router->get('/blog/posts/create', 'BlogController@postForm');
    $router->post('/blog/posts', 'BlogController@savePost');
    $router->get('/blog/posts/{id}/edit', 'BlogController@postForm');
    $router->post('/blog/posts/{id}', 'BlogController@savePost');
    $router->get('/blog/posts/{id}/delete', 'BlogController@deletePost');

    // Categories
    $router->get('/blog/categories', 'BlogController@categories');
    $router->get('/blog/categories/create', 'BlogController@categoryForm');
    $router->post('/blog/categories', 'BlogController@saveCategory');
    $router->get('/blog/categories/{id}/edit', 'BlogController@categoryForm');
    $router->post('/blog/categories/{id}', 'BlogController@saveCategory');
    $router->get('/blog/categories/{id}/delete', 'BlogController@deleteCategory');

    // Pages
    $router->get('/blog/pages', 'BlogController@pages');
    $router->get('/blog/pages/create', 'BlogController@pageForm');
    $router->post('/blog/pages', 'BlogController@savePage');
    $router->get('/blog/pages/{id}/edit', 'BlogController@pageForm');
    $router->post('/blog/pages/{id}', 'BlogController@savePage');
    $router->get('/blog/pages/{id}/delete', 'BlogController@deletePage');

    $router->get('/logout', 'AdminController@logout');
});





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


