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
        if(LikeType::tryFrom($DTO->likeType) === null)
            throw new InvalidArgumentException("Like type is not valid");

        $like = $this->likeRepo->getLike($DTO->userId, $DTO->postId);
        $likeId = $like !== null ? $like->getId() : null;

        if($this->userRepo->getUserById($DTO->userId) === null)
            throw new EntityNotFoundException("User", $DTO->userId);

        if($this->postRepo->getPostById($DTO->postId) === null)
            throw new EntityNotFoundException("Post", $DTO->postId);

        $like = new Like($likeId, $DTO->postId, $DTO->userId, LikeType::from($DTO->likeType));

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