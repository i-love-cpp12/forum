<?php
declare(strict_types=1);

namespace src\Interface\Mapper;

use src\Domain\Entity\User;

class UserMapper
{
    public static function map(User $user): array
    {
        return [
            "id" => $user->getId(),
            "username" => $user->getUsername(),
            "email" => $user->email,
            "role" => User::roleToString($user->role)
        ];
    }
}