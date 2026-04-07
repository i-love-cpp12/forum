<?php
declare(strict_types=1);

namespace src\Application\Service\Like;

use PDO;
use src\Domain\Repository\LikeRepositoryInterface;
use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Exception\BusinessException\EntityNotFoundException;
use Throwable;

require_once(__DIR__ . "/../../../../autoload.php");

class LikeDeleteService
{
    public function __construct
    (

        private PDO $conn,
        private LikeRepositoryInterface $likeRepo,
        private PostRepositoryInterface $postRepo
    )
    {}

    public function execute(int $userId, int $postId): void
    {
        $like = $this->likeRepo->getLike($userId, $postId);
        
        if($like === null)
            throw new EntityNotFoundException("Like", "($userId, $postId)", "(UserId, PostId)");

        $this->conn->beginTransaction();
        try
        {
            $this->likeRepo->deleteLike($userId, $postId);
            $this->postRepo->deleteLike($like->type);
            $this->conn->commit();
        }
        catch(Throwable $e)
        {
            $this->conn->rollBack();
            throw $e;
        }
    }
}