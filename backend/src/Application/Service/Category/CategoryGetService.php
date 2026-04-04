<?php
declare(strict_types=1);

namespace src\Application\Service\Category;

use src\Domain\Entity\PostCategory;
use src\Domain\Repository\CategoryRepositoryInterface;
use src\Shared\Exception\BusinessException;

require_once(__DIR__ . "/../../../../autoload.php");

class CategoryGetService
{
    public function __construct
    (
        private CategoryRepositoryInterface $categoryRepo
    )
    {}

    public function execute(int $categoryId): PostCategory
    {
        $category = $this->categoryRepo->getCategoryById($categoryId);

        if($category === null)
            throw new BusinessException("Category with id: $categoryId not found", 404);

        return $category;
    }
}