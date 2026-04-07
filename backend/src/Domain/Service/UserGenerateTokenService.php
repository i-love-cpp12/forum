<?php
declare(strict_types=1);

namespace src\Domain\Service;

class UserGenerateTokenService
{
    public static function execute(): string
    {
        return hash("sha256", random_bytes(32));
    }
}