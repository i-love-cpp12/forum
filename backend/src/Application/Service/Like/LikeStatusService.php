<?php
declare(strict_types=1);

namespace src\Application\Service\Like;

use src\Domain\Entity\Like;

use src\Domain\Repository\LikeRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;
use src\Domain\Repository\PostRepositoryInterface;

use src\Shared\Exception\BusinessException\EntityNotFoundException;

require_once(__DIR__ . "/../../../../autoload.php");

class LikeStatusService
{
    public function __construct
    (
        private LikeRepositoryInterface $likeRepo,
        private PostRepositoryInterface $postRepo,
        private UserRepositoryInterface $userRepo,
    )
    {}

    public function execute(int $userId, int $postId): ?Like
    {
        if($this->userRepo->getUserById($userId) === null)
            throw new EntityNotFoundException("User", $userId);

        if($this->postRepo->getPostById($postId) === null)
            throw new EntityNotFoundException("Post", $postId);

        return $this->likeRepo->getLike($userId, $postId);
    }
}