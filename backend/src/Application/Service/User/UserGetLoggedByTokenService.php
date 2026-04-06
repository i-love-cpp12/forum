<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Entity\User;

use src\Domain\Repository\UserRepositoryInterface;

use src\Shared\Exception\BusinessException\BusinessException;
use src\Shared\Exception\BusinessException\EntityNotFoundException;

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
            throw new EntityNotFoundException("User", $token->value, "active token");

        return $user;
    }
}