<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Entity\Post;
use src\Domain\Repository\PostRepositoryInterface;
use src\Shared\Exception\BusinessException\EntityNotFoundException;

class PostGetCommentsService
{
    public function __construct(private PostRepositoryInterface $postRepo){}

    /** @return Post[] */
    public function execute(int $postId): array
    {
        $post = $this->postRepo->getCommentsForPost($postId);
        if($post === null)
            throw new EntityNotFoundException("Post", $postId);
        $comments = $this->postRepo->getCommentsForPost($postId);
        return $comments;
    }
}