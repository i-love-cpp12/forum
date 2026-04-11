<?php
declare(strict_types=1);

namespace src\Application\Service\Like;

use src\Application\DTO\Like\LikeDTO;
use src\Domain\Entity\LikeType;
use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Repository\LikeRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;

use src\Shared\Exception\BusinessException\EntityNotFoundException;
use InvalidArgumentException;
use src\Domain\Entity\Like;
use PDO;
use src\Application\Service\ServiceHelper;
use src\Domain\Entity\PostType;
use src\Shared\Exception\BusinessException\BusinessException;
use src\Shared\Exception\BusinessException\InvalidValueException;
use Throwable;

class LikeAddService
{
    public function __construct
    (
        private PDO $conn,
        private LikeRepositoryInterface $likeRepo,
        private PostRepositoryInterface $postRepo,
        private UserRepositoryInterface $userRepo,
    )
    {}

    public function execute(LikeDTO $DTO): void
    {
        if(($likeType = LikeType::tryFrom($DTO->likeType)) === null)
            throw new InvalidValueException("Like type", $DTO->likeType);

        if(($postType = PostType::tryFrom($DTO->postType)) === null)
            throw new InvalidValueException("postType", $DTO->postType);

        $like = $this->likeRepo->getLike($DTO->userId, $DTO->postId);
        if($like !== null)
        {
            if($like->type === $likeType)
                throw new BusinessException("you already" . $likeType->value . "d this post");
            $likeId = $like->getId();
        }
        if($this->userRepo->getUserById($DTO->userId) === null)
            throw new EntityNotFoundException("User", $DTO->userId);

        if(($post = $this->postRepo->getPostById($DTO->postId)) === null)
            throw new EntityNotFoundException("Post", $DTO->postId);

        ServiceHelper::validatePostType($postType, $post);
        
        $like = new Like($likeId, $DTO->postId, $DTO->userId, $likeType);

        $this->conn->beginTransaction();
        try
        {
            $this->likeRepo->saveLike($like);
            $this->postRepo->likePost($DTO->postId, $like->type);
            $this->conn->commit();
        }
        catch(Throwable $e)
        {
            $this->conn->rollBack();
            throw $e;
        }
    }
}