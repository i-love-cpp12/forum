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
use src\Infrastructure\Http\Respond;
use src\Interface\Mapper\LikeMapper;
use src\Shared\Exception\BusinessException\RequestDataFormatException;
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
    
    public function likeStatus(string $postId): void
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

            $like = $this->likeStatusService->execute($userId, $postId);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Geting like status for postId: $postId and userId: $userId successfull",
                        "like" => ($like !== null ? LikeMapper::map($like) : null)
                    ]
            ]
        );

    }
    public function like(string $postId): void
    {
        $this->addLikeHelper($postId, LikeType::like);
    }
    public function removeLike(string $postId): void
    {
        $this->removeLikeHelper($postId, LikeType::like);
    }
    public function dislike(string $postId): void
    {
        $this->addLikeHelper($postId, LikeType::dislike);
    }
    public function removeDislike(string $postId): void
    {
        $this->removeLikeHelper($postId, LikeType::dislike);
    }

    private function addLikeHelper(string $postId, LikeType $likeType)
    {
        /** @var User $loggedUser */
        $loggedUser = $this->request->getFromState("user");
        $userId = $loggedUser->getId();

        try
        {
            if(!ctype_digit($postId))
                throw new RequestDataFormatException("postId", "int", true);

            $postId = intval($postId);

            $likeDTO = new LikeDTO($postId, $userId, $likeType->value);
            $this->likeAddService->execute($likeDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        $action = Like::likeTypeToString($likeType) . "d";
        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Post with postId: $postId $action by userId: $userId successfully"
                    ]
            ]
        );
    }

    private function removeLikeHelper(string $postId, LikeType $likeType)
    {
        /** @var User $loggedUser */
        $loggedUser = $this->request->getFromState("user");
        $userId = $loggedUser->getId();

        try
        {
            if(!ctype_digit($postId))
                throw new RequestDataFormatException("postId", "int", true);

            $postId = intval($postId);

            $this->likeDeleteService->execute($userId, $postId);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        $likeTypeStr = Like::likeTypeToString($likeType);
        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Removed $likeTypeStr from post with postId: $postId by userId: $userId successfully"
                    ]
            ]
        );
    }
}