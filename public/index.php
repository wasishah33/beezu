<?php
/**
 * Beezu Framework Entry Point
 */

// Set timezone
date_default_timezone_set('UTC');

// Define paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CORE_PATH', ROOT_PATH . '/core');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('PUBLIC_PATH', __DIR__);

// Composer autoloader
require ROOT_PATH . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

// Error handling
if ($_ENV['APP_DEBUG'] === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Start session
session_start();

// Create application instance
$app = new Core\Application();

// Load configuration
$app->loadConfig();

// Register middlewares
$app->registerMiddlewares();

// Load routes
require ROOT_PATH . '/routes/web.php';

// Handle request
$app->run();