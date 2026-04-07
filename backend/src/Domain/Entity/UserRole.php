<?php
declare(strict_types=1);

namespace src\Domain\Entity;

enum UserRole: int
{
    case normal = 0;
    case admin = 1;
};