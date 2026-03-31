<?php
declare(strict_types=1);

namespace src\Application\Service;

require_once(__DIR__ . "/../../../autoload.php");

use src\Domain\Entity\UserRole;

class ServiceHelper
{
    public static function authUserAction(int $userId, int $userRole, int $userDataAuthorId)
    {
        return
            $userId !== $userDataAuthorId &&
            $userRole !== UserRole::admin->value;
    }
}