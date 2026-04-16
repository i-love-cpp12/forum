<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\PDO;

use PDO;
use src\Application\DTO\Like\LikeDTO;
use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Entity\Post;
use src\Application\DTO\Post\PostGetAllDTO;
use src\Application\DTO\Post\PostGetCommentsDTO;
use src\Domain\Entity\Comment;
use src\Domain\Entity\LikeType;
use src\Shared\Array\ArrayHelper;
use src\Domain\Entity\Like;
use src\Domain\Entity\PostCategory;
use src\Interface\Mapper\PostMapper;

class PDOPostRepository implements PostRepositoryInterface
{

    public function __construct(private PDO $conn)
    {
    }
    public function savePost(Post $post): void
    {
        //insert
        if(($id = $post->getId()) === null)
        {
            $sql = "INSERT INTO post (parent_post_id, user_id, header, content) VALUES (:parent_post_id, :user_id, :header, :content);";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["parent_post_id" => $post->parentPostId, "user_id" => $post->userId, "header" => $post->getHeader(), "content" => $post->getContent()]);
            //cateogies
        }
        //update
        else
        {
            $sql = "UPDATE post SET header = :header, content = :content WHERE post_id = :post_id AND deleted_at IS NULL;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["header" => $post->getHeader(), "content" => $post->getContent(), "post_id" => $id]);
            //categories
        }
    }
    /** @return Post[]*/
    public function getAllPosts(PostGetAllDTO $DTO): array
    {
        $data = $this->conn->query("SELECT p.post_id as post_id, parent_post_id, user_id, header, content, like_count, dislike_count, comment_count, pc.post_category_id AS post_category_id, pc.post_category_name AS post_category_name, UNIX_TIMESTAMP(p.created_at) AS post_created_at, UNIX_TIMESTAMP(pc.created_at) AS post_category_created_at FROM post AS p LEFT JOIN post_post_category AS ppc ON p.post_id = ppc.post_id AND ppc.deleted_at IS NULL LEFT JOIN post_category AS pc ON ppc.post_category_id = pc.post_category_id AND pc.deleted_at IS NULL WHERE p.deleted_at IS NULL;")->fetchAll(PDO::FETCH_ASSOC);

        /** @var Post[] $result */
        $result = [];

        foreach($data as $row)
        {
            if(!isset($result[$row["post_id"]]))
            {
                $result[$row["post_id"]] = new Post($row["post_id"], $row["parent_post_id"], $row["user_id"], $row["header"], $row["content"], [], $row["like_count"], $row["like_count"], $row["comment_count"], $row["post_created_at"]);      
            }

            if($row["post_category_id"] !== null)
                $result[$row["post_id"]]->addCategory(new PostCategory($row["post_category_id"], $row["post_category_name"], $row["post_category_created_at"]));
            
        }

        return $result;
    }
    public function getPostById(int $id): ?Post
    {
        $stmt = $this->conn->prepare("SELECT p.post_id as post_id, parent_post_id, user_id, header, content, like_count, dislike_count, comment_count, pc.post_category_id AS post_category_id, pc.post_category_name AS post_category_name, UNIX_TIMESTAMP(p.created_at) AS post_created_at, UNIX_TIMESTAMP(pc.created_at) AS post_category_created_at FROM post AS p LEFT JOIN post_post_category AS ppc ON p.post_id = ppc.post_id AND ppc.deleted_at IS NULL LEFT JOIN post_category AS pc ON ppc.post_category_id = pc.post_category_id AND pc.deleted_at IS NULL WHERE p.post_id = :post_id AND p.deleted_at IS NULL;");

        $stmt->execute(["post_id" => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($data) === 0)
            return null;

        $row = $data[0];
        /** @var Post $result */
        $post = new Post($row["post_id"], $row["parent_post_id"], $row["user_id"], $row["header"], $row["content"], [], $row["like_count"], $row["like_count"], $row["comment_count"], $row["post_created_at"]);

        foreach($data as $row)
        {
            if($row["post_category_id"] !== null)
                $post->addCategory(new PostCategory($row["post_category_id"], $row["post_category_name"], $row["post_category_created_at"]));
        }

        return $post;
    }
    public function deletePost(int $id): void
    {
    }
    /** @return Post[]*/
    public function getCommentsForPost(PostGetCommentsDTO $DTO): array
    {
        $limit = $DTO->limit;
        $offset = ($DTO->page - 1) * $DTO->limit;

        $sql = "SELECT p.post_id as post_id, parent_post_id, user_id, header, content, like_count, dislike_count, comment_count, pc.post_category_id AS post_category_id, pc.post_category_name AS post_category_name, UNIX_TIMESTAMP(p.created_at) AS post_created_at, UNIX_TIMESTAMP(pc.created_at) AS post_category_created_at FROM post AS p LEFT JOIN post_post_category AS ppc ON p.post_id = ppc.post_id AND ppc.deleted_at IS NULL LEFT JOIN post_category AS pc ON ppc.post_category_id = pc.post_category_id AND pc.deleted_at IS NULL WHERE parent_post_id = :parent_post_id AND p.deleted_at IS NULL";

        if($limit !== null)
            $sql .= " LIMIT $limit OFFSET $offset;";
        else $sql .= ";";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["parent_post_id" => $DTO->postId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /** @var Post[] $result */
        $result = [];

        foreach($data as $row)
        {
            if(!isset($result[$row["post_id"]]))
            {
                $result[$row["post_id"]] = new Post($row["post_id"], $row["parent_post_id"], $row["user_id"], $row["header"], $row["content"], [], $row["like_count"], $row["like_count"], $row["comment_count"], $row["post_created_at"]);      
            }

            if($row["post_category_id"] !== null)
                $result[$row["post_id"]]->addCategory(new PostCategory($row["post_category_id"], $row["post_category_name"], $row["post_category_created_at"]));
            
        }

        return $result;
    }
    public function likePost(int $postId, LikeType $likeType): void
    {
        $likeColumnName = Like::likeTypeToString($likeType) . "_count";
        $sql = "UPDATE post SET $likeColumnName = $likeColumnName + 1 WHERE post_id = :post_id AND deleted_at IS NULL;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["post_id" => $postId]);
    }
    public function deleteLike(int $postId, LikeType $likeType): void
    {
        $likeColumnName = Like::likeTypeToString($likeType) . "_count";
        $sql = "UPDATE post SET $likeColumnName = $likeColumnName - 1 WHERE post_id = :post_id AND deleted_at IS NULL;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["post_id" => $postId]);
    }
    public function addComment(int $postId): void
    {
        $sql = "UPDATE post SET comment_count = comment_count + 1 WHERE post_id = :post_id AND deleted_at IS NULL;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["post_id" => $postId]);
    }
    public function deleteComment(int $postId): void
    {
        $sql = "UPDATE post SET comment_count = comment_count - 1 WHERE post_id = :post_id AND deleted_at IS NULL;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["post_id" => $postId]);
    }
}