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
use src\Domain\Entity\SortType;
use src\Interface\Mapper\PostMapper;
use Throwable;

class PDOPostRepository implements PostRepositoryInterface
{

    public function __construct(private PDO $conn)
    {
    }

    public function savePost(Post $post): void
    {
        try
        {
            $id = $post->getId();

            if ($id === null)
            {
                $stmt = $this->conn->prepare("
                    INSERT INTO post (parent_post_id, user_id, header, content)
                    VALUES (:parent_post_id, :user_id, :header, :content)
                ");

                $stmt->execute([
                    "parent_post_id" => $post->parentPostId,
                    "user_id" => $post->userId,
                    "header" => $post->getHeader(),
                    "content" => $post->getContent()
                ]);

                $id = (int)$this->conn->lastInsertId();
            }
            else
            {
                $stmt = $this->conn->prepare("
                    UPDATE post
                    SET header = :header,
                        content = :content
                    WHERE post_id = :post_id
                    AND deleted_at IS NULL
                ");

                $stmt->execute([
                    "header" => $post->getHeader(),
                    "content" => $post->getContent(),
                    "post_id" => $id
                ]);
            }

            $newCategories = $post->getCategories();
            $newIds = array_map(fn($c) => $c->getId(), $newCategories);

            foreach ($newCategories as $category)
            {
                $stmt = $this->conn->prepare("
                    SELECT post_post_category_id, deleted_at
                    FROM post_post_category
                    WHERE post_id = :post_id
                    AND post_category_id = :category_id
                    LIMIT 1
                ");

                $stmt->execute([
                    "post_id" => $id,
                    "category_id" => $category->getId()
                ]);

                $existing = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing)
                {
                    if ($existing["deleted_at"] !== null)
                    {
                        $stmt = $this->conn->prepare("
                            UPDATE post_post_category
                            SET deleted_at = NULL
                            WHERE post_id = :post_id
                            AND post_category_id = :category_id
                        ");

                        $stmt->execute([
                            "post_id" => $id,
                            "category_id" => $category->getId()
                        ]);
                    }
                }
                else
                {
                    $stmt = $this->conn->prepare("
                        INSERT INTO post_post_category (post_id, post_category_id)
                        VALUES (:post_id, :category_id)
                    ");

                    $stmt->execute([
                        "post_id" => $id,
                        "category_id" => $category->getId()
                    ]);
                }
            }

            if (!empty($newIds))
            {
                $in = implode(',', array_fill(0, count($newIds), '?'));

                $stmt = $this->conn->prepare("
                    UPDATE post_post_category
                    SET deleted_at = NOW()
                    WHERE post_id = ?
                    AND post_category_id NOT IN ($in)
                    AND deleted_at IS NULL
                ");

                $stmt->execute(array_merge([$id], $newIds));
            }
            else
            {
                $stmt = $this->conn->prepare("
                    UPDATE post_post_category
                    SET deleted_at = NOW()
                    WHERE post_id = ?
                    AND deleted_at IS NULL
                ");

                $stmt->execute([$id]);
            }

        }
        catch (Throwable $e)
        {
            throw $e;
        }
    }

    public function getAllPosts(PostGetAllDTO $DTO): array
    {
        $this->conn->beginTransaction();

        try
        {
            $sql = "
                SELECT DISTINCT p.post_id
                FROM post p
                JOIN _user u ON u.user_id = p.user_id
                LEFT JOIN post_post_category ppc ON p.post_id = ppc.post_id AND ppc.deleted_at IS NULL
                LEFT JOIN post_category pc ON ppc.post_category_id = pc.post_category_id AND pc.deleted_at IS NULL
                WHERE p.deleted_at IS NULL
            ";

            $params = [];

            if($DTO->search)
            {
                $sql .= " AND (p.header LIKE :search OR p.content LIKE :search)";
                $params["search"] = "%{$DTO->search}%";
            }

            if($DTO->author)
            {
                $sql .= " AND (u.username LIKE :author OR u.email LIKE :author)";
                $params["author"] = "%{$DTO->author}%";
            }

            if($DTO->category)
            {
                $sql .= " AND pc.post_category_name LIKE :category";
                $params["category"] = "%{$DTO->category}%";
            }
            $sql .= ";";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);

            $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!$ids) {
                return [];
            }

            $in = implode(',', array_fill(0, count($ids), '?'));

            $sql2 = "
                SELECT
                    p.post_id,
                    p.parent_post_id,
                    p.user_id,
                    p.header,
                    p.content,
                    p.like_count,
                    p.dislike_count,
                    p.comment_count,
                    UNIX_TIMESTAMP(p.created_at) AS created_at,
                    pc.post_category_id,
                    pc.post_category_name,
                    UNIX_TIMESTAMP(pc.created_at) AS category_created_at
                FROM post p
                JOIN _user u ON u.user_id = p.user_id
                LEFT JOIN post_post_category ppc ON p.post_id = ppc.post_id AND ppc.deleted_at IS NULL
                LEFT JOIN post_category pc ON ppc.post_category_id = pc.post_category_id AND pc.deleted_at IS NULL
                WHERE p.post_id IN ($in)
            ";
            $sortMap = [
                "latest" => " ORDER BY p.created_at DESC",
                "eldest" => " ORDER BY p.created_at ASC",
                "mostLiked" => " ORDER BY p.like_count DESC",
                "leastLiked" => " ORDER BY p.like_count ASC",
                "mostDisliked" => " ORDER BY p.dislike_count DESC",
                "leastDisliked" => " ORDER BY p.dislike_count ASC",
                "mostCommented" => " ORDER BY p.comment_count DESC",
                "leastCommented" => " ORDER BY p.comment_count ASC"
            ];

            $sort = SortType::tryFrom($DTO->sort ?? '')?->value ?? 'latest';
            $sql2 .= $sortMap[$sort];

            if($DTO->limit !== null)
            {
                $limit = $DTO->limit;
                $offset = ($DTO->page - 1) * $limit;

                $sql2 .= " LIMIT $limit OFFSET $offset";
            }
            $sql2 .= ";";

            $stmt = $this->conn->prepare($sql2);
            $stmt->execute($ids);

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $result = [];

            foreach ($data as $row)
            {
                $id = $row["post_id"];

                if (!isset($result[$id]))
                {
                    $result[$id] = new Post(
                        $row["post_id"],
                        $row["parent_post_id"],
                        $row["user_id"],
                        $row["header"],
                        $row["content"],
                        [],
                        $row["like_count"],
                        $row["dislike_count"],
                        $row["comment_count"],
                        $row["created_at"]
                    );
                }

                if ($row["post_category_id"] !== null)
                {
                    $result[$id]->addCategory(
                        new PostCategory(
                            $row["post_category_id"],
                            $row["post_category_name"],
                            $row["category_created_at"]
                        )
                    );
                }
            }

            $this->conn->commit();

            return $result;
        }
        catch (Throwable $e)
        {
            $this->conn->rollBack();
            throw $e;
        }
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
        $post = new Post($row["post_id"], $row["parent_post_id"], $row["user_id"], $row["header"], $row["content"], [], $row["like_count"], $row["dislike_count"], $row["comment_count"], $row["post_created_at"]);

        foreach($data as $row)
        {
            if($row["post_category_id"] !== null)
                $post->addCategory(new PostCategory($row["post_category_id"], $row["post_category_name"], $row["post_category_created_at"]));
        }

        return $post;
    }
    public function deletePost(int $id): void
    {
        $this->conn->beginTransaction();

        try
        {
            $stmt = $this->conn->prepare("
                WITH RECURSIVE post_tree AS (
                    SELECT post_id
                    FROM post
                    WHERE post_id = :id AND deleted_at IS NULL

                    UNION ALL

                    SELECT p.post_id
                    FROM post p
                    INNER JOIN post_tree pt ON p.parent_post_id = pt.post_id
                    WHERE p.deleted_at IS NULL
                )
                UPDATE post
                SET deleted_at = NOW()
                WHERE post_id IN (SELECT post_id FROM post_tree)
            ");
            $stmt->execute(["id" => $id]);

            $stmt = $this->conn->prepare("
                UPDATE _like
                SET deleted_at = NOW()
                WHERE post_id IN (
                    SELECT post_id FROM post
                    WHERE post_id = :id OR parent_post_id IS NOT NULL
                )
                AND deleted_at IS NULL
            ");
            $stmt->execute(["id" => $id]);

            $stmt = $this->conn->prepare("
                UPDATE post_post_category
                SET deleted_at = NOW()
                WHERE post_id IN (
                    SELECT post_id FROM post
                    WHERE post_id = :id OR parent_post_id IS NOT NULL
                )
                AND deleted_at IS NULL
            ");
            $stmt->execute(["id" => $id]);

            $this->conn->commit();
        }
        catch (Throwable $e)
        {
            $this->conn->rollBack();
            throw $e;
        }
    }
    /** @return Post[]*/
    public function getCommentsForPost(PostGetCommentsDTO $DTO): array
    {
        $limit = $DTO->limit;
        $offset = ($DTO->page - 1) * $DTO->limit;

        $sql = "SELECT p.post_id as post_id, parent_post_id, user_id, header, content, like_count, dislike_count, comment_count, pc.post_category_id AS post_category_id, pc.post_category_name AS post_category_name, UNIX_TIMESTAMP(p.created_at) AS post_created_at, UNIX_TIMESTAMP(pc.created_at) AS post_category_created_at FROM post AS p LEFT JOIN post_post_category AS ppc ON p.post_id = ppc.post_id AND ppc.deleted_at IS NULL LEFT JOIN post_category AS pc ON ppc.post_category_id = pc.post_category_id AND pc.deleted_at IS NULL WHERE parent_post_id = :parent_post_id AND p.deleted_at IS NULL";

        if($limit !== null)
            $sql .= " LIMIT $limit OFFSET $offset";
        $sql .= ";";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["parent_post_id" => $DTO->postId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /** @var Post[] $result */
        $result = [];

        foreach($data as $row)
        {
            if(!isset($result[$row["post_id"]]))
            {
                $result[$row["post_id"]] = new Post($row["post_id"], $row["parent_post_id"], $row["user_id"], $row["header"], $row["content"], [], $row["like_count"], $row["dislike_count"], $row["comment_count"], $row["post_created_at"]);      
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