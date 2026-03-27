<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Application\DTO\User\RegisterDTO;
use src\Domain\Repository\UserRepositoryInterface;
use src\Domain\Entity\User;
use src\Shared\Exception\BusinessException;

class UserRegisterService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(RegisterDTO $DTO)
    {
        if(!User::validateUsername($DTO->username))
            throw new BusinessException("username: $DTO->username must be (" . User::$usernameMinLenght . " - " . User::$usernameMaxLenght . ") character long");

        if(!User::validateEmail($DTO->email))
            throw new BusinessException("email: $DTO->email is not valid email");

        if(!User::validatePassword($DTO->password))
            throw new BusinessException("password: $DTO->password must contain at least one uppercase letter one lowercase letter and one special character and password must be at least (" . User::$passwordMinLenght . ") long");

        $paswordHash = password_hash($DTO->password, "sha256");

        $user = new User(null, $DTO->username, $DTO->email, $paswordHash);
        $this->userRepo->save($user);
    }
}