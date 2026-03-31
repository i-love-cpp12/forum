<?php
declare(strict_types=1);

namespace src\Application\DTO\Post;

enum Sort: int
{
    case latest = 0;
    case eldest = 1;
    case mostLiked = 2;
    case leastLiked = 3;
    case mostDisliked = 4;
    case leastDisliked = 5;
}

class PostGetAllDTO
{
    public function __construct
    (
        public readonly ?int $page = null,
        public readonly ?int $limit = null,
        public readonly ?string $search = null,
        public readonly ?int $categoryId = null,
        public readonly ?int $authorId = null,
        public readonly Sort $sort = Sort::latest
    )
    {
        
    }
}