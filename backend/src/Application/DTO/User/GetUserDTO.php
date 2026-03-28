<?php
declare(strict_types=1);

namespace src\Application\DTO\User;

class GetUserDTO
{
    public function __construct
    (
        readonly public int $id
    )
    {
        
    }
}