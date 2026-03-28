<?php
declare(strict_types=1);

namespace src\Domain\Repository;

require_once(__DIR__ . "/../../../autoload.php");

use src\Domain\Entity\User;
use src\Domain\Entity\Token;

interface UserRepositoryInterface
{
    public function saveUser(User $user): void;
    /** @return User[]*/
    public function getAllUsers(): array;
    public function getUserById(int $id): ?User;
    public function getUserByEmail(string $email): ?User;

    public function deleteUser(int $id): void;

    public function activateToken(Token $token): void;
    public function deactivateTokensForUser(int $userId): void;
    public function getActiveTokenByValue(string $tokenValue): ?Token;
}