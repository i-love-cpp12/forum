<?php
declare(strict_types=1);

namespace src\Infrastructure\Http;

use OutOfBoundsException;
use src\Domain\Entity\User;

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
        $this->body = $this->method === "GET" ? [] : json_decode(file_get_contents("php://input"), true);
        $this->headers = getallheaders() ?? [];
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
}