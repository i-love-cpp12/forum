<?php
declare(strict_types=1);

namespace src\Domain\Entity;

require_once(__DIR__ . "/../../../autoload.php");

use InvalidArgumentException;
use src\Shared\Validation\Validator;

class Token extends Entity
{
    readonly public string $value;
    public static int $length = 256;
    readonly public ?int $expireTimeStamp;

    public function __construct(?int $id, string $value, ?int $duration = null)
    {
        parent::__construct($id);
        if(!Validator::validateLenght($value, self::$length, self::$length))
            throw new InvalidArgumentException("token: $value must be (" . self::$length . ") long");
        $this->value = $value;

        if($duration !== null && $duration < 0)
            throw new InvalidArgumentException("duration: $duration can not be negative");
        
        $this->expireTimeStamp = $duration !== null ? time() + $duration : null;
    }
}