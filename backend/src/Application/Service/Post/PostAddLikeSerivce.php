<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

require_once(__DIR__ . "/../../../../autoload.php");

use InvalidArgumentException;
use src\Application\DTO\Like\LikeAddDTO;
use src\Domain\Entity\Like;
use src\Domain\Entity\LikeType;
use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Exception\BusinessException\EntityNotFoundException;

class PostAddLikeSerivce
{
    public function __construct
    (
        private UserRepositoryInterface $userRepo,
        private PostRepositoryInterface $postRepo
    ){}

    public function execute(LikeAddDTO $DTO): void
    {
        if(LikeType::tryFrom($DTO->likeType) === null)
            throw new InvalidArgumentException("Like type is not valid");

        if($this->userRepo->getUserById($DTO->userId) === null)
            throw new EntityNotFoundException("User", $DTO->userId);

        if($this->postRepo->getPostById($DTO->postId) === null)
            throw new EntityNotFoundException("Post", $DTO->postId);

        $like = new Like(null, $DTO->postId, $DTO->userId, LikeType::from($DTO->likeType));
        $this->postRepo->likePost($like);
    }
}