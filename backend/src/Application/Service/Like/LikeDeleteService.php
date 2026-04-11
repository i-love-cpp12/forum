<?php
declare(strict_types=1);

namespace src\Application\Service\Like;

use InvalidArgumentException;
use PDO;
use src\Application\DTO\Like\LikeDTO;
use src\Application\Service\ServiceHelper;
use src\Domain\Entity\Like;
use src\Domain\Entity\LikeType;
use src\Domain\Entity\PostType;
use src\Domain\Repository\LikeRepositoryInterface;
use src\Domain\Repository\PostRepositoryInterface;
use src\Shared\Exception\BusinessException\BusinessException;
use src\Shared\Exception\BusinessException\EntityNotFoundException;
use src\Shared\Exception\BusinessException\InvalidValueException;
use src\Shared\String\StringHelper;
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
        
        if(($likeType = LikeType::tryFrom($DTO->likeType)) === null)
            throw new InvalidArgumentException("Like type $DTO->likeType is not valid");

        if(($postType = PostType::tryFrom($DTO->postType)) === null)
            throw new InvalidArgumentException("Post type $DTO->postType is not valid");

        
        if
        (
            ($like = $this->likeRepo->getLike($DTO->userId, $DTO->postId)) === null || $likeType !== $like->type
        )
        {

            throw new EntityNotFoundException(StringHelper::capitalize(Like::likeTypeToString($likeType)), "($DTO->userId, $DTO->postId)", "(UserId, PostId)");
        }

        $post = $this->postRepo->getPostById($DTO->postId);


        ServiceHelper::validatePostType($postType, $post);

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