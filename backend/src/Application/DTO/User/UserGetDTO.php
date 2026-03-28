<?php
declare(strict_types=1);

namespace src\Application\DTO\User;

class UserGetDTO
{
    public function __construct
    (
        readonly public int $id
    )
    {
        
    }
}