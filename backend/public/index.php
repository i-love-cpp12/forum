<?php
declare(strict_types = 1);

require_once(__DIR__ . "/../autoload.php");

use src\Infrastructure\Http\Request;
use src\Infrastructure\Http\Respond;
use src\Interface\Router\Router;

$request = new Request();

$router = new Router();

$router->bind("GET", "pth", function(){echo "asdasd";});

$router->route($request->method, $request->uri);

echo "dziala";