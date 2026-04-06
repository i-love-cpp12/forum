<?php
declare(strict_types=1);

namespace src\Application\DTO\Post;

class PostDeleteDTO
{
    public function __construct
    (
        readonly public int $postToDeleteId,
        readonly public int $loggedUserId,
        readonly public int $loggedUserRole
    )
    {
        
    }
}