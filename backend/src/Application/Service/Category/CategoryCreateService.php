<?php
declare(strict_types=1);

namespace src\Application\Service\Category;

use src\Domain\Entity\PostCategory;
use src\Domain\Entity\User;
use src\Domain\Entity\UserRole;

use src\Domain\Repository\CategoryRepositoryInterface;
use src\Application\DTO\Category\CategoryCreateDTO;

use src\Shared\Exception\BussinessException\AuthException;
use src\Shared\Exception\BussinessException\InvalidValueException;

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
            throw new AuthException(User::roleToString(UserRole::from($DTO->loggedUserRole)));

        if(!PostCategory::validateCategoryName($DTO->categoryName))
            throw new InvalidValueException("CategoryName", $DTO->categoryName);

        $category = new PostCategory(null, $DTO->categoryName);
        $this->categoryRepo->saveCategory($category);
    }
}