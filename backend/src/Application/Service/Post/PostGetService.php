<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Entity\Post;
use src\Domain\Repository\PostRepositoryInterface;
use src\Shared\Exception\BussinessException\EntityNotFoundException;

class PostGetService
{
    public function __construct(private PostRepositoryInterface $postRepo){}

    public function execute(int $postId): ?Post
    {
        $post = $this->postRepo->getPostById($postId);
        if($post === null)
            throw new EntityNotFoundException("Post", $postId);
        return $post;
    }
}