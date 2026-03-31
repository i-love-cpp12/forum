<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Exception\BusinessException;
use src\Domain\Entity\User;

class UserGetLoggedByTokenService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(string $token): User
    {
        $token = $this->userRepo->getActiveTokenByValue($token);
        if($token === null)
            throw new BusinessException("Token: $token is not active");

        $user = $this->userRepo->getUserById($token->userId);

        if($user === null)
            throw new BusinessException("User with active token: $token do not exist");

        return $user;
    }
}