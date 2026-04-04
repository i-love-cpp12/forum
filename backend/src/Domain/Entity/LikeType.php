<?php
declare(strict_types=1);

namespace src\Domain\Entity;

require_once(__DIR__ . "/../../../autoload.php");

enum LikeType: int
{
    case like = 0;
    case dislike = 1;
};