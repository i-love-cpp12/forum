<?php
declare(strict_types=1);

namespace src\Application\DTO\User;

class UpdateDTO
{
    public function __construct
    (
        readonly public int $id,
        readonly public string $newUsername,
        readonly public string $newPassword
    )
    {
        
    }
}