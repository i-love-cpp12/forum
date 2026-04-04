<?php
declare(strict_types=1);

namespace src\Application\DTO\Category;

class CategoryDeleteDTO
{
    public function __construct
    (
        readonly public int $categoryToDeleteId,
        readonly public int $loggedUserRole,
    )
    {
        
    }
}