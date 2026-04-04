<?php
declare(strict_types=1);

namespace src\Domain\Entity;

require_once(__DIR__ . "/../../../autoload.php");

enum UserRole: int
{
    case normal = 0;
    case admin = 1;
};