<?php
declare(strict_types = 1);

require_once(__DIR__ . "/../autoload.php");

use src\Application\Service\User\UserDeleteService;
use src\Application\Service\User\UserGetAllService;
use src\Application\Service\User\UserGetLoggedByTokenService;
use src\Application\Service\User\UserGetService;
use src\Application\Service\User\UserGetTokenByValueService;
use src\Application\Service\User\UserUpdateService;
use src\Application\Service\User\UserLoginService;
use src\Application\Service\User\UserLogoutService;
use src\Application\Service\User\UserRegisterService;
use src\Domain\Service\UserGenerateTokenService;
use src\Domain\Service\UserGetAuthTokenService;
use src\Infrastructure\Http\Request;

use src\Infrastructure\Repository\Dummy\DummyUserRepository;

use src\Interface\Controller\UserContoller;
use src\Interface\Middleware\AuthMiddleware;
use src\Interface\Router\Router;

$request = new Request();

$router = new Router();

$userRepository = new DummyUserRepository();

$userLoginService = new UserLoginService($userRepository);
$userRegisterService = new UserRegisterService($userRepository);
$userGenerateTokenService = new UserGenerateTokenService();
$userLogoutService = new UserLogoutService($userRepository);
$userGetAllService = new UserGetAllService($userRepository);
$userGetService = new UserGetService($userRepository);
$userUpdateService = new UserUpdateService($userRepository);
$userDeleteService = new UserDeleteService($userRepository);
$userGetTokenByValueService = new UserGetTokenByValueService($userRepository);
$userGetLoggedByTokenService = new UserGetLoggedByTokenService($userRepository);
$userGetAuthTokenService = new UserGetAuthTokenService($userRepository);

$authMiddleware = new AuthMiddleware(
    $request,
    $userGetLoggedByTokenService,
    $userGetAuthTokenService
);


$userController = new UserContoller(
    $request,
    $userLoginService,
    $userRegisterService,
    $userGenerateTokenService,
    $userLogoutService,
    $userGetAllService,
    $userGetService,
    $userUpdateService,
    $userDeleteService,
    $userGetTokenByValueService
);

$router->bind("POST", "api/register", [$userController, "register"]);
$router->bind("POST", "api/login", [$userController, "login"]);
$router->bind("POST", "api/logout", [$userController, "logout"]);
$router->bind("GET", "api/users/{id}", [$userController, "getUser"]);
$router->bind("GET", "api/users", [$userController, "getAllUsers"]);
$router->bind("GET", "api/me", [$userController, "getLoggedUser"], [
    [$authMiddleware, "execute"]
]);
$router->bind("PUT", "api/users/{id}", [$userController, "updateUser"], [
    [$authMiddleware, "execute"]
]);
$router->bind("PUT", "api/users/{id}", [$userController, "deleteUser"], [
    [$authMiddleware, "execute"]
]);

$router->route($request->method, $request->uri);
