<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Repository\UserRepositoryInterface;
use src\Application\DTO\User\UserGetDTO;
use src\Shared\Exception\BusinessException;

class UserGetService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(UserGetDTO $DTO): void
    {
        $user = $this->userRepo->getUserById($DTO->id);
        
        if($user === null)
            throw new BusinessException("User with id: $DTO->id not found", 404);
        $this->userRepo->deleteUser($user->getId());
    }
}