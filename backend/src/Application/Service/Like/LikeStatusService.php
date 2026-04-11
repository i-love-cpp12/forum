<?php
declare(strict_types=1);

namespace src\Application\Service\Like;

use InvalidArgumentException;
use src\Application\Service\ServiceHelper;
use src\Domain\Entity\Like;
use src\Domain\Entity\PostType;
use src\Domain\Repository\LikeRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;
use src\Domain\Repository\PostRepositoryInterface;

use src\Shared\Exception\BusinessException\EntityNotFoundException;

class LikeStatusService
{
    public function __construct
    (
        private LikeRepositoryInterface $likeRepo,
        private PostRepositoryInterface $postRepo,
        private UserRepositoryInterface $userRepo,
    )
    {}

    public function execute(int $userId, int $postId, int $postTypeInt): ?Like
    {
        if(($postType = PostType::tryFrom($postTypeInt)) === null)
            throw new InvalidArgumentException("Post type $postTypeInt is not valid");

        if($this->userRepo->getUserById($userId) === null)
            throw new EntityNotFoundException("User", $userId);

        if(($post = $this->postRepo->getPostById($postId)) === null)
            throw new EntityNotFoundException("Post", $postId);

        ServiceHelper::validatePostType($postType, $post);
        
        return $this->likeRepo->getLike($userId, $postId);
    }
}