<?php
declare(strict_types=1);

namespace src\Application\DTO\Post;

class PostGetCommentsDTO
{
    public function __construct
    (
        public readonly int $postId,
        public readonly ?int $page = null,
        public readonly ?int $limit = null
    )
    {
        
    }
}