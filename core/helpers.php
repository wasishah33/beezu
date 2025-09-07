<?php

use Core\Application;
use Core\Session;

/**
 * Get application instance
 */
function app(): Application
{
    return Application::getInstance();
}

/**
 * Get configuration value
 */
function config(string $key, $default = null)
{
    return app()->config($key, $default);
}

/**
 * Include view
 */
function partial(string $view, array $data = [])
{

    return Application::$app->view->partial($view, $data);
}

/**
 * Generate URL
 */
function url(string $path = ''): string
{
    $baseUrl = $_ENV['APP_URL'] ?? 'http://localhost';
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}


/**
 * Generate asset URL
 */
function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Redirect to URL
 */
function redirect(string $url, int $code = 302): void
{
    app()->getResponse()->redirect($url, $code);
}

/**
 * Get request instance
 */
function request(): Core\Request
{
    return app()->getRequest();
}

/**
 * Get old input value
 */
function old(string $key, $default = null)
{
    return Session::get('old_input.' . $key, $default);
}

/**
 * CSRF token field
 */
function csrf_field(): string
{
    $token = Session::get('csrf_token');
    if (!$token) {
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
    }
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Get CSRF token
 */
function csrf_token(): string
{
    $token = Session::get('csrf_token');
    if (!$token) {
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
    }
    return $token;
}

/**
 * Escape output
 */
function e($value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Dump and die
 */
function dd(...$vars): void
{
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}

/**
 * Get environment variable
 */
function env(string $key, $default = null)
{
    return $_ENV[$key] ?? $default;
}

/**
 * Check if user is authenticated
 */
function auth(): bool
{
    return Session::has('user_id');
}

/**
 * Get authenticated user ID
 */
function auth_id(): ?int
{
    return Session::get('user_id');
}
