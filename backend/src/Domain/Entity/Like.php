<?php
declare(strict_types=1);

namespace src\Domain\Entity;

require_once(__DIR__ . "/../../../autoload.php");

use InvalidArgumentException;
use src\Domain\Entity\Entity;

enum LikeType: int
{
    case like = 0;
    case dislike = 1;
};

class Like extends Entity
{
    public readonly int $postId;
    public readonly LikeType $type;

    public function __construct
    (
        ?int $id,
        int $postId,
        LikeType $type,
    )
    {
        parent::__construct($id);

        if($postId < 0)
            throw new InvalidArgumentException("postId: $postId can not be negative");

        $this->postId = $postId;
        $this->type = $type;
    }

    public static function likeTypeToString(LikeType $type): string
    {
        $likeTypes = ["like", "dislike"];
        return $likeTypes[$type->value];
    }

    public function __toString(): string
    {
        return
            "id: " . $this->getId() .
            " | postId: " . $this->postId .
            " | type: " . self::likeTypeToString($this->type);
    }
}