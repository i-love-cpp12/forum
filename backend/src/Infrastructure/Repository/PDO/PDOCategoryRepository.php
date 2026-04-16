<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\PDO;

use PDO;
use src\Domain\Repository\CategoryRepositoryInterface;
use src\Domain\Entity\PostCategory;
use Throwable;

class PDOCategoryRepository implements CategoryRepositoryInterface
{

    public function __construct(private PDO $conn)
    {
    }

    public function saveCategory(PostCategory $category): void
    {
        //insert
        if(($id = $category->getId()) === null)
        {
            $sql = "INSERT INTO post_category (post_category_name) VALUES (:category_name);";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["category_name" => $category->getCategoryName()]);
        }
        //update
        else
        {
            $sql = "UPDATE post_category SET post_category_name = :category_name WHERE post_category_id = :id AND deleted_at IS NULL;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["category_name" => $category->getCategoryName(), "id" => $id]);
        }
    }
    public function getCategoryById(int $categoryId): ?PostCategory
    {
        $sql = "SELECT post_category_id, post_category_name, UNIX_TIMESTAMP(created_at) AS created_at FROM post_category WHERE post_category_id = :id AND deleted_at IS NULL;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $categoryId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$data) return null;
        
        return new PostCategory($data["post_category_id"], $data["post_category_name"], $data["created_at"]);
    }
    /** @return PostCategory[] */
    public function getAllCategories(): array
    {
        $sql = "SELECT post_category_id, post_category_name, UNIX_TIMESTAMP(created_at) AS created_at FROM post_category WHERE deleted_at IS NULL;";
        $data = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        /** @var PostCategory[] $categories */
        $categories = [];
        
        foreach($data as $row)
        {
            $categories[] = new PostCategory($row["post_category_id"], $row["post_category_name"], $row["created_at"]);   
        }

        return $categories;
    }
    public function deleteCategory(int $categoryId): void
    {
        $this->conn->beginTransaction();

        try
        {
            $stmt = $this->conn->prepare("
                UPDATE post_category
                SET deleted_at = NOW()
                WHERE post_category_id = :id
                AND deleted_at IS NULL
            ");
            $stmt->execute(["id" => $categoryId]);

            $stmt = $this->conn->prepare("
                UPDATE post_post_category
                SET deleted_at = NOW()
                WHERE post_category_id = :id
                AND deleted_at IS NULL
            ");
            $stmt->execute(["id" => $categoryId]);

            $this->conn->commit();
        }
        catch (Throwable $e)
        {
            $this->conn->rollBack();
            throw $e;
        }
    }
}