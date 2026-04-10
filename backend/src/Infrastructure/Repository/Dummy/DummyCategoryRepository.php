<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\Dummy;

use src\Domain\Repository\CategoryRepositoryInterface;
use src\Domain\Entity\PostCategory;
use src\Shared\Array\ArrayHelper;

class DummyCategoryRepository implements CategoryRepositoryInterface
{
    /** @var PostCategory[] $categories */
    private array $categories;
    private int $nextCategoryId;

    public function __construct()
    {
        $this->categories = [];
        $this->nextCategoryId = 0;
        
        for($i = 0; $i < 10; ++$i)
            $this->saveCategory(new PostCategory(null, "category $i"));
    }

    public function saveCategory(PostCategory $category): void
    {
        DummyRepositoryHelper::saveEntity($category, $this->categories, $this->nextCategoryId);
    }
    public function getCategoryById(int $categoryId): ?PostCategory
    {
        return DummyRepositoryHelper::getEntityById($categoryId, $this->categories);
    }
    public function getCategoryByName(string $name): ?PostCategory
    {
        return ArrayHelper::find(
            $this->categories,
            fn(PostCategory $category) =>
                ($category->categoryName === $name));
    }
    /** @return PostCategory[] */
    public function getAllCategories(): array
    {
        return DummyRepositoryHelper::getAllEntities($this->categories);
    }
    public function deleteCategory(int $categoryId): void
    {
        DummyRepositoryHelper::deleteEntity($categoryId, $this->categories);
    }
}