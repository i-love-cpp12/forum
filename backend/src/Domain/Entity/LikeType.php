<?php
declare(strict_types=1);

namespace src\Domain\Entity;

enum LikeType: int
{
    case like = 0;
    case dislike = 1;
};