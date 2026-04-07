<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\Dummy;

use src\Domain\Repository\LikeRepositoryInterface;
use src\Domain\Entity\Like;

class DummyLikeRepository implements LikeRepositoryInterface
{
    public function saveLike(Like $like): void
    {

    }
    public function deleteLike(int $userId, int $postId): void
    {

    }
    public function getLike(int $userId, int $postId): ?Like
    {
        return null;
    }
    public function getLikeById(int $likeId): ?Like
    {
        return null;
    }
}