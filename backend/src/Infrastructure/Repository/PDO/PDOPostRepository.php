<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\PDO;

use PDO;
use src\Application\DTO\Like\LikeDTO;
use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Entity\Post;
use src\Application\DTO\Post\PostGetAllDTO;
use src\Application\DTO\Post\PostGetCommentsDTO;
use src\Domain\Entity\Comment;
use src\Domain\Entity\LikeType;
use src\Shared\Array\ArrayHelper;
use src\Domain\Entity\Like;
use src\Domain\Entity\PostCategory;

class PDOPostRepository implements PostRepositoryInterface
{

    public function __construct(private PDO $conn)
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
    public function getCommentsForPost(PostGetCommentsDTO $DTO): array
    {
        return [];
    }
    public function likePost(int $postId, LikeType $likeType): void
    {

    }
    public function deleteLike(int $postId, LikeType $likeType): void
    {
        
    }
}