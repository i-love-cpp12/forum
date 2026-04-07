<?php
declare(strict_types=1);

namespace src\Application\Service\Category;

use src\Domain\Entity\PostCategory;
use src\Domain\Repository\CategoryRepositoryInterface;

class CategoryGetAllService
{
    public function __construct
    (
        private CategoryRepositoryInterface $categoryRepo
    )
    {}

    /** @return PostCategory[] */
    public function execute(): array
    {
        return $this->categoryRepo->getAllCategories();
    }
}