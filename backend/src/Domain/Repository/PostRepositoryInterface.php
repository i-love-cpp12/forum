<?php
declare(strict_types=1);

namespace src\Domain\Repository;

require_once(__DIR__ . "/../../../autoload.php");

use src\Domain\Entity\Post;

//from user repo interface to post interface
interface PostRepositoryInterface
{
    public function savePost(Post $post): void;
    /** @return User[]*/
    public function getAllPosts(): array;
    public function getUserById(int $id): ?User;
    public function getUserByEmail(string $email): ?User;

    public function deleteUser(int $id): void;

    public function activateToken(Token $token): void;
    public function deactivateTokensForUser(int $userId): void;
    public function getActiveTokenByValue(string $tokenValue): ?Token;
}