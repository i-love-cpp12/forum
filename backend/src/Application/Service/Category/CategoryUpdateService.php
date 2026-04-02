<?php
declare(strict_types=1);

namespace src\Application\Service\Category;

use src\Domain\Repository\CategoryRepositoryInterface;

require_once(__DIR__ . "/../../../../autoload.php");

class CategoryUpdateService
{
    public function __construct
    (
        private CategoryRepositoryInterface $categoryRepo
    )
    {}

    public function execute(CategoryUpdateDTO $DTO): void
    {
        
    }
}