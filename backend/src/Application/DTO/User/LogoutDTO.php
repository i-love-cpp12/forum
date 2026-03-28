<?php
declare(strict_types=1);

namespace src\Application\DTO\User;

class LogoutDTO
{
    public function __construct
    (
        readonly public string $email
    )
    {
        
    }
}