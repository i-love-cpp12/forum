<?php
declare(strict_types=1);

namespace src\Application\Service\Like;

use PDO;
use src\Application\DTO\Like\LikeDTO;
use src\Application\Service\ServiceHelper;
use src\Domain\Entity\LikeType;
use src\Domain\Entity\PostType;
use src\Domain\Repository\LikeRepositoryInterface;
use src\Domain\Repository\PostRepositoryInterface;
use src\Shared\Exception\BusinessException\EntityNotFoundException;
use src\Shared\Exception\BusinessException\InvalidValueException;
use Throwable;

class LikeDeleteService
{
    public function __construct
    (

        private PDO $conn,
        private LikeRepositoryInterface $likeRepo,
        private PostRepositoryInterface $postRepo
    )
    {}

    public function execute(LikeDTO $DTO): void
    {
        
        if(($like = $this->likeRepo->getLike($DTO->userId, $DTO->postId)) === null)
            throw new EntityNotFoundException("Like", "($DTO->userId, $DTO->postId)", "(UserId, PostId)");

        $post = $this->postRepo->getPostById($DTO->postId);

        if(($likeType = LikeType::tryFrom($DTO->likeType)) === null)
            throw new InvalidValueException("Like type", $DTO->likeType);

        if(($postType = PostType::tryFrom($DTO->postType)) === null)
            throw new InvalidValueException("postType", $DTO->postType);

        ServiceHelper::validatePostType($postType, $post);
        ServiceHelper::validateLikeType($likeType, $like);

        $this->conn->beginTransaction();
        try
        {
            $this->likeRepo->deleteLike($like->userId, $like->postId);
            $this->postRepo->deleteLike($like->postId, $like->type);
            $this->conn->commit();
        }
        catch(Throwable $e)
        {
            $this->conn->rollBack();
            throw $e;
        }
    }
}