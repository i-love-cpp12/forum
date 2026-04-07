<?php
declare(strict_types=1);

namespace src\Domain\Repository;

require_once(__DIR__ . "/../../../autoload.php");

use src\Domain\Entity\Post;
use src\Application\DTO\Post\PostGetAllDTO;
use src\Domain\Entity\LikeType;

interface PostRepositoryInterface
{
    public function savePost(Post $post): void;
    /** @return Post[]*/
    public function getAllPosts(PostGetAllDTO $DTO): array;
    public function getPostById(int $id): ?Post;
    public function deletePost(int $id): void;
    public function getCommentsForPost(int $postId): array;
    public function likePost(LikeType $likeType): void;
    public function deleteLike(LikeType $likeType): void;
}