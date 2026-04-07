<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\Dummy;

use src\Domain\Repository\CategoryRepositoryInterface;

class DummyCategoryRepository implements CategoryRepositoryInterface
{
    public function saveCategory(PostCategory $category): void
    {

    }
    public function getCategoryById(int $categoryId): ?PostCategory
    {
        return null;
    }
    public function getCategoryByName(string $name): ?PostCategory
    {
        return null;
    }
    /** @return PostCategory[] */
    public function getAllCategories(): array
    {
        return [];
    }
    public function deleteCategory(int $categoryId): void
    {
        
    }
}