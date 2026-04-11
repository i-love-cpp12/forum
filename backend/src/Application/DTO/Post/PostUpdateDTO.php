<?php
declare(strict_types=1);

namespace src\Application\DTO\Post;

class PostUpdateDTO
{
    /** @param int[] $newCategories
    *   @param int[] $categoriesToDelete
    */
    public function __construct
    (
        readonly public int $postType,
        readonly public int $postToUpdateId,
        readonly public int $loggedUserId,
        readonly public int $loggedUserRole,
        readonly public ?string $newHeader,
        readonly public ?string $newContent,
        readonly public ?array $categoriesToAdd,
        readonly public ?array $categoriesToDelete
    )
    {
        
    }
}