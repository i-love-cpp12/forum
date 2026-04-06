<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Entity\User;
use src\Domain\Entity\UserRole;

use src\Application\Service\ServiceHelper;

use src\Domain\Repository\PostRepositoryInterface;
use src\Application\DTO\Post\PostDeleteDTO;

use src\Shared\Exception\BusinessException\EntityNotFoundException;

class PostDeleteService
{
    public function __construct(private PostRepositoryInterface $postRepo){}

    public function execute(PostDeleteDTO $DTO): void
    {
        ServiceHelper::authorizeAction(
            UserRole::from($DTO->loggedUserRole),
            $DTO->loggedUserId,
            $DTO->postAuthorId
        );

        $post = $this->postRepo->getPostById($DTO->postToDeleteId);
          
        if($post === null)
            throw new EntityNotFoundException("Post", $DTO->postToDeleteId);

        $this->postRepo->deletePost($post->getId());
    }
}