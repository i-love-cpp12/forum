<?php
declare(strict_types=1);

namespace src\Application\Service;

require_once(__DIR__ . "/../../../autoload.php");

use InvalidArgumentException;
use src\Domain\Entity\User;
use src\Domain\Entity\UserRole;
use src\Shared\Exception\BussinessException\AuthException;

class ServiceHelper
{
    public static function authorizeAction
    (
        UserRole $userRole,
        ?int $userId,
        ?int $userDataAuthorId,
        UserRole $lowestAbsoluteRole = UserRole::admin
    )
    {
        if($userId === null && $userDataAuthorId !== null || $userId !== null && $userDataAuthorId === null)
            throw new InvalidArgumentException("userId and userDataAuthorId must be both set or unset");

        if
        (
            $userRole->value >= $lowestAbsoluteRole->value ||
            ($userId !== null && $userId === $userDataAuthorId)
        )
            return;

        throw new AuthException(( $userId === null ? User::roleToString($userRole) : ""));
    }
}