<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array|callable $handler, array $middleware = []): self
    {
        return $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, array|callable $handler, array $middleware = []): self
    {
        return $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function any(string $path, array|callable $handler, array $middleware = []): self
    {
        $this->addRoute('GET', $path, $handler, $middleware);
        return $this->addRoute('POST', $path, $handler, $middleware);
    }

    private function addRoute(string $method, string $path, array|callable $handler, array $middleware = []): self
    {
        $normalizedPath = '/' . trim($path, '/');
        if ($normalizedPath !== '/' && str_ends_with($normalizedPath, '/')) {
            $normalizedPath = rtrim($normalizedPath, '/');
        }

        // Convert {param} placeholders to regex named capture groups
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $normalizedPath);
        $regex = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method' => $method,
            'path' => $normalizedPath,
            'regex' => $regex,
            'handler' => $handler,
            'middleware' => $middleware,
        ];

        return $this;
    }

    public function dispatch(Request $request): void
    {
        $requestMethod = $request->getMethod();
        $requestPath = $request->getPath();

        // Support _method override for forms
        if ($requestMethod === 'POST' && $request->post('_method')) {
            $requestMethod = strtoupper((string)$request->post('_method'));
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            if (preg_match($route['regex'], $requestPath, $matches)) {
                // Extract named params
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = urldecode($value);
                    }
                }

                // Run middleware pipeline
                foreach ($route['middleware'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    $result = $middleware->handle($request);
                    if ($result === false) {
                        return; // Middleware aborted / redirected
                    }
                }

                // Call handler
                $handler = $route['handler'];
                if (is_callable($handler)) {
                    $handler($request, $params);
                    return;
                }

                if (is_array($handler) && count($handler) === 2) {
                    [$controllerClass, $action] = $handler;
                    if (!class_exists($controllerClass)) {
                        throw new \RuntimeException("Controller not found: {$controllerClass}");
                    }
                    $controller = new $controllerClass();
                    if (!method_exists($controller, $action)) {
                        throw new \RuntimeException("Action {$action} not found in {$controllerClass}");
                    }
                    $controller->$action($request, $params);
                    return;
                }
            }
        }

        // If no route matches: 404
        http_response_code(404);
        try {
            echo View::render('storefront/404', ['title' => 'Page Not Found - HOMEIQ']);
        } catch (\Throwable $e) {
            echo "<h1>404 Not Found</h1><p>The page you requested could not be found.</p>";
        }
    }
}

