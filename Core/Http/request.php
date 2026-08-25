<?php

namespace Core\Http;

class Request
{
    private array $body = [];
    private string $method;
    private string $path;

    public function __construct()
    {
        // Get and normalize the HTTP request method
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);

        // Extract the path component from the request URI and ignore query parameters
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Collect and sanitize the request input based on the HTTP method
        $this->sanitizeInput();
    }

    public function getMethod(): string
    {
        // Return the HTTP method of the current request
        return $this->method;
    }

    public function getPath(): string
    {
        // Return the path of the current request
        return $this->path;
    }

    public function all(): array
    {
        // Return all sanitized input from the current request
        return $this->body;
    }

    public function get(string $key, $default = null)
    {
        // Return the requested input value or the default value when the key does not exist
        return $this->body[$key] ?? $default;
    }

    private function sanitizeInput()
    {
        // Collect and sanitize GET parameters
        if ($this->method === 'GET') 
        {
            foreach ($_GET as $key => $value) 
            {
                $this->body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        // Collect and sanitize POST parameters
        if ($this->method === 'POST') 
        {
            foreach ($_POST as $key => $value) 
            {
                $this->body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
    }
}
