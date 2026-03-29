<?php

require_once(__DIR__ . "/../autoload.php");

use src\Infrastructure\Repository\Dummy\DummyUserRepository;
use src\Infrastructure\Http\Respond;
use src\Interface\Controller\UserContoller;

use src\Application\Service\User\UserDeleteService;
use src\Application\Service\User\UserGetAllService;
use src\Application\Service\User\UserGetService;
use src\Application\Service\User\UserUpdateService;
use src\Application\Service\User\UserLoginService;
use src\Application\Service\User\UserLogoutService;
use src\Application\Service\User\UserRegisterService;
use src\Domain\Service\UserGenerateTokenService;

$userRepository = new DummyUserRepository();

$userLoginService = new UserLoginService($userRepository);
$userRegisterService = new UserRegisterService($userRepository);
$userGenerateTokenService = new UserGenerateTokenService();
$userLogoutService = new UserLogoutService($userRepository);
$userGetAllService = new UserGetAllService($userRepository);
$userGetService = new UserGetService($userRepository);
$userUpdateService = new UserUpdateService($userRepository);
$userDeleteService = new UserDeleteService($userRepository);

$userController = new UserContoller(
    $request,
    $userLoginService,
    $userRegisterService,
    $userGenerateTokenService,
    $userLogoutService,
    $userGetAllService,
    $userGetService,
    $userUpdateService,
    $userDeleteService
);