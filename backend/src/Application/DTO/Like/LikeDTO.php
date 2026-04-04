<?php
declare(strict_types=1);

namespace src\Application\DTO\Like;

class LikeAddDTO
{
    public function __construct
    (
        readonly public int $postId,
        readonly public int $userId,
        readonly public int $likeType
    )
    {
        
    }
}