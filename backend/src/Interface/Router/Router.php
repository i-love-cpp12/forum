<?php
declare(strict_types=1);

namespace src\Interface\Router;

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

        self::$routes[$method][] =
            ["path" => self::pathToRegexp($path), "handle" => $handle, "middleware" => $middleware];
    }

    public function route(string $method, string $uri): void
    {
        if(!self::validateMethod($method))
            Respond::json(["error" => "Method not allowed"], 405);

        foreach(self::$routes[$method] as $route)
        {
            if(!preg_match($route["path"], $uri, $match))
                continue;

            if(count($match) > 1)
                $match = [$match[1]];

            foreach($route["middleware"] as $middleware)
            {
                $middleware();
            }
            $route["handle"](...$match);
        }

        Respond::json(["error" => "Endpoint not found"], 404);
    }

    private static function validateMethod(string $method): bool
    {
        return in_array($method, self::$methods, true);
    }

    private static function pathToRegexp(string $path): string
    {
        $path = str_replace("/", "\/", $path);
        return "/" . preg_replace("/\{[a-z]+\}/i", "([A-Za-z0-9]+)", $path) . "$/";
    }
}