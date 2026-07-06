<?php
declare(strict_types=1);

namespace src\Domain\Entity;

enum UserRole: int
{
    case admin = 0;
    case normal = 1;
};