<?php
declare(strict_types=1);

namespace src\Interface\Router;

require_once(__DIR__ . "/../../../autoload.php");

use Exception;
use src\Infrastructure\Http\Respond;

//uri example /api/users/{id} where id is placeholder -> /api/users/1

class Router
{
    private static $routes = [];
    private static $methods = ["GET", "POST", "PUT", "DELETE"];

    /** @param callable[] $middleware */
    public function bind(
        string $method,
        string $path,
        callable $handle,
        array $middleware = []): void
    {
        $method = strtoupper($method);

        
        if(!self::validateMethod($method))
            throw new Exception("Method not allowed");

        self::$routes[$method] =
            ["path" => $path, "handle" => $handle, "middleware" => $middleware];
    }

    public function route(string $method, string $uri): void
    {
        if(!self::validateMethod($method))
            Respond::json(["error" => "Method not allowed"], 405);

        // [$path, $handle, $middleware] = self::$routes[$method];

        foreach(self::$routes[$method] as $route)
        {
            $uriRegexp =
                "^" . str_replace("\{[A-Za-z]\}", "([A-Za-z0-9]+)", $route["path"]) . "$";
            Respond::json([$uriRegexp]);
        }

        Respond::json(["error" => "Endpoint not found"], 404);
    }

    private static function validateMethod(string $method): bool
    {
        return in_array($method, self::$methods, true);
    }
}