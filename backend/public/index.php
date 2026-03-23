<?php
declare(strict_types = 1);

require_once(__DIR__ . "/../autoload.php");

use src\Infrastructure\Http\Request;
use src\Infrastructure\Http\Respond;
use src\Interface\Router\Router;

$request = new Request();

$router = new Router();

$router->bind("GET", "api/{id}/create", function($id){echo $id; exit();});

$router->route($request->method, "backend/api/2374/create");
// $router->route($request->method, "backend/api/create");

echo "dziala";