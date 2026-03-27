<?php
declare(strict_types=1);

namespace src\Application\DTO\User;

class RegisterDTO
{
    public function __construct
    (
        readonly public string $username,
        readonly public string $email,
        readonly public string $password
    )
    {
        
    }
}