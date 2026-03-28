<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Application\DTO\User\UpdateDTO;
use src\Domain\Entity\User;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Exception\BusinessException;

class UserGetService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(UpdateDTO $DTO): void
    {
        $user = $this->userRepo->getUserById($DTO->id);
        
        if($user === null)
            throw new BusinessException("User with id: $DTO->id not found", 404);

        if(!User::validateUsername($DTO->newUsername))
            throw new BusinessException("New username: $DTO->newUsername is not valid");

        $user->username = $DTO->newUsername;
        
        $this->userRepo->saveUser($user);
    }
}