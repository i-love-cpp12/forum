<?php
declare(strict_types=1);
namespace src\Interface\Controller;

require_once(__DIR__ . "/../../../autoload.php");

use src\Application\DTO\User\UserDeleteDTO;
use src\Application\DTO\User\UserLoginDTO;
use src\Application\DTO\User\UserRegisterDTO;
use src\Application\DTO\User\UserUpdateDTO;
use src\Application\Service\User\UserDeleteService;
use src\Application\Service\User\UserGetAllService;
use src\Application\Service\User\UserGetService;
use src\Application\Service\User\UserGetTokenByValueService;
use src\Application\Service\User\UserLoginService;
use src\Application\Service\User\UserLogoutService;
use src\Application\Service\User\UserRegisterService;
use src\Application\Service\User\UserUpdateService;
use src\Domain\Entity\User;
use src\Domain\Service\UserGenerateTokenService;
use src\Infrastructure\Http\Request;
use src\Infrastructure\Http\Respond;
use src\Interface\Mapper\TokenMapper;
use src\Interface\Mapper\UserMapper;
use src\Shared\Exception\BusinessException\BusinessException;
use src\Shared\Exception\BusinessException\RequestDataFormatException;
use src\Shared\Exception\ExceptionHandler;
use Throwable;



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
        private UserDeleteService $userDeleteService,
        private UserGetTokenByValueService $userGetTokenByValueService
    ){}
    public function login(): void
    {
        $email = $this->request->body["email"] ?? null;
        $password = $this->request->body["password"] ?? null;

        try
        {
            if(!$email || !is_string($email))
                throw new RequestDataFormatException("email", "string");
            if(!$password || !is_string($password))
                throw new RequestDataFormatException("password", "string");

            $generatedToken = $this->userGenerateTokenService->execute();

            $userLoginDTO = new UserLoginDTO($email, $password, $generatedToken);

            $this->userLoginService->execute($userLoginDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        $token = $this->userGetTokenByValueService->execute($generatedToken);
        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Login for email: $email and password: " . User::hidePassword($password) .  " successful",
                        "token" => TokenMapper::map($token)
                    ]
            ]
        );
    }

    public function logout(): void
    {
        /** @var User $user */
        $user = $this->request->getFromState("user");

        try
        {
            $this->userLogoutService->execute($user->getId());
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
                throw new RequestDataFormatException("username", "string");
            if(!$email || !is_string($email))
                throw new RequestDataFormatException("email", "string");
            if(!$password || !is_string($password))
                throw new RequestDataFormatException("password", "string");

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
        $user = null;
        try
        {
            if(!ctype_digit($userId))
                throw new RequestDataFormatException("userId", "int", true);
            $userId = intval($userId);
            $user = $this->userGetService->execute($userId);
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

        $username = $this->request->body["username"] ?? null;
        $password = $this->request->body["password"] ?? null;

        try
        {
            if(!is_int($userId))
                throw new RequestDataFormatException("userId", "int", true);
            $userId = intval($userId);

            if($username !== null && !is_string($username))
                throw new RequestDataFormatException("username", "string");

            if($password !== null && !is_string($password))
                throw new RequestDataFormatException("password", "string");

            if($password === null && $username === null)
                throw new BusinessException("To update user you have to provide some of allowed data: username (string), password(string)");
            
            /** @var User $loggedUser */
            $loggedUser = $this->request->getFromState("user");

            $userUpdateDTO = new UserUpdateDTO($userId, $loggedUser->getId(), $loggedUser->role->value, $username, $password);
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

        try
        {
            if(!is_int($userId))
                throw new RequestDataFormatException("userId", "int", true);
            $userId = intval($userId);

            /** @var User $loggedUser */
            $loggedUser = $this->request->getFromState("user");

            $userDeleteDTO = new UserDeleteDTO($userId, $loggedUser->getId(), $loggedUser->role->value);
            $this->userDeleteService->execute($userDeleteDTO);
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