<?php
declare(strict_types=1);

namespace src\Domain\Entity;

enum PostType: int
{
    case post = 0;
    case comment = 1;
};