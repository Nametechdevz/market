<?php

namespace App\Core;

class Request
{
    public string $method;
    public string $path;
    private array $params = [];
    private ?array $jsonBody = null;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->path = rtrim(parse_url($uri, PHP_URL_PATH) ?: '/', '/');
        if ($this->path === '') {
            $this->path = '/';
        }
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function param(string $key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    public function query(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public function input(string $key, $default = null)
    {
        $body = $this->jsonBody();
        if ($body !== null && array_key_exists($key, $body)) {
            return $body[$key];
        }
        return $_POST[$key] ?? $default;
    }

    public function jsonBody(): ?array
    {
        if ($this->jsonBody !== null) {
            return $this->jsonBody;
        }
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') === false) {
            return null;
        }
        $raw = file_get_contents('php://input');
        $decoded = json_decode($raw, true);
        $this->jsonBody = is_array($decoded) ? $decoded : [];
        return $this->jsonBody;
    }

    public function bearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if ($header === '' && function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            $header = $headers['Authorization'] ?? '';
        }
        if (preg_match('/Bearer\s+(\S+)/i', $header, $m)) {
            return $m[1];
        }
        return null;
    }
}
