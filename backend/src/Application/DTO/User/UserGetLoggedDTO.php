<?php
declare(strict_types=1);

namespace src\Application\DTO\User;

class UserGetLoggedDTO
{
    public function __construct
    (
        readonly public string $token
    )
    {
        
    }
}