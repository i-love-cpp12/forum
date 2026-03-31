<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Application\DTO\User\UserGetDTO;
use src\Domain\Repository\UserRepositoryInterface;

class UserLogoutService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(int $userId): void
    {
        $this->userRepo->deactivateTokensForUser($userId);
    }
}