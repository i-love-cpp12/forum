<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Entity\User;
use src\Domain\Entity\UserRole;

use src\Application\Service\ServiceHelper;

use src\Domain\Repository\UserRepositoryInterface;
use src\Application\DTO\User\UserUpdateDTO;
use src\Shared\Exception\BusinessException\BusinessException;
use src\Shared\Exception\BusinessException\EntityNotFoundException;
use src\Shared\Exception\BusinessException\InvalidValueException;

class UserUpdateService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(UserUpdateDTO $DTO): void
    {
        ServiceHelper::authorizeAction(
            UserRole::from($DTO->loggedUserRole),
            $DTO->loggedUserId,
            $DTO->userToUpdateId
        );

        if($DTO->newUsername === null && $DTO->newPassword === null)
            throw new BusinessException("To update user you have to provide some of allowed data: username (string), password(string)");

        $user = $this->userRepo->getUserById($DTO->userToUpdateId);
        
        if($user === null)
            throw new EntityNotFoundException("User", $DTO->userToUpdateId);

        if($DTO->newUsername !== null && !User::validateUsername($DTO->newUsername))
            throw new InvalidValueException("New username", $DTO->newUsername, User::getUsernameValidateMessage());

        if($DTO->newPassword !== null && !User::validatePassword($DTO->newPassword))
            throw new InvalidValueException("New password", User::hidePassword($DTO->newPassword), User::getPasswordValidateMessage());
        
        if($DTO->newUsername !== null)
            $user->setUsername($DTO->newUsername);

        if($DTO->newPassword !== null)
            $user->setPassword($DTO->newPassword);
        
        $this->userRepo->saveUser($user);
    }
}