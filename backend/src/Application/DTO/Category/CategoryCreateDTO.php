<?php
declare(strict_types=1);

namespace src\Application\DTO\Category;

class CategoryCreateDTO
{
    public function __construct
    (
        readonly public int $loggedUserRole,
        readonly public string $categoryName
    )
    {
        
    }
}