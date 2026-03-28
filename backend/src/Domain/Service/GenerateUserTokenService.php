<?php
declare(strict_types=1);

namespace src\Domain\Service;

require_once(__DIR__ . "/../../../autoload.php");

class GenerateUserTokenService
{
    public static function execute(): string
    {
        return hash("sha256", random_bytes(32));
    }
}