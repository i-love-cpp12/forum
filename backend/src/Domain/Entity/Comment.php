<?php
declare(strict_types=1);

namespace src\Domain\Entity;

use src\Domain\Entity\Post;

class Comment extends Post
{

    /** @param PostCategory[] $categories */
    public function __construct
    (
        ?int $id,
        int $parentPostId,
        int $userId,
        string $content,
        int $likeCount = 0,
        int $dislikeCount = 0,
        int $commentCount = 0,
        ?int $createdAtTimeStamp = null
    )
    {
        parent::__construct($id, $parentPostId, $userId, null, $content, [], $likeCount, $dislikeCount, $commentCount, $createdAtTimeStamp);
    }
}