<?php

namespace Core;

class Request
{
    /**
     * Get the HTTP method in upper case.
     */
    public function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Get the request path (without query string), normalized with a leading slash
     * and without a trailing slash (except for the root path "/").
     */
    public function getPath(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

        // Determine base path (directory of the executing script), e.g. "/beezu/public"
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');

        // Strip base path prefix if present
        if ($basePath !== '' && $basePath !== '/' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
            if ($path === false || $path === '') {
                $path = '/';
            }
        }

        // Normalize leading/trailing slashes
        if ($path !== '/') {
            $path = '/' . trim($path, '/');
        }
        return $path;
    }

    /**
     * Get all input values (GET and POST merged).
     */
    public function all(): array
    {
        // Prefer POST overriding GET keys when both exist
        return array_merge($_GET ?? [], $_POST ?? []);
    }

    /**
     * Get a single input value.
     */
    public function input(string $key, $default = null)
    {
        $data = $this->all();
        return $data[$key] ?? $default;
    }

    /**
     * Get a header value (case-insensitive).
     */
    public function header(string $name, $default = null)
    {
        $serverKey = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        if (isset($_SERVER[$serverKey])) {
            return $_SERVER[$serverKey];
        }

        // Some headers are not prefixed with HTTP_
        $alt = strtoupper(str_replace('-', '_', $name));
        if (isset($_SERVER[$alt])) {
            return $_SERVER[$alt];
        }

        return $default;
    }

    /**
     * Determine if the request expects or sends JSON.
     */
    public function isJson(): bool
    {
        $contentType = (string) $this->header('Content-Type', '');
        $accept = (string) $this->header('Accept', '');
        return (stripos($contentType, 'application/json') !== false)
            || (stripos($accept, 'application/json') !== false);
    }

    /**
     * Determine if the request was made via AJAX (XMLHttpRequest).
     */
    public function isAjax(): bool
    {
        return strtolower((string) $this->header('X-Requested-With', '')) === 'xmlhttprequest';
    }
}


