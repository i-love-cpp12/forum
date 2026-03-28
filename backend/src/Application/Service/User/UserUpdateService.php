<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Application\DTO\User\UserUpdateDTO;
use src\Domain\Entity\User;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Exception\BusinessException;

class UserUpdateService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(UserUpdateDTO $DTO): void
    {
        $user = $this->userRepo->getUserById($DTO->id);
        
        if($user === null)
            throw new BusinessException("User with id: $DTO->id not found", 404);

        if(!User::validateUsername($DTO->newUsername))
            throw new BusinessException("New username: $DTO->newUsername is not valid");

        if(!User::validatePassword($DTO->newPassword))
            throw new BusinessException("New password: " . User::hidePassword($DTO->newPassword) . " is too weak, it must contain at least one uppercase letter one lowercase letter and one special character and password must be at least (" . User::$passwordMinLenght . ") long");

        $user->setUsername($DTO->newUsername);
        $user->setPassword($DTO->newPassword);
        
        $this->userRepo->saveUser($user);
    }
}