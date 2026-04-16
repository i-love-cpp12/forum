<?php
declare(strict_types=1);

namespace src\Domain\Repository;

use src\Domain\Entity\Post;
use src\Application\DTO\Post\PostGetAllDTO;
use src\Application\DTO\Post\PostGetCommentsDTO;
use src\Domain\Entity\LikeType;

interface PostRepositoryInterface
{
    public function savePost(Post $post): void;
    /** @return Post[]*/
    public function getAllPosts(PostGetAllDTO $DTO): array;
    public function getPostById(int $id): ?Post;
    public function deletePost(int $id): void;
    /** @return Post[]*/
    public function getCommentsForPost(PostGetCommentsDTO $DTO): array;
    public function likePost(int $postId, LikeType $likeType): void;
    public function deleteLike(int $postId, LikeType $likeType): void;
    public function addComment(int $postId): void;
    public function deleteComment(int $postId): void;
}