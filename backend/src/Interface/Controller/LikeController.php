<?php
declare(strict_types=1);
namespace src\Interface\Controller;

use src\Application\DTO\Like\LikeDTO;
use src\Application\Service\Like\LikeAddService;
use src\Application\Service\Like\LikeDeleteService;
use src\Application\Service\Like\LikeStatusService;
use src\Infrastructure\Http\Request;
use src\Shared\Exception\ExceptionHandler;
use src\Domain\Entity\User;
use src\Domain\Entity\Like;
use src\Domain\Entity\LikeType;
use src\Domain\Entity\Post;
use src\Domain\Entity\PostType;
use src\Infrastructure\Http\Respond;
use src\Interface\Mapper\LikeMapper;
use src\Shared\Exception\BusinessException\RequestDataFormatException;
use src\Shared\String\StringHelper;
use Throwable;

class LikeController
{
    public function __construct
    (
        private Request $request,
        private LikeStatusService $likeStatusService,
        private LikeAddService $likeAddService,
        private LikeDeleteService $likeDeleteService
    ){}
    
    public function likeStatusPost(string $postId): void
    {
        $this->likeStatusHelper($postId, PostType::post);
    }
    public function likeStatusComment(string $postId): void
    {
        $this->likeStatusHelper($postId, PostType::comment);
    }
    public function likePost(string $postId): void
    {
        $this->addLikeHelper($postId, LikeType::like, PostType::post);
    }
    public function likeComment(string $postId): void
    {
        $this->addLikeHelper($postId, LikeType::like, PostType::comment);
    }

    public function removePostLike(string $postId): void
    {
        $this->removeLikeHelper($postId, LikeType::like, PostType::post);
    }
    public function removeCommentLike(string $postId): void
    {
        $this->removeLikeHelper($postId, LikeType::like, PostType::comment);
    }

    public function dislikePost(string $postId): void
    {
        $this->addLikeHelper($postId, LikeType::dislike, PostType::post);
    }
    public function dislikeComment(string $postId): void
    {
        $this->addLikeHelper($postId, LikeType::dislike, PostType::comment);
    }

    public function removePostDislike(string $postId): void
    {
        $this->removeLikeHelper($postId, LikeType::dislike, PostType::post);
    }
    public function removeCommentDislike(string $postId): void
    {
        $this->removeLikeHelper($postId, LikeType::dislike, PostType::comment);
    }
    private function likeStatusHelper(string $postId, PostType $postType): void
    {
        /** @var User $loggedUser */
        $loggedUser = $this->request->getFromState("user");
        $userId = $loggedUser->getId();

        /** @var ?Like $like */
        $like = null;
        try
        {
            if(!ctype_digit($postId))
                throw new RequestDataFormatException("postId", "int", true);

            $postId = intval($postId);

            $like = $this->likeStatusService->execute($userId, $postId, $postType->value);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        $postTypeStr = Post::postTypeToString($postType);
        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Geting like status for $postTypeStr with id: $postId and userId: $userId successfull",
                        "like" => ($like !== null ? LikeMapper::map($like) : null)
                    ]
            ]
        );
    }
    private function addLikeHelper(string $postId, LikeType $likeType, PostType $postType)
    {
        /** @var User $loggedUser */
        $loggedUser = $this->request->getFromState("user");
        $userId = $loggedUser->getId();

        try
        {
            if(!ctype_digit($postId))
                throw new RequestDataFormatException("postId", "int", true);

            $postId = intval($postId);

            $likeDTO = new LikeDTO($postId, $userId, $likeType->value, $postType->value);
            $this->likeAddService->execute($likeDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        $postTypeStr = StringHelper::capitalize(Post::postTypeToString($postType));
        $action = Like::likeTypeToString($likeType) . "d";
        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "$postTypeStr with id: $postId $action by userId: $userId successfully"
                    ]
            ]
        );
    }

    private function removeLikeHelper(string $postId, LikeType $likeType, PostType $postType)
    {
        /** @var User $loggedUser */
        $loggedUser = $this->request->getFromState("user");
        $userId = $loggedUser->getId();

        try
        {
            if(!ctype_digit($postId))
                throw new RequestDataFormatException("postId", "int", true);

            $postId = intval($postId);

            $likeDTO = new LikeDTO($postId, $userId, $likeType->value, $postType->value);
            $this->likeDeleteService->execute($likeDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        $postTypeStr = Post::postTypeToString($postType);
        $likeTypeStr = Like::likeTypeToString($likeType);
        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Removed $likeTypeStr from $postTypeStr with postId: $postId by userId: $userId successfully"
                    ]
            ]
        );
    }
}