<?php
declare(strict_types=1);

namespace src\Domain\Repository;

require_once(__DIR__ . "/../../../autoload.php");

use src\Domain\Entity\User;
use src\Domain\Entity\Token;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    /** @return User[]*/
    public function getAllUsers(): array;
    public function getUserById(string $id): ?User;
    public function getUserByEmail(string $email): ?User;

    public function deleteUser($id): void;

    public function saveToken(User $user, Token $token): void;
    public function deactivateToken(Token $token): void;
    
}