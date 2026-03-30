<?php
declare(strict_types=1);

namespace src\Domain\Entity;

require_once(__DIR__ . "/../../../autoload.php");

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

        if(!Validator::validateLenght($categoryName, self::$categoryNameMinLenght, self::$categoryNameMaxLenght))
            throw new InvalidArgumentException("categoryName: $categoryName must be (" . self::$categoryNameMinLenght . " - " . self::$categoryNameMaxLenght . ") long");

        $this->categoryName = $categoryName;
    }
    public function __toString(): string
    {
        return
            "id: " . $this->getId() .
            " | category: " . $this->categoryName;
    }
}