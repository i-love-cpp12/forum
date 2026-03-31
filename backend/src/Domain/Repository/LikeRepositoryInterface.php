<?php
declare(strict_types=1);

namespace src\Domain\Repository;

use src\Domain\Entity\Like;

require_once(__DIR__ . "/../../../autoload.php");


interface LikeRepositoryInterface
{
    public function saveLike(Like $like): void;
    public function deleteLike(int $likeId): void;
    public function getLike(int $userId, int $postId): ?Like;
}