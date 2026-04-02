<?php
declare(strict_types=1);

namespace src\Application\Service\Like;

use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Repository\LikeRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Exception\BusinessException;
use src\Domain\Entity\Like;

require_once(__DIR__ . "/../../../../autoload.php");

class LikeAddService
{
    public function __construct
    (
        private LikeRepositoryInterface $likeRepo,
        private PostRepositoryInterface $postRepo,
        private UserRepositoryInterface $userRepo,
    )
    {}

    public function execute(Like $like): void
    {
        $likeId = $like->getId();
        $likeByUserPostId = $this->likeRepo->getLike($like->userId, $like->postId);
        
        if
        (
            $likeId !== null &&
            ($this->likeRepo->getLikeById($like->getId()) === null ||
            $likeByUserPostId === null)
        )
        {
            throw new BusinessException("Like with id: $likeId not found");
        }

        if($this->userRepo->getUserById($like->userId) === null)
            throw new BusinessException("User with id: $like->userId not found");

        if($this->postRepo->getPostById($like->postId) === null)
            throw new BusinessException("Post with id: $like->postId not found");
        
        if($likeByUserPostId !== null && $like->getId() === null)
            $like->setId($likeByUserPostId->getId());

        $this->likeRepo->saveLike($like);
    }
}