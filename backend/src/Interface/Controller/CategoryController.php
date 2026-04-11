<?php
declare(strict_types=1);
namespace src\Interface\Controller;

use src\Application\DTO\Category\CategoryCreateDTO;
use src\Application\DTO\Category\CategoryDeleteDTO;
use src\Application\DTO\Category\CategoryUpdateDTO;
use src\Application\Service\Category\CategoryCreateService;
use src\Application\Service\Category\CategoryDeleteService;
use src\Application\Service\Category\CategoryGetAllService;
use src\Application\Service\Category\CategoryUpdateService;
use src\Infrastructure\Http\Request;
use src\Infrastructure\Http\Respond;
use src\Interface\Mapper\CategoryMapper;
use src\Shared\Exception\ExceptionHandler;
use src\Domain\Entity\PostCategory;
use src\Domain\Entity\User;
use src\Shared\Exception\BusinessException\RequestDataFormatException;
use Throwable;

class CategoryController
{
    public function __construct
    (
        private Request $request,
        private CategoryGetAllService $categoryGetAllService,
        private CategoryCreateService $categoryCreateService,
        private CategoryUpdateService $categoryUpdateService,
        private CategoryDeleteService $categoryDeleteService
    ){}
    
    public function getAllCategories(): void
    {
        /** @var PostCategory[] $categories */
        $categories = [];

        try
        {
            $categories = $this->categoryGetAllService->execute();
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        $categoriesMapped = [];

        foreach($categories as $category)
        {
            $categoriesMapped[] = CategoryMapper::map($category);
        }

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Getting all categories successful",
                        "categories" => $categoriesMapped
                    ]
            ]
        );
    }
    public function createCategory(): void
    {
        /** @var User $loggedUser */
        $loggedUser = $this->request->getFromState("user");
        $categoryName = $this->request->body["categoryName"] ?? null;

        try
        {
            if(!$categoryName || !is_string($categoryName))
                throw new RequestDataFormatException("categoryName", "string");

            $CategoryCreateDTO = new CategoryCreateDTO($loggedUser->role->value, $categoryName);
            $this->categoryCreateService->execute($CategoryCreateDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Category with categoryName: $categoryName created successfully"
                    ]
            ]
        );
    }
    public function updateCategory(string $categoryId): void
    {
        /** @var User $loggedUser */
        $loggedUser = $this->request->getFromState("user");

        $categoryName = $this->request->body["categoryName"] ?? null;

        try
        {
            if(!ctype_digit($categoryId))
                throw new RequestDataFormatException("categoryId", "int", true);

            $categoryId = intval($categoryId);

            if(!$categoryName || !is_string($categoryName))
                throw new RequestDataFormatException("categoryName", "string");

            $CategoryUpdateDTO = new CategoryUpdateDTO($categoryId, $loggedUser->role->value, $categoryName);
            $this->categoryUpdateService->execute($CategoryUpdateDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Category with categoryId: $categoryId updated successfully",
                    ]
            ]
        );
    }
    public function deleteCategory(string $categoryId): void
    {
        /** @var User $loggedUser */
        $loggedUser = $this->request->getFromState("user");

        try
        {
            if(!ctype_digit($categoryId))
                throw new RequestDataFormatException("categoryId", "int", true);

            $categoryId = intval($categoryId);

            $CategoryDeleteDTO = new CategoryDeleteDTO($categoryId, $loggedUser->role->value);
            $this->categoryDeleteService->execute($CategoryDeleteDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Category with categoryId: $categoryId deleted successfully",
                    ]
            ]
        );
    }
}