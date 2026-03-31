<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Application\DTO\Post\PostDeleteDTO;
use src\Application\Service\ServiceHelper;
use src\Domain\Entity\UserRole;
use src\Domain\Repository\PostRepositoryInterface;
use src\Shared\Exception\BusinessException;

class PostDeleteService
{
    public function __construct(private PostRepositoryInterface $postRepo){}

    public function execute(PostDeleteDTO $DTO): void
    {
        if(!ServiceHelper::authUserAction($DTO->loggedUserId, $DTO->loggedUserRole, $DTO->postAuthorId))
            throw new BusinessException("You are not authorized user to make this action", 401);

        $post = $this->postRepo->getPostById($DTO->postToDeleteId);
          
        if($post === null)
            throw new BusinessException("Post with id: $DTO->postToDeleteId not found", 404);

        $this->postRepo->deletePost($post->getId());
    }
}