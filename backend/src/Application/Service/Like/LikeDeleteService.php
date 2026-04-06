<?php
declare(strict_types=1);

namespace src\Application\Service\Like;

use src\Domain\Repository\LikeRepositoryInterface;
use src\Shared\Exception\BusinessException\EntityNotFoundException;

require_once(__DIR__ . "/../../../../autoload.php");

class LikeDeleteService
{
    public function __construct
    (
        private LikeRepositoryInterface $likeRepo,
    )
    {}

    public function execute(int $userId, int $postId): void
    {
        $like = $this->likeRepo->getLike($userId, $postId);
        if($like === null)
            throw new EntityNotFoundException("Like", "($userId, $postId)", "(UserId, PostId)");
        $this->likeRepo->deleteLike($userId, $postId);
    }
}