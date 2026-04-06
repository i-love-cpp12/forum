<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Exception\BusinessException\EntityNotFoundException;

class PostDeleteLikeService
{
    public function __construct
    (
        private UserRepositoryInterface $userRepo,
        private PostRepositoryInterface $postRepo
    ){}

    public function execute(int $userId, int $postId): void
    {
        if($this->userRepo->getUserById($userId) === null)
            throw new EntityNotFoundException("User", $userId);

        if($this->postRepo->getPostById($postId) === null)
            throw new EntityNotFoundException("Post", $postId);

        $this->postRepo->deleteLikePost($userId, $postId);
    }
}