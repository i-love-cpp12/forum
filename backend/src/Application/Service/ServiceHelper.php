<?php
declare(strict_types=1);

namespace src\Application\Service;

use InvalidArgumentException;
use src\Domain\Entity\Like;
use src\Domain\Entity\LikeType;
use src\Domain\Entity\Post;
use src\Domain\Entity\PostType;
use src\Domain\Entity\User;
use src\Domain\Entity\UserRole;
use src\Shared\Exception\BusinessException\AuthException;
use src\Shared\Exception\BusinessException\BusinessException;

class ServiceHelper
{
    public static function authorizeAction
    (
        UserRole $userRole,
        ?int $userId,
        ?int $userDataAuthorId,
        UserRole $lowestAbsoluteRole = UserRole::admin
    )
    {
        if($userId === null && $userDataAuthorId !== null || $userId !== null && $userDataAuthorId === null)
            throw new InvalidArgumentException("userId and userDataAuthorId must be both set or unset");

        if
        (
            $userRole->value >= $lowestAbsoluteRole->value ||
            ($userId !== null && $userId === $userDataAuthorId)
        )
            return;

        throw new AuthException(( $userId === null ? User::roleToString($userRole) : ""));
    }
    public static function validatePostType(PostType $postType, Post $post): void
    {
        $postId = $post->getId();
        $postType2 = $post->getPostType();
        if($postType !== $postType2)
            throw new BusinessException(
                $postType === PostType::post ?
                "Post with id: $postId is a comment" : 
                "Comment with id: $postId is a post"
            );
    }
}