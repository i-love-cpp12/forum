<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Entity\User;
use src\Domain\Entity\UserRole;

use src\Application\Service\ServiceHelper;

use src\Domain\Repository\UserRepositoryInterface;
use src\Application\DTO\User\UserDeleteDTO;

use src\Shared\Exception\BussinessException\AuthException;
use src\Shared\Exception\BussinessException\EntityNotFoundException;

class UserDeleteService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(UserDeleteDTO $DTO): void
    {
        ServiceHelper::authorizeAction(
            UserRole::from($DTO->loggedUserRole),
            $DTO->loggedUserId,
            $DTO->userToDeleteId
        );

        $user = $this->userRepo->getUserById($DTO->userToDeleteId);
          
        if($user === null)
            throw new EntityNotFoundException("User", $DTO->userToDeleteId);
        $this->userRepo->deleteUser($user->getId());
    }
}