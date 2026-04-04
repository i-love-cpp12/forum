<?php
declare(strict_types=1);

namespace src\Application\Service\Category;

use src\Domain\Entity\PostCategory;
use src\Domain\Entity\User;
use src\Domain\Entity\UserRole;

use src\Domain\Repository\CategoryRepositoryInterface;
use src\Application\DTO\Category\CategoryUpdateDTO;

use src\Shared\Exception\BussinessException\AuthException;
use src\Shared\Exception\BussinessException\EntityNotFoundException;
use src\Shared\Exception\BussinessException\InvalidValueException;

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
            throw new AuthException(User::roleToString(UserRole::from($DTO->loggedUserRole)));


        if(!PostCategory::validateCategoryName($DTO->newCategoryName))
            throw new InvalidValueException("New categoryName", $DTO->newCategoryName);

        $category = $this->categoryRepo->getCategoryById($DTO->categoryToUpdateId);
        
        if($category === null)
            throw new EntityNotFoundException("Cateogry", $DTO->categoryToUpdateId);

        $category->setCategoryName($DTO->newCategoryName);
        
        $this->categoryRepo->saveCategory($category);
    }
}