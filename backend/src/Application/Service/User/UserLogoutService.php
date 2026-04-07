<?php
declare(strict_types=1);

namespace src\Application\Service\User;

use src\Domain\Repository\UserRepositoryInterface;

class UserLogoutService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(int $userId): void
    {
        $this->userRepo->deactivateTokensForUser($userId);
    }
}