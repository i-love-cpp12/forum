<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\Dummy;

use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Entity\Post;
use src\Application\DTO\Post\PostGetAllDTO;
use src\Domain\Entity\LikeType;

class DummyPostRepository implements PostRepositoryInterface
{
    public function __construct()
    {
        
    }
    public function savePost(Post $post): void
    {

    }
    /** @return Post[]*/
    public function getAllPosts(PostGetAllDTO $DTO): array
    {
        return [];
    }
    public function getPostById(int $id): ?Post
    {
        return null;
    }
    public function deletePost(int $id): void
    {

    }
    /** @return Post[]*/
    public function getCommentsForPost(int $postId): array
    {
        return [];
    }
    public function likePost(LikeType $likeType): void
    {

    }
    public function deleteLike(LikeType $likeType): void
    {

    }
}