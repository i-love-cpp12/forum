<?php
declare(strict_types=1);

namespace src\Interface\Mapper;

use src\Domain\Entity\PostCategory;

class CategoryMapper
{
    public static function map(PostCategory $category): array
    {
        return [
            ...EntityMapper::map($category),
            "name" => $category->getCateogryName()
        ];
    }
}