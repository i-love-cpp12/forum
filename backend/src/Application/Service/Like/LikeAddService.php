<?php
declare(strict_types=1);

namespace src\Application\Service\Like;

use src\Domain\Entity\Like;

use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Repository\LikeRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;

use src\Shared\Exception\BussinessException\EntityNotFoundException;

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
            throw new EntityNotFoundException("Like", $likeId);
        }

        if($this->userRepo->getUserById($like->userId) === null)
            throw new EntityNotFoundException("User", $like->userId);

        if($this->postRepo->getPostById($like->postId) === null)
            throw new EntityNotFoundException("Post", $like->postId);
        
        if($likeByUserPostId !== null && $like->getId() === null)
            $like->setId($likeByUserPostId->getId());

        $this->likeRepo->saveLike($like);
    }
}