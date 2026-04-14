<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\PDO;

use PDO;
use src\Domain\Repository\LikeRepositoryInterface;
use src\Domain\Entity\Like;
use src\Domain\Entity\LikeType;

class PDOLikeRepository implements LikeRepositoryInterface
{
    public function __construct(private PDO $conn)
    {
    }
    public function saveLike(Like $like): void
    {
        //insert
        if($like->getId() === null)
        {
            $sql = "INSERT INTO _like (post_id, user_id, like_type_id) VALUES (:post_id, :user_id, :like_type_id);";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["post_id" => $like->postId, "user_id" => $like->userId, "like_type_id" => $like->type->value + 1]);
        }
        //update
        else
        {
            $sql = "UPDATE _like SET like_type_id = :like_type_id WHERE post_id = :post_id AND user_id = :user_id AND deleted_at IS NULL;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["like_type_id" => $like->type->value + 1, "post_id" => $like->postId, "user_id" => $like->userId]);
        }
    }
    public function deleteLike(int $userId, int $postId): void
    {
        $sql = "UPDATE _like SET deleted_at = NOW() WHERE user_id = :user_id AND post_id = :post_id AND deleted_at IS NULL;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["user_id" => $userId, "post_id" => $postId]);
    }
    public function getLike(int $userId, int $postId): ?Like
    {
        $sql = "SELECT like_id, post_id, user_id, like_type_id, UNIX_TIMESTAMP(created_at) as created_at FROM _like WHERE post_id = :post_id AND user_id = :user_id AND deleted_at is null;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["post_id" => $postId, "user_id" => $userId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$data) return null;
        
        return new Like($data["like_id"], $data["post_id"], $data["user_id"], LikeType::from($data["like_type_id"] - 1), $data["created_at"]);
    }
}