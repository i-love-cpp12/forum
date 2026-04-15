<?php
declare(strict_types=1);

namespace src\Domain\Entity;

use InvalidArgumentException;
use src\Shared\Validation\Validator;

class Token extends Entity
{
    readonly public int $userId;

    readonly public string $value;
    public static int $length = 64;

    readonly public ?int $expireTimeStamp;

    public static int $tokenDurationS = 60 * 60 * 5;

    private bool $isActive;

    public function __construct
    (
        ?int $id,
        int $userId,
        string $value,
        ?int $expireTimeStamp = null,
        ?int $createdTimeStamp = null,
        bool $isActive = true
    )
    {
        parent::__construct($id, $createdTimeStamp);

        if($userId < 0)
            throw new InvalidArgumentException("user: $userId id must not be negative");

        if(
            !Validator::validateLenght($value, self::$length, self::$length) ||
            !Validator::validateSha256($value)
        )
            throw new InvalidArgumentException("token: $value must be (" . self::$length . ") long");
        
        $this->userId = $userId; 
        $this->value = $value; 
        $this->expireTimeStamp = $expireTimeStamp;
        $this->isActive = $isActive;
    }

    public function __toString(): string
    {
        return
            "id: " . $this->getId() .
            " | userId: " . $this->userId .
            " | value: " . $this->value .
            " | expDate: " . ($this->expireTimeStamp !== null ? date("d M Y H:i:s", $this->expireTimeStamp) : "null") .
            " | is active: " . ($this->isActive() ? "true":"false");
    }

    public function isActive(): bool
    {
        return $this->isActive && ($this->expireTimeStamp === null || $this->expireTimeStamp >= time());
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }
}