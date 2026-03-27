<?php
declare(strict_types=1);

namespace src\Domain\Entity;

require_once(__DIR__ . "/../../../autoload.php");

use InvalidArgumentException;
use src\Shared\Validation\Validator;

class Token extends Entity
{
    readonly public int $userId;

    readonly public string $value;
    public static int $length = 64;

    readonly public ?int $expireTimeStamp;

    public function __construct(?int $id, int $userId, string $value, ?int $duration = null)
    {
        parent::__construct($id);

        if($userId < 0)
            throw new InvalidArgumentException("user: $userId id must not be negative");

        if(
            !Validator::validateLenght($value, self::$length, self::$length) ||
            !Validator::validateSha256($value)
        )
            throw new InvalidArgumentException("token: $value must be (" . self::$length . ") long");

        if($duration !== null && $duration < 0)
            throw new InvalidArgumentException("duration: $duration can not be negative");
        
        $this->userId = $userId; 
        $this->value = $value; 
        $this->expireTimeStamp = $duration !== null ? time() + $duration : null;
    }
}