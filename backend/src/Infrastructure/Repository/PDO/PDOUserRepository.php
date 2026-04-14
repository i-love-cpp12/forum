<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\PDO;

use LogicException;
use PDO;
use src\Domain\Entity\User;
use src\Domain\Entity\Token;
use src\Domain\Entity\UserRole;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Array\ArrayHelper;
use src\Infrastructure\Repository\Dummy\DummyRepositoryHelper;

class PDOUserRepository implements UserRepositoryInterface
{

    public function __construct(private PDO $conn)
    {
    }

    public function saveUser(User $user): void
    {

    }

    /** @return User[]*/
    public function getAllUsers(): array
    {
        return [];
    }
    public function getUserById(int $id): ?User
    {
        return null;
    }
    public function getUserByUsername(string $username): ?User
    {
        return null;
    }
    public function getUserByEmail(string $email): ?User
    {
        return null;
    }

    public function deleteUser(int $id): void
    {

    }

    public function activateToken(Token $token): void
    {
        if($token->getId() !== null)
            throw new LogicException("Token must be new token");
        //save token
    }

    public function deactivateTokensForUser(int $userId): void
    {

    }

    public function getActiveTokenByValue(string $tokenValue): ?Token
    {
        return null;
    }
}