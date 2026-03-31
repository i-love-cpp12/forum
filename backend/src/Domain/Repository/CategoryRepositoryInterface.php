<?php
declare(strict_types=1);

namespace src\Domain\Repository;

use src\Domain\Entity\PostCategory;

require_once(__DIR__ . "/../../../autoload.php");


interface CategoryRepositoryInterface
{
    public function saveCategory(PostCategory $category): void;
    public function getCategory(int $categoryId): ?PostCategory;
    public function deleteCategory(int $categoryId): void;
}