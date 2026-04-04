<?php
declare(strict_types=1);

namespace src\Application\Service\Category;

use src\Domain\Repository\CategoryRepositoryInterface;
use src\Application\DTO\Category\CategoryCreateDTO;
use src\Domain\Entity\PostCategory;
use src\Domain\Entity\UserRole;
use src\Shared\Exception\BusinessException;

require_once(__DIR__ . "/../../../../autoload.php");

class CategoryCreateService
{
    public function __construct
    (
        private CategoryRepositoryInterface $categoryRepo
    )
    {}

    public function execute(CategoryCreateDTO $DTO): void
    {
        if($DTO->loggedUserRole !== UserRole::admin)
            throw new BusinessException("You are not authorized user to make this action", 401);

        if(!PostCategory::validateCategoryName($DTO->categoryName))
            throw new BusinessException("CategoryName: $DTO->categoryName is not valid");

        $category = new PostCategory(null, $DTO->categoryName);
        $this->categoryRepo->saveCategory($category);
    }
}