<?php

namespace Core;

class Application
{
    private static ?Application $instance = null;
    private Router $router;
    private Request $request;
    private Response $response;
    private Database $database;
    private array $config = [];
    private array $middlewares = [];
    
    public function __construct()
    {
        self::$instance = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }
    
    /**
     * Get application instance
     */
    public static function getInstance(): Application
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Load configuration files
     */
    public function loadConfig(): void
    {
        $configFiles = glob(APP_PATH . '/config/*.php');
        foreach ($configFiles as $file) {
            $key = basename($file, '.php');
            $this->config[$key] = require $file;
        }
    }
    
    /**
     * Get configuration value
     */
    public function config(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    /**
     * Register global middlewares
     */
    public function registerMiddlewares(): void
    {
        // Register CSRF middleware
        $this->addMiddleware(new \App\Middlewares\CsrfMiddleware());
        
        // Register rate limiting middleware
        $this->addMiddleware(new \App\Middlewares\RateLimitMiddleware());
    }
    
    /**
     * Add middleware
     */
    public function addMiddleware(Middleware $middleware): void
    {
        $this->middlewares[] = $middleware;
    }
    
    /**
     * Run the application
     */
    public function run(): void
    {
        try {
            // Run global middlewares
            foreach ($this->middlewares as $middleware) {
                $result = $middleware->handle($this->request, $this->response);
                if ($result === false) {
                    return;
                }
            }
            
            // Dispatch route
            $this->router->dispatch();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * Handle exceptions
     */
    private function handleException(\Exception $e): void
    {
        if ($_ENV['APP_DEBUG'] === 'true') {
            echo "<h1>Error</h1>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
            echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        } else {
            $this->response->setStatusCode(500);
            echo "Internal Server Error";
        }
    }
    
    // Getters
    public function getRouter(): Router { return $this->router; }
    public function getRequest(): Request { return $this->request; }
    public function getResponse(): Response { return $this->response; }
    
    /**
     * Get database connection
     */
    public function getDatabase(): Database
    {
        if (!isset($this->database)) {
            $this->database = new Database($this->config('database'));
        }
        return $this->database;
    }
}