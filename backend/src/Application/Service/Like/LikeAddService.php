<?php
declare(strict_types=1);

namespace src\Application\Service\Like;

use src\Application\DTO\Like\LikeAddDTO;
use src\Domain\Entity\LikeType;
use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Repository\LikeRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;

use src\Shared\Exception\BusinessException\EntityNotFoundException;
use InvalidArgumentException;
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

    public function execute(LikeAddDTO $DTO): void
    {
        if(LikeType::tryFrom($DTO->likeType) === null)
            throw new InvalidArgumentException("Like type is not valid");

        $likeId = $this->likeRepo->getLike($DTO->userId, $DTO->postId);
        

        if($this->userRepo->getUserById($DTO->userId) === null)
            throw new EntityNotFoundException("User", $DTO->userId);

        if($this->postRepo->getPostById($DTO->postId) === null)
            throw new EntityNotFoundException("Post", $DTO->postId);

        $like = new Like($likeId, $DTO->postId, $DTO->userId, LikeType::from($DTO->likeType));

        $this->likeRepo->saveLike($like);
    }
}