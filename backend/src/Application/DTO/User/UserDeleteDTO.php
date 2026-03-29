<?php
declare(strict_types=1);

namespace src\Application\DTO\User;

use src\Domain\Entity\UserRole;

class UserDelteDTO
{
    public function __construct
    (
        readonly public int $userToDeleteId,
        readonly public int $loggedUserId,
        readonly public int $loggedUserRole
    )
    {
        
    }
}