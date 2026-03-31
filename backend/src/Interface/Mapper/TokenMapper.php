<?php
declare(strict_types=1);

namespace src\Interface\Mapper;

use src\Domain\Entity\Token;

class TokenMapper
{
    public static function map(Token $token): array
    {
        return [
            ...EntityMapper::map($token),
            "userId" => $token->userId,
            "value" => $token->value,
            "expireTimeStamp" => $token->expireTimeStamp,
            "isActive" => $token->isActive(),
        ];
    }
}