<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Entity\User;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Exception\BusinessException\EntityNotFoundException;

class UserGetService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(int $userId): User
    {
        $user = $this->userRepo->getUserById($userId);
        
        if($user === null)
            throw new EntityNotFoundException("User", $userId);
        return $user;
    }
}