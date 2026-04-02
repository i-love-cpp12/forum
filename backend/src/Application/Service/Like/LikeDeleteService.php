<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

use src\Domain\Repository\LikeRepositoryInterface;
use src\Shared\Exception\BusinessException;

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
            throw new BusinessException("Like to delete not found");
        $this->likeRepo->deleteLike($userId, $postId);
    }
}