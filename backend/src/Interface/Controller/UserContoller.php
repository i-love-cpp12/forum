<?php
declare(strict_types=1);
namespace src\Interface\Controller;

use src\Application\DTO\User\LogoutDTO;
use src\Application\DTO\User\UserGetDTO;
use src\Application\DTO\User\UserGetLoggedDTO;
use src\Application\DTO\User\UserLoginDTO;
use src\Application\Service\User\UserGetLoggedByTokenService;
use src\Application\Service\User\UserLoginService;
use src\Application\Service\User\UserLogoutService;
use src\Domain\Entity\User;
use src\Domain\Service\UserGenerateTokenService;
use src\Infrastructure\Http\Request;
use src\Infrastructure\Http\Respond;
use src\Shared\Exception\BusinessException;
use src\Shared\Exception\ExceptionHandler;
use Throwable;

require_once(__DIR__ . "/../../../autoload.php");



class UserContoller
{
    public function __construct
    (
        private Request $request,
        private UserLoginService $userLoginService,
        private UserGenerateTokenService $userGenerateTokenService,
        private UserLogoutService $userLogoutService,
    ){}
    public function login(): void
    {
        $email = $this->request->body["email"] ?? null;
        $password = $this->request->body["password"] ?? null;

        try
        {
            if(!$email || !is_string($email))
                throw new BusinessException("Request body must contain `email` of (string) type");
            if(!$password || !is_string($password))
                throw new BusinessException("Request body must contain `password` of (string) type");

            $token = $this->userGenerateTokenService->execute();

            $userLoginDTO = new UserLoginDTO($email, $password, $token);

            $this->userLoginService->execute($userLoginDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        Respond::json(["error" => "", "data" => ["message" => "Login for email: $email and password: " . User::hidePassword($password) .  " successful"], "token" => $token]);
    }

    public function logout(): void
    {
        /** @var User $user */
        $user = $this->request->getFromState("user");

        $DTO = new UserGetDTO($user->getId());
        $this->userLogoutService->execute($DTO);

        Respond::json(["error" => "", "data" => ["message" => "Logout successful for email: " . $user->email]]);
    }
    
    public function register(): void
    {

    }
    public function getAllUsers(): void
    {

    }
    public function getUser(int $userId): void
    {

    }
    public function updateUser(int $userId): void
    {

    }
    public function getLoggedUser(): void
    {

    }
    public function deleteUser(int $userId): void
    {

    }
}