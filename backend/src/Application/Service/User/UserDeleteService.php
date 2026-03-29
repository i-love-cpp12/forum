<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Application\DTO\User\UserDelteDTO;
use src\Domain\Entity\UserRole;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Exception\BusinessException;

class UserDeleteService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(UserDelteDTO $DTO): void
    {
        if
        (
            $DTO->loggedUserId !== $DTO->userToDeleteId &&
            $DTO->loggedUserRole !== UserRole::admin->value
        )
        {
            throw new BusinessException("You are not authorized user to make this action", 401);
        }

        $user = $this->userRepo->getUserById($DTO->userToDeleteId);
          
        if($user === null)
            throw new BusinessException("User with id: $DTO->userToDeleteId not found", 404);
        $this->userRepo->deleteUser($user->getId());
    }
}