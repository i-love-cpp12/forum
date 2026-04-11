<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\Dummy;

use src\Domain\Repository\LikeRepositoryInterface;
use src\Domain\Entity\Like;
use src\Domain\Entity\LikeType;
use src\Shared\Array\ArrayHelper;

class DummyLikeRepository implements LikeRepositoryInterface
{
    /** @var Like[] $likes */
    private array $likes;
    private int $nextLikeId;

    public function __construct()
    {
        $this->likes = [];
        $this->nextLikeId = 0;
        $this->saveLike(new Like(null, 0, 1, LikeType::like));
        $this->saveLike(new Like(null, 0, 2, LikeType::like));
        $this->saveLike(new Like(null, 0, 3, LikeType::like));

        $this->saveLike(new Like(null, 0, 4, LikeType::dislike));
        $this->saveLike(new Like(null, 0, 5, LikeType::dislike));
        $this->saveLike(new Like(null, 0, 6, LikeType::dislike));
        $this->saveLike(new Like(null, 0, 7, LikeType::dislike));

        $this->saveLike(new Like(null, 4, 8, LikeType::like));
        $this->saveLike(new Like(null, 4, 9, LikeType::dislike));
    }
    public function saveLike(Like $like): void
    {
        DummyRepositoryHelper::saveEntity($like, $this->likes, $this->nextLikeId);
    }
    public function deleteLike(int $userId, int $postId): void
    {
        ArrayHelper::deleteByItem(
            $this->likes, ["userId" => $userId, "postId" => $postId],
            fn(Like $i1, array $i2) =>
                (
                    $i1->userId === $i2["userId"] &&
                    $i1->postId === $i2["postId"]
                )
        );
    }
    public function getLike(int $userId, int $postId): ?Like
    {
        return ArrayHelper::find(
            $this->likes,
            fn(Like $item) =>
                (
                    $item->userId === $userId &&
                    $item->postId === $postId
                ));
    }
    public function getLikeById(int $likeId): ?Like
    {
        return DummyRepositoryHelper::getEntityById($likeId, $this->likes);
    }
}