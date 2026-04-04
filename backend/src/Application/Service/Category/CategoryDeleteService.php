<?php
declare(strict_types=1);

namespace src\Application\Service\Category;

use src\Domain\Entity\UserRole;

use src\Application\Service\ServiceHelper;

use src\Domain\Repository\CategoryRepositoryInterface;
use src\Application\DTO\Category\CategoryDeleteDTO;

use src\Shared\Exception\BussinessException\EntityNotFoundException;

require_once(__DIR__ . "/../../../../autoload.php");

class CategoryDeleteService
{
    public function __construct
    (
        private CategoryRepositoryInterface $categoryRepo
    )
    {}

    public function execute(CategoryDeleteDTO $DTO): void
    {
        ServiceHelper::authorizeAction(
            UserRole::from($DTO->loggedUserRole),
            null,
            null,
            UserRole::admin
        );

        $category = $this->categoryRepo->getCategoryById($DTO->categoryToDeleteId);
          
        if($category === null)
            throw new EntityNotFoundException("Category", $DTO->categoryToDeleteId);

        $this->categoryRepo->deleteCategory($category->getId());
    }
}