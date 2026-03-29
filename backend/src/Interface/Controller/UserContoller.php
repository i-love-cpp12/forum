<?php
declare(strict_types=1);
namespace src\Interface\Controller;

use src\Application\DTO\User\UserDelteDTO;
use src\Application\DTO\User\UserGetDTO;
use src\Application\DTO\User\UserLoginDTO;
use src\Application\DTO\User\UserRegisterDTO;
use src\Application\DTO\User\UserUpdateDTO;
use src\Application\Service\User\UserDeleteService;
use src\Application\Service\User\UserGetAllService;
use src\Application\Service\User\UserGetService;
use src\Application\Service\User\UserLoginService;
use src\Application\Service\User\UserLogoutService;
use src\Application\Service\User\UserRegisterService;
use src\Application\Service\User\UserUpdateService;
use src\Domain\Entity\User;
use src\Domain\Entity\UserRole;
use src\Domain\Service\UserGenerateTokenService;
use src\Infrastructure\Http\Request;
use src\Infrastructure\Http\Respond;
use src\Interface\Mapper\UserMapper;
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
        private UserRegisterService $userRegisterService,
        private UserGenerateTokenService $userGenerateTokenService,
        private UserLogoutService $userLogoutService,
        private UserGetAllService $userGetAllService,
        private UserGetService $userGetService,
        private UserUpdateService $userUpdateService,
        private UserDeleteService $userDeleteService
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

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Login for email: $email and password: " . User::hidePassword($password) .  " successful",
                        "token" => $token
                    ]
            ]
        );
    }

    public function logout(): void
    {
        /** @var User $user */
        $user = $this->request->getFromState("user");

        $DTO = new UserGetDTO($user->getId());
        try
        {
            $this->userLogoutService->execute($DTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Logout successful for email: " . $user->email
                    ]
            ]
        );
    }
    
    public function register(): void
    {
        $username = $this->request->body["username"] ?? null;
        $email = $this->request->body["email"] ?? null;
        $password = $this->request->body["password"] ?? null;

        try
        {
            if(!$username || !is_string($username))
                throw new BusinessException("Request body must contain `username` of (string) type");
            if(!$email || !is_string($email))
                throw new BusinessException("Request body must contain `email` of (string) type");
            if(!$password || !is_string($password))
                throw new BusinessException("Request body must contain `password` of (string) type");

            $userRegisterDTO = new UserRegisterDTO($username, $email, $password);

            $this->userRegisterService->execute($userRegisterDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Register successful for email: " . $email
                    ]
            ]
        );
    }
    public function getAllUsers(): void
    {
        /** @var User[] $users */
        $users = [];

        try
        {
            $users = $this->userGetAllService->execute();
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        $usersMapped = [];
        foreach($users as $user)
        {
            $usersMapped[] = UserMapper::map($user);    
        }

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Getting all users successful",
                        "users" => $usersMapped
                    ]
            ]
        );
    }

    public function getUser(string $userId): void
    {
        $userId = intval($userId);
        $user = null;

        try
        {
            $userGetDTO = new UserGetDTO($userId);
            $user = $this->userGetService->execute($userGetDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        $userMapped = UserMapper::map($user);

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Getting user with id: $userId successful",
                        "user" => $userMapped
                    ]
            ]
        );
    }

    public function updateUser(string $userId): void
    {
        $userId = intval($userId);

        $username = $this->request->body["username"] ?? null;
        $password = $this->request->body["password"] ?? null;

        try
        {
            if($username !== null && !is_string($username))
                throw new BusinessException("Username must be type of (string)");

            if($password !== null && !is_string($password))
                throw new BusinessException("Password must be type of (string)");

            if($password === null && $username === null)
                throw new BusinessException("To update user you have to provide some of allowed data: username (string), password(string)");
            
            /** @var User $loggedUser */
            $loggedUser = $this->request->getFromState("user");

            $userUpdateDTO = new UserUpdateDTO($userId, $username, $password, $loggedUser->getId(), $loggedUser->role->value);
            $this->userUpdateService->execute($userUpdateDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }


        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" =>
                            "Updating user with id: $userId with new " .
                            ($username !== null ? "username: " . $username:"") .
                            ($username !== null && $password !== null ? " and":"") .
                            ($password !== null ? " password: " . User::hidePassword($password):"") .
                            " successful"
                    ]
            ]
        );
    }
    
    public function deleteUser(string $userId): void
    {
        $userId = intval($userId);

        try
        {
            /** @var User $loggedUser */
            $loggedUser = $this->request->getFromState("user");

            $userDelteDTO = new UserDelteDTO($userId, $loggedUser->getId(), $loggedUser->role->value);
            $this->userDeleteService->execute($userDelteDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }


        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" =>
                            "Deleting user with id: $userId successful"
                    ]
            ]
        );
    }

    public function getLoggedUser(): void
    {

        $userMapped = UserMapper::map($this->request->getFromState("user"));

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Getting logged user successful",
                        "user" => $userMapped
                    ]
            ]
        );
    }
}