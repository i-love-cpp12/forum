<?php
declare(strict_types=1);

namespace src\Infrastructure\Http;

class Request
{
    readonly public array $body;
    readonly public string $method; 
    readonly public string $uri;
    
    public function __construct()
    {
        $this->method = strtoupper($_SERVER["REQUEST_METHOD"]);
        $this->uri = strtoupper($_SERVER["REQUEST_URI"]);
        $this->body = $this->method === "GET" ? [] : json_decode(file_get_contents("php://input"), true);
    }
}