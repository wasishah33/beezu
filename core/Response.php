<?php

namespace Core;

class Response
{
    private int $statusCode = 200;
    private array $headers = [];

    /**
     * Set HTTP status code.
     */
    public function setStatusCode(int $code): void
    {
        $this->statusCode = $code;
        http_response_code($code);
    }

    /**
     * Get current status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Set a response header.
     */
    public function setHeader(string $name, string $value, bool $replace = true): void
    {
        $this->headers[$name] = $value;
        header($name . ': ' . $value, $replace, $this->statusCode);
    }

    /**
     * Redirect to URL with status code.
     */
    public function redirect(string $url, int $code = 302): void
    {
        $this->setStatusCode($code);
        header('Location: ' . $url, true, $code);
        exit;
    }

    /**
     * Send JSON response.
     */
    public function json($data, int $code = 200): void
    {
        $this->setStatusCode($code);
        $this->setHeader('Content-Type', 'application/json');
        echo json_encode($data);
    }
}


