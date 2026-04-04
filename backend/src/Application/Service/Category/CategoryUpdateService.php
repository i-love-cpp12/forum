<?php
declare(strict_types=1);

namespace src\Application\Service\Category;

use src\Domain\Repository\CategoryRepositoryInterface;
use src\Application\DTO\Category\CategoryUpdateDTO;
use src\Domain\Entity\PostCategory;
use src\Domain\Entity\UserRole;
use src\Shared\Exception\BusinessException;

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
        if($DTO->loggedUserRole !== UserRole::admin)
            throw new BusinessException("You are not authorized user to make this action", 401);

        if(!PostCategory::validateCategoryName($DTO->newCategoryName))
            throw new BusinessException("CategoryName: $DTO->newCategoryName is not valid");

        $category = $this->categoryRepo->getCategoryById($DTO->categoryToUpdateId);
        
        if($category === null)
            throw new BusinessException("Category with id: $DTO->categoryToUpdateId not found", 404);

        $category->setCategoryName($DTO->newCategoryName);
        
        $this->categoryRepo->saveCategory($category);
    }
}