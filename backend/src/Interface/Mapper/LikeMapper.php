<?php
declare(strict_types=1);

namespace src\Interface\Mapper;

use src\Domain\Entity\Like;

class LikeMapper
{
    public static function map(Like $like): array
    {
        return [
            ...EntityMapper::map($like),
            "postId" => $like->postId,
            "userId" => $like->userId,
            "type" => Like::likeTypeToString($like->type)
        ];
    }
}