<?php

namespace App\Core;

use Exception;

class Router {
    protected array $routes = [];
    protected array $groupStack = [];

    public function get(string $uri, mixed $action, array $middlewares = []): self {
        return $this->addRoute('GET', $uri, $action, $middlewares);
    }

    public function post(string $uri, mixed $action, array $middlewares = []): self {
        return $this->addRoute('POST', $uri, $action, $middlewares);
    }

    public function group(array $attributes, callable $callback): void {
        $this->groupStack[] = $attributes;
        $callback($this);
        array_pop($this->groupStack);
    }

    protected function addRoute(string $method, string $uri, mixed $action, array $middlewares = []): self {
        $prefix = '';
        $groupMiddlewares = [];

        foreach ($this->groupStack as $group) {
            if (isset($group['prefix'])) {
                $prefix .= '/' . trim($group['prefix'], '/');
            }
            if (isset($group['middleware'])) {
                $m = is_array($group['middleware']) ? $group['middleware'] : [$group['middleware']];
                $groupMiddlewares = array_merge($groupMiddlewares, $m);
            }
        }

        $fullUri = rtrim($prefix, '/') . '/' . ltrim($uri, '/');
        $fullUri = trim($fullUri, '/');
        $fullUri = $fullUri === '' ? '/' : '/' . $fullUri;

        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $fullUri);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method'      => $method,
            'uri'         => $fullUri,
            'pattern'     => $pattern,
            'action'      => $action,
            'middlewares' => array_merge($groupMiddlewares, $middlewares)
        ];

        return $this;
    }

    public function dispatch(Request $request): void {
        $method = $request->method();
        $uri = $request->uri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                // Execute Middlewares
                foreach ($route['middlewares'] as $middlewareClass) {
                    if (class_exists($middlewareClass)) {
                        $middleware = new $middlewareClass();
                        if (method_exists($middleware, 'handle')) {
                            $middleware->handle($request);
                        }
                    }
                }

                // Extract named params
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                $action = $route['action'];

                if (is_callable($action)) {
                    call_user_func_array($action, array_values($params));
                    return;
                }

                if (is_array($action) && count($action) === 2) {
                    [$controllerClass, $methodName] = $action;
                    if (!class_exists($controllerClass)) {
                        throw new Exception("Controller không tồn tại: {$controllerClass}");
                    }
                    $controller = new $controllerClass();
                    if (!method_exists($controller, $methodName)) {
                        throw new Exception("Action không tồn tại: {$controllerClass}::{$methodName}");
                    }
                    call_user_func_array([$controller, $methodName], array_values($params));
                    return;
                }
            }
        }

        // Không tìm thấy route -> 404
        throw new Exception("Không tìm thấy trang yêu cầu: [{$method}] {$uri}", 404);
    }
}

