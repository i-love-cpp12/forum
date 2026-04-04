<?php
declare(strict_types=1);

namespace src\Application\Service\Category;

use src\Domain\Repository\CategoryRepositoryInterface;
use src\Application\DTO\Category\CategoryDeleteDTO;
use src\Domain\Entity\UserRole;
use src\Shared\Exception\BusinessException;

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
        if($DTO->loggedUserRole !== UserRole::admin)
            throw new BusinessException("You are not authorized user to make this action", 401);

        $cateogry = $this->categoryRepo->getCategoryById($DTO->categoryToDeleteId);
          
        if($cateogry === null)
            throw new BusinessException("Post with id: $DTO->categoryToDeleteId not found", 404);

        $this->categoryRepo->deleteCategory($cateogry->getId());
    }
}