<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\Dummy;

use src\Application\DTO\Like\LikeDTO;
use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Entity\Post;
use src\Application\DTO\Post\PostGetAllDTO;
use src\Domain\Entity\Comment;
use src\Domain\Entity\LikeType;
use src\Shared\Array\ArrayHelper;
use src\Domain\Entity\Like;
use src\Domain\Entity\PostCategory;

class DummyPostRepository implements PostRepositoryInterface
{
    /** @var Post[] $posts */
    private array $posts;
    private int $nextPostId;

    public function __construct()
    {
        $this->posts = [];
        $this->nextPostId = 0;
        
        $i = 0;

        $this->savePost(new Post(null, null, 10, "Nowy post $i", "Content nowego postu $i", [new PostCategory(0, "category 0")], 3, 4, 2));
        ++$i;
        $this->savePost(new Post(null, null, 10, "Nowy post $i", "Content nowego postu $i", [new PostCategory(1, "category 1"), new PostCategory(2, "category 2")]));
        ++$i;
        $this->savePost(new Post(null, null, 11, "Nowy post $i", "Content nowego postu $i", [new PostCategory(1, "category 1"), new PostCategory(2, "category 2")]));
        ++$i;
        $this->savePost(new Comment(null, 0, 11, "Comment 1"));
        $this->savePost(new Comment(null, 0, 12, "Comment 2", 1, 1));

    }
    public function savePost(Post $post): void
    {
        DummyRepositoryHelper::saveEntity($post, $this->posts, $this->nextPostId);
    }
    /** @return Post[]*/
    public function getAllPosts(PostGetAllDTO $DTO): array
    {
        return DummyRepositoryHelper::getAllEntities($this->posts);
    }
    public function getPostById(int $id): ?Post
    {
        return DummyRepositoryHelper::getEntityById($id, $this->posts);
    }
    public function deletePost(int $id): void
    {
        DummyRepositoryHelper::deleteEntity($id, $this->posts);
    }
    /** @return Post[]*/
    public function getCommentsForPost(int $postId): array
    {
        return ArrayHelper::findAll($this->posts, fn(Post $post) => ($post->parentPostId === $postId));
    }
    public function likePost(int $postId, LikeType $likeType): void
    {
        /** @var Post */
        $post = DummyRepositoryHelper::getEntityById($postId, $this->posts);

        $post->like($likeType);

    }
    public function deleteLike(int $postId, LikeType $likeType): void
    {
        /** @var Post */
        $post = DummyRepositoryHelper::getEntityById($postId, $this->posts);

        $post->deleteLike($likeType);
    }
}