<?php
declare(strict_types=1);

namespace src\Application\DTO\Post;

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