<?php
declare(strict_types=1);

namespace src\Domain\Repository;

use src\Domain\Entity\Like;

require_once(__DIR__ . "/../../../autoload.php");


interface LikeRepositoryInterface
{
    public function saveLike(Like $like): void;
    public function deleteLike(int $userId, int $postId): void;
    public function getLike(int $userId, int $postId): ?Like;
    public function getLikeById(int $likeId): ?Like;
}