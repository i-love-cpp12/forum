<?php
declare(strict_types=1);

namespace src\Domain\Entity;

use InvalidArgumentException;
use src\Domain\Entity\Entity;
use src\Domain\Entity\LikeType;

class Like extends Entity
{
    public readonly int $postId;
    public readonly int $userId;
    public readonly LikeType $type;

    public function __construct
    (
        ?int $id,
        int $postId,
        int $userId,
        LikeType $type,
        ?int $createdAtTimeStamp = null
    )
    {
        parent::__construct($id, $createdAtTimeStamp);

        if($postId < 0)
            throw new InvalidArgumentException("postId: $postId can not be negative");
        if($userId < 0)
            throw new InvalidArgumentException("userId: $userId can not be negative");

        $this->postId = $postId;
        $this->userId = $userId;
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