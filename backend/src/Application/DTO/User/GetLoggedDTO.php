<?php
declare(strict_types=1);

namespace src\Application\DTO\User;

class GetLoggedDTO
{
    public function __construct
    (
        readonly public string $token
    )
    {
        
    }
}