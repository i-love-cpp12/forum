<?php
declare(strict_types=1);

namespace src\Application\DTO\User;

class UserUpdateDTO
{
    public function __construct
    (
        readonly public int $userToUpdateId,
        readonly public int $loggedUserId,
        readonly public int $loggedUserRole,
        readonly public ?string $newUsername,
        readonly public ?string $newPassword
    )
    {
        
    }
}