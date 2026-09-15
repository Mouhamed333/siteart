<?php

declare(strict_types=1);

class Router
{
    private array $routes = [];

    public function get(string $path, string $controller, string $method = 'index'): void
    {
        $this->addRoute('GET', $path, $controller, $method);
    }

    public function post(string $path, string $controller, string $method = 'index'): void
    {
        $this->addRoute('POST', $path, $controller, $method);
    }

    private function addRoute(string $httpMethod, string $path, string $controller, string $method): void
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method'     => $httpMethod,
            'pattern'    => $pattern,
            'controller' => $controller,
            'action'     => $method,
        ];
    }

    public function dispatch(string $url): void
    {
        $url = trim($url, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $url, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->callController($route['controller'], $route['action'], $params);
                return;
            }
        }

        http_response_code(404);
        require APP_PATH . '/views/errors/404.php';
    }

    private function callController(string $controller, string $action, array $params): void
    {
        $class = $controller . 'Controller';
        $file = APP_PATH . '/controllers/' . str_replace('\\', '/', $controller) . 'Controller.php';

        if (!file_exists($file)) {
            throw new RuntimeException("Contrôleur introuvable : {$class}");
        }

        require_once $file;

        if (!class_exists($class)) {
            throw new RuntimeException("Classe introuvable : {$class}");
        }

        $instance = new $class();

        if (!method_exists($instance, $action)) {
            throw new RuntimeException("Méthode introuvable : {$class}::{$action}");
        }

        call_user_func_array([$instance, $action], $params);
    }
}
