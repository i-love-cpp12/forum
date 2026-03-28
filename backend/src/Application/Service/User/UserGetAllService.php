<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Repository\UserRepositoryInterface;

class UserGetAllService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    /** @return User[] */
    public function execute(): array
    {
        return $this->userRepo->getAllUsers();
    }
}