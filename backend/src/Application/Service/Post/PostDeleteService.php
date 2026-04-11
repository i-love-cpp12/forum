<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

use InvalidArgumentException;
use src\Domain\Entity\UserRole;

use src\Application\Service\ServiceHelper;

use src\Domain\Repository\PostRepositoryInterface;
use src\Application\DTO\Post\PostDeleteDTO;
use src\Domain\Entity\PostType;
use src\Shared\Exception\BusinessException\BusinessException;
use src\Shared\Exception\BusinessException\EntityNotFoundException;
use src\Shared\Exception\BusinessException\InvalidValueException;

class PostDeleteService
{
    public function __construct(private PostRepositoryInterface $postRepo){}

    public function execute(PostDeleteDTO $DTO): void
    {
        $post = $this->postRepo->getPostById($DTO->postToDeleteId);

        if($post === null)
            throw new EntityNotFoundException("Post", $DTO->postToDeleteId);

        if(($postType = PostType::tryFrom($DTO->postType)) === null)
            throw new InvalidArgumentException("Post type $DTO->postType is not valid");

        ServiceHelper::validatePostType($postType, $post);

        ServiceHelper::authorizeAction(
            UserRole::from($DTO->loggedUserRole),
            $DTO->loggedUserId,
            $post->userId
        );

        $this->postRepo->deletePost($post->getId());
    }
}