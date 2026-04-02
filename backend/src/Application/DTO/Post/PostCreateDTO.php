<?php
declare(strict_types=1);

namespace src\Application\DTO\Post;

class PostCreateDTO
{
    /** @param int[] $categories */
    public function __construct
    (
        readonly public ?int $parentPostId,
        readonly public int $userId,
        readonly public ?string $header,
        readonly public string $content,
        readonly public ?array $categories
    )
    {
        
    }
}