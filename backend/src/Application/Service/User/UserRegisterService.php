<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Entity\User;

use src\Domain\Repository\UserRepositoryInterface;
use src\Application\DTO\User\UserRegisterDTO;

use src\Shared\Exception\BussinessException\BusinessException;
use src\Shared\Exception\BussinessException\InvalidValueException;

class UserRegisterService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(UserRegisterDTO $DTO): void
    {
        if(!User::validateUsername($DTO->username))
            throw new InvalidValueException("Username", $DTO->username, "(" . User::$usernameMinLenght . " - " . User::$usernameMaxLenght . ") character long");

        if(!User::validateEmail($DTO->email))
            throw new InvalidValueException("Email", $DTO->email);

        if(!User::validatePassword($DTO->password))
            throw new InvalidValueException("New password", User::hidePassword($DTO->password), "contain at least one uppercase letter one lowercase letter and one special character and password must be at least (" . User::$passwordMinLenght . ") long");

        if($this->userRepo->getUserByEmail($DTO->email) !== null)
            throw new BusinessException("User with this email: $DTO->email already exist", 409);

        $paswordHash = password_hash($DTO->password, "sha256");

        $user = new User(null, $DTO->username, $DTO->email, $paswordHash);
        $this->userRepo->saveUser($user);
    }
}