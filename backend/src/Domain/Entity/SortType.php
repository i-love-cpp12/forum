<?php
declare(strict_types=1);

namespace src\Domain\Entity;

enum SortType: string
{
    case latest = "latest";
    case eldest = "eldest";
    case mostLiked = "mostLiked";
    case leastLiked = "leastLiked";
    case mostDisliked = "mostDisliked";
    case leastDisliked = "leastDisliked";
    case mostCommented = "mostCommented";
    case leastCommented = "leastCommented";

}