<?php
declare(strict_types=1);

namespace src\Interface\Mapper;

use src\Domain\Entity\User;
use src\Interface\Mapper\EntityMapper;

class UserMapper
{
    public static function map(User $user): array
    {
        return [
            ...EntityMapper::map($user),
            "username" => $user->getUsername(),
            "email" => $user->email,
            "role" => User::roleToString($user->role)
        ];
    }
}