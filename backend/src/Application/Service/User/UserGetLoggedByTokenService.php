<?php
declare(strict_types=1);

namespace src\Application\Service\User;

use src\Domain\Entity\User;

use src\Domain\Repository\UserRepositoryInterface;

use src\Shared\Exception\BusinessException\BusinessException;
use src\Shared\Exception\BusinessException\EntityNotFoundException;

class UserGetLoggedByTokenService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(string $rawToken): User
    {
        $token = $this->userRepo->getActiveTokenByValue($rawToken);
        if($token === null)
            throw new BusinessException("Token: $rawToken is not active");

        $user = $this->userRepo->getUserById($token->userId);

        if($user === null)
            throw new EntityNotFoundException("User", $token->value, "active token");

        return $user;
    }
}