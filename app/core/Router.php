<?php

namespace App\Core;

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => []
    ];

    private $currentRoute = null;

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
        return $this;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
        return $this;
    }

    public function put($path, $callback)
    {
        $this->routes['PUT'][$path] = $callback;
        return $this;
    }

    public function delete($path, $callback)
    {
        $this->routes['DELETE'][$path] = $callback;
        return $this;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = str_replace('/market', '', $path); // Ajusta según tu path base
        if (empty($path)) $path = '/';

        foreach ($this->routes[$method] as $route => $callback) {
            if ($this->matchRoute($route, $path, $params)) {
                $this->currentRoute = $route;
                return $this->call($callback, $params);
            }
        }

        http_response_code(404);
        require __DIR__ . '/../views/404.php';
    }

    private function matchRoute($route, $path, &$params = [])
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route);
        $pattern = '#^' . $pattern . '$#';
        return preg_match($pattern, $path, $params);
    }

    private function call($callback, $params = [])
    {
        if (is_string($callback)) {
            [$controller, $method] = explode('@', $callback);
            $class = 'App\\Controllers\\' . $controller;
            $controller = new $class();
            return $controller->$method(...array_values(array_filter($params, 'is_string', ARRAY_FILTER_USE_KEY)));
        }
        return call_user_func_array($callback, $params);
    }
}
