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
    public static function validatePostType(PostType $suposedPostType, Post $post): void
    {
        $postId = $post->getId();
        $postType = $post->getPostType();
        if($suposedPostType !== $postType && $suposedPostType === PostType::comment)
            throw new BusinessException( 
                "Comment with id: $postId is a post"
            );
    }
}