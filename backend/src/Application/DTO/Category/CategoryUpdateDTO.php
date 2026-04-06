<?php
declare(strict_types=1);

namespace src\Application\DTO\Category;

class CategoryUpdateDTO
{
    public function __construct
    (
        readonly public int $categoryToUpdateId,
        readonly public int $loggedUserRole,
        readonly public ?string $newCategoryName
    )
    {
        
    }
}