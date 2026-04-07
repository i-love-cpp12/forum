<?php
declare(strict_types=1);

namespace src\Domain\Entity;

use InvalidArgumentException;
use src\Domain\Entity\Entity;
use src\Shared\Validation\Validator;

class PostCategory extends Entity
{
    public readonly string $categoryName;
    public static $categoryNameMinLenght = 1;
    public static $categoryNameMaxLenght = 100;

    public function __construct
    (
        ?int $id,
        string $categoryName
    )
    {
        parent::__construct($id);
        $this->categoryName = "";
        $this->setCategoryName($categoryName);
        
    }

    public function setCategoryName(string $categoryName): void
    {
        if(!self::validateCategoryName($categoryName))
            throw new InvalidArgumentException("categoryName: $categoryName must " . self::getCategoryNameValidateMessage());
        
        $this->categoryName = $categoryName;
    }
    public static function validateCategoryName(string $categoryName): bool
    {
        return Validator::validateLenght($categoryName, self::$categoryNameMinLenght, self::$categoryNameMaxLenght);
    }
    public static function getCategoryNameValidateMessage(): string
    {
        return "be (" . self::$categoryNameMinLenght . " - " . self::$categoryNameMaxLenght . ") long";
    }
    public function __toString(): string
    {
        return
            "id: " . $this->getId() .
            " | category: " . $this->categoryName;
    }
}