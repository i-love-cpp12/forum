<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Entity\User;
use src\Domain\Entity\UserRole;

use src\Application\Service\ServiceHelper;

use src\Domain\Repository\UserRepositoryInterface;
use src\Application\DTO\User\UserUpdateDTO;

use src\Shared\Exception\BussinessException\AuthException;
use src\Shared\Exception\BussinessException\EntityNotFoundException;
use src\Shared\Exception\BussinessException\InvalidValueException;

class UserUpdateService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(UserUpdateDTO $DTO): void
    {
        if(!ServiceHelper::authUserAction($DTO->loggedUserId, $DTO->loggedUserRole, $DTO->userToUpdateId))
            throw new AuthException(User::roleToString(UserRole::from($DTO->loggedUserRole)));

        if($DTO->newUsername === null && $DTO->newPassword === null)
            return;

        $user = $this->userRepo->getUserById($DTO->userToUpdateId);
        
        if($user === null)
            throw new EntityNotFoundException("User", $DTO->userToUpdateId);

        if($DTO->newUsername !== null && !User::validateUsername($DTO->newUsername))
            throw new InvalidValueException("New username", $DTO->newUsername);

        if($DTO->newPassword !== null && !User::validatePassword($DTO->newPassword))
            throw new InvalidValueException("New password", User::hidePassword($DTO->newPassword), "contain at least one uppercase letter one lowercase letter and one special character and password must be at least (" . User::$passwordMinLenght . ") long");
        
        if($DTO->newUsername !== null)
            $user->setUsername($DTO->newUsername);

        if($DTO->newPassword !== null)
            $user->setPassword($DTO->newPassword);
        
        $this->userRepo->saveUser($user);
    }
}