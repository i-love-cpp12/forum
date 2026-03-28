<?php
declare(strict_types=1);

namespace src\Application\DTO\User;

class UserLoginDTO
{
    public function __construct
    (
        readonly public string $email,
        readonly public string $password,
        readonly public string $token
    )
    {
        
    }
}