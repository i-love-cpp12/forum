<?php
declare(strict_types=1);

namespace src\Application\DTO\Post;

enum Sort: string
{
    case latest = "latest";
    case eldest = "eldest";
    case mostLiked = "mostLiked";
    case leastLiked = "leastLiked";
    case mostDisliked = "mostDisliked";
    case leastDisliked = "leastDisliked";
}

class PostGetAllDTO
{
    public function __construct
    (
        public readonly ?int $page = null,
        public readonly ?int $limit = null,
        public readonly ?string $search = null,
        public readonly ?string $category = null,
        public readonly ?string $author = null,
        public readonly ?string $sort = null
    )
    {
        
    }
}