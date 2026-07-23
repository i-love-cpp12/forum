<?php
declare(strict_types=1);

namespace src\Infrastructure\Http;

use OutOfBoundsException;

class Request
{
    readonly public array $headers;
    readonly public array $body;
    readonly public string $method; 
    readonly public string $uri;
    private array $state;
    
    public function __construct()
    {
        $this->method = strtoupper($_SERVER["REQUEST_METHOD"]);
        $this->uri = $_SERVER["REQUEST_URI"];
        $this->body =
            $this->method === "GET" ? $_GET :
            json_decode(file_get_contents("php://input"), true) ?? [];

        $this->headers = $this->getRequestHeaders() ?? [];

        $this->state = [];
    }

    public function getFromState(string $key): mixed
    {
        if(!isset($this->state[$key]))
            throw new OutOfBoundsException("Key: $key is not valid");
        return $this->state[$key];
    }

    public function setStateItem(string $key, mixed $value): void
    {
        $this->state[$key] = $value;
    }

    private function getRequestHeaders(): array
    {
        if(function_exists('getallheaders'))
        {
            return getallheaders();
        }
        
        $headers = [];

        foreach ($_SERVER as $name => $value)
        {
            if (str_starts_with($name, 'HTTP_')) {
                $header = str_replace('_', '-', strtolower(substr($name, 5)));
                $headers[$header] = $value;
            }
        }

        if (isset($_SERVER['CONTENT_TYPE'])) {
            $headers['content-type'] = $_SERVER['CONTENT_TYPE'];
        }

        if (isset($_SERVER['CONTENT_LENGTH'])) {
            $headers['content-length'] = $_SERVER['CONTENT_LENGTH'];
        }

        if (isset($_SERVER['HTTP_X_AUTHORIZATION'])) {
            $headers['X-Authorization'] = $_SERVER['HTTP_X_AUTHORIZATION'];
        }

        return $headers;
    }
}