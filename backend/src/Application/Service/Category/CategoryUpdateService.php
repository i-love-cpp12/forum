<?php
declare(strict_types=1);

namespace src\Application\Service\Category;

use src\Domain\Entity\PostCategory;
use src\Domain\Entity\UserRole;

use src\Application\Service\ServiceHelper;

use src\Domain\Repository\CategoryRepositoryInterface;
use src\Application\DTO\Category\CategoryUpdateDTO;

use src\Shared\Exception\BusinessException\EntityNotFoundException;
use src\Shared\Exception\BusinessException\InvalidValueException;
use src\Shared\Exception\BusinessException\BusinessException;

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
        ServiceHelper::authorizeAction(
            UserRole::from($DTO->loggedUserRole),
            null,
            null,
            UserRole::admin
        );

        if($DTO->newCategoryName === null)
            throw new BusinessException("To update category you have to provide some of allowed data: categoryName (string)");
        if(!PostCategory::validateCategoryName($DTO->newCategoryName))
            throw new InvalidValueException("New categoryName", $DTO->newCategoryName, PostCategory::getCategoryNameValidateMessage());

        $category = $this->categoryRepo->getCategoryById($DTO->categoryToUpdateId);
        
        if($category === null)
            throw new EntityNotFoundException("Cateogry", $DTO->categoryToUpdateId);

        $category->setCategoryName($DTO->newCategoryName);
        
        $this->categoryRepo->saveCategory($category);
    }
}