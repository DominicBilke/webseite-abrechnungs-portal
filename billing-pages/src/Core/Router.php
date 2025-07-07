<?php

namespace BillingPages\Core;

/**
 * Simple Router Class
 */
class Router
{
    private array $routes = [];
    private array $params = [];

    /**
     * Add a GET route
     */
    public function get(string $path, array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Add a POST route
     */
    public function post(string $path, array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Add a route
     */
    private function addRoute(string $method, string $path, array $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    /**
     * Dispatch the request to the appropriate handler
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove trailing slash
        $path = rtrim($path, '/');
        if (empty($path)) {
            $path = '/';
        }

        // Check if route exists
        if (!isset($this->routes[$method][$path])) {
            // Try to match dynamic routes
            $handler = $this->matchDynamicRoute($method, $path);
            if (!$handler) {
                $this->handleNotFound();
                return;
            }
        } else {
            $handler = $this->routes[$method][$path];
        }

        // Execute the handler
        $this->executeHandler($handler);
    }

    /**
     * Match dynamic routes with parameters
     */
    private function matchDynamicRoute(string $method, string $path): ?array
    {
        if (!isset($this->routes[$method])) {
            return null;
        }

        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = $this->convertRouteToRegex($route);
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Remove the full match
                $this->params = $matches;
                return $handler;
            }
        }

        return null;
    }

    /**
     * Convert route pattern to regex
     */
    private function convertRouteToRegex(string $route): string
    {
        return '#^' . preg_replace('#\{([a-zA-Z0-9]+)\}#', '([^/]+)', $route) . '$#';
    }

    /**
     * Execute the route handler
     */
    private function executeHandler(array $handler): void
    {
        [$controllerClass, $method] = $handler;
        
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller class {$controllerClass} not found");
        }

        $controller = new $controllerClass();
        
        if (!method_exists($controller, $method)) {
            throw new \Exception("Method {$method} not found in controller {$controllerClass}");
        }

        // Pass parameters to the method
        $controller->$method(...$this->params);
    }

    /**
     * Handle 404 Not Found
     */
    private function handleNotFound(): void
    {
        http_response_code(404);
        include __DIR__ . '/../../templates/errors/404.php';
    }
} 