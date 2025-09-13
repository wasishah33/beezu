<?php

namespace Core;

class Router
{
    private array $routes = [];
    private Request $request;
    private Response $response;
    private array $currentMiddlewares = [];
    private string $currentPrefix = '';
    private bool $inGroup = false;


    
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
    /**
     * Add GET route
     */
    public function get(string $path, $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }
    
    /**
     * Add POST route
     */
    public function post(string $path, $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }
    
    /**
     * Add PUT route
     */
    public function put(string $path, $handler): self
    {
        return $this->addRoute('PUT', $path, $handler);
    }
    
    /**
     * Add DELETE route
     */
    public function delete(string $path, $handler): self
    {
        return $this->addRoute('DELETE', $path, $handler);
    }
    
    /**
     * Add route with middlewares
     */
    public function middleware(array $middlewares): self
    {
        $this->currentMiddlewares = $middlewares;
        return $this;
    }
    
    /**
     * Add route to collection
     */
    private function addRoute(string $method, string $path, $handler): self
    {
        $normalizedPath = $this->normalizePath($this->currentPrefix . $path);
        $this->routes[] = [
            'method' => $method,
            'path' => $normalizedPath,
            'handler' => $handler,
            'middlewares' => $this->currentMiddlewares
        ];

        // Reset middlewares/prefix only if not in group
        if (!$this->inGroup) {
            $this->currentMiddlewares = [];
            $this->currentPrefix = '';
        }

        return $this;
    }

    /**
     * Group routes with shared options, for example middleware.
     */
    public function prefix(string $prefix): self
    {
        $this->currentPrefix = rtrim($prefix, '/');
        return $this;
    }

    public function group(callable $callback): void
    {
        // Backup current state
        $previousInGroup = $this->inGroup;
        $previousMiddlewares = $this->currentMiddlewares;
        $previousPrefix = $this->currentPrefix;

        $this->inGroup = true;

        $callback($this);

        // Reset after group
        $this->inGroup = $previousInGroup;
        $this->currentMiddlewares = [];
        $this->currentPrefix = '';
    }

    
    /**
     * Dispatch request to appropriate handler
     */
    public function dispatch(): void
    {
        $method = $this->request->getMethod();
        //$path = $this->request->getPath();
        $path = $this->normalizePath($this->request->getPath());
        //echo $method;echo $path;exit;
        // Auto-detect admin layout for admin routes
        if (strpos($path, '/admin') === 0) {
            \Core\View::setLayout('admin');
        } else {
            \Core\View::setLayout('front');
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            $pattern = $this->convertPathToRegex($route['path']);
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Remove full match
                
                // Run route middlewares
                foreach ($route['middlewares'] as $middleware) {
                    $middlewareInstance = new $middleware();
                    $result = $middlewareInstance->handle($this->request, $this->response);
                    if ($result === false) {
                        return;
                    }
                }
                
                // Call handler
                $this->callHandler($route['handler'], $matches);
                return;
            }
        }
        
        // No route found
        $this->response->setStatusCode(404);
        echo "404 - Page Not Found";
    }
    
    /**
     * Convert route path to regex pattern
     */
    private function convertPathToRegex(string $path): string
    {
        // Convert {param} to regex capture group
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Convert route path to normalize Path
     */
    private function normalizePath(string $path): string
    {
        // Keep root "/" as is
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }
        return $path;
    }
    
    
    /**
     * Call route handler
     */
    private function callHandler($handler, array $params): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
        } elseif (is_string($handler)) {
            [$controllerName, $methodName] = explode('@', $handler);
            $controllerClass = "App\\Controllers\\{$controllerName}";
            
            if (!class_exists($controllerClass)) {
                throw new \Exception("Controller {$controllerClass} not found");
            }
            
            $controller = new $controllerClass();
            if (!method_exists($controller, $methodName)) {
                throw new \Exception("Method {$methodName} not found in controller {$controllerClass}");
            }

            call_user_func_array([$controller, $methodName], $params);
        }
    }
}