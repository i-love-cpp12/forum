<?php
declare(strict_types=1);

namespace src\Interface\Mapper;

use src\Domain\Entity\Post;

class PostMapper
{
    public static function map(Post $post): array
    {
        $categories = [];
        foreach($post->getCategories() as $category)
        {
            $categories[] = CategoryMapper::map($category);
        }

        return [
            ...EntityMapper::map($post),
            "parentPostId" => $post->parentPostId,
            "userId" => $post->userId,
            "header" => $post->getHeader(),
            "content" => $post->getContent(),
            "categories" => $categories,
            "likeCount" => $post->getLikeCount(),
            "dislikeCount" => $post->getDislikeCount(),
            "commentCount" => $post->getCommentCount()
        ];
    }
}