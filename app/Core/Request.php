<?php

namespace App\Core;

class Request {
    protected array $get;
    protected array $post;
    protected array $server;
    protected array $files;

    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
    }

    public function method(): string {
        $method = strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
        if ($method === 'POST' && isset($this->post['_method'])) {
            return strtoupper($this->post['_method']);
        }
        return $method;
    }

    public function uri(): string {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $trimmed = trim($path, '/');
        return $trimmed === '' ? '/' : '/' . $trimmed;
    }

    public function all(): array {
        return array_merge($this->get, $this->post);
    }

    public function input(string $key, mixed $default = null): mixed {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function query(string $key, mixed $default = null): mixed {
        return $this->get[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed {
        return $this->post[$key] ?? $default;
    }

    public function file(string $key): ?array {
        return $this->files[$key] ?? null;
    }

    public function isAjax(): bool {
        return (!empty($this->server['HTTP_X_REQUESTED_WITH']) &&
            strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }

    public function isPost(): bool {
        return $this->method() === 'POST';
    }

    public function isGet(): bool {
        return $this->method() === 'GET';
    }
}

