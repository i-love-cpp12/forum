<?php
declare(strict_types=1);

namespace src\Domain\Entity;

require_once(__DIR__ . "/../../../autoload.php");

use InvalidArgumentException;
use LogicException;
use src\Domain\Entity\Entity;
use src\Shared\Array\ArrayHelper;
use src\Shared\Validation\Validator;

class Post extends Entity
{
    public readonly ?int $parentPostId;

    public readonly int $userId;

    private ?string $header;
    public static $headerMinLenght = 1;
    public static $headerMaxLenght = 100;

    private string $content;
    public static $contentMinLenght = 1;
    public static $contentMaxLenght = 1000;

    /** @var PostCategory[] $categories */
    private array $categories;

    private int $likeCount;
    private int $dislikeCount;

    private int $commentCount;

    /** @param PostCategory[] $categories */
    public function __construct
    (
        ?int $id,
        ?int $parentPostId,
        int $userId,
        ?string $header,
        string $content,
        array $categories = [],
        int $likeCount = 0,
        int $dislikeCount = 0,
        int $commentCount = 0,
        ?int $createdAtTimeStamp = null
    )
    {
        parent::__construct($id, $createdAtTimeStamp);

        if($userId < 0)
            throw new InvalidArgumentException("userId: $userId can not be negative");

        if($parentPostId !== null && $parentPostId < 0)
            throw new InvalidArgumentException("parentPostId: $parentPostId can not be negative");

        $this->parentPostId = $parentPostId;
        $this->userId = $userId;
    
        $this->header = null;
        $this->content = "";
        $this->categories = [];

        if($likeCount < 0)
            throw new InvalidArgumentException("likeCount: $likeCount can not be negative");
        if($dislikeCount < 0)
            throw new InvalidArgumentException("dislikeCount: $dislikeCount can not be negative");
        if($commentCount < 0)
            throw new InvalidArgumentException("commentCount: $commentCount can not be negative");




        $this->likeCount = $likeCount;
        $this->dislikeCount = $dislikeCount;
        $this->commentCount = $commentCount;

        $this->setHeader($header);
        $this->setContent($content);
        $this->addCategories($categories);
    }

    public function setHeader(?string $header): void
    {
        if($header !== null && !self::validateHeader($header))
            throw new InvalidArgumentException("Header $header must " . self::getHeaderValidateMessage());
        if($header !== null && $this->parentPostId !== null)
            throw new LogicException("Header can not be set at comment");
        $this->header = $header;
    }

    public function getHeader(): ?string
    {
        return $this->header;
    }

    public function setContent(string $content): void
    {
        if(!self::validateContent($content))
            throw new InvalidArgumentException("Content $content must " . self::getContentValidateMessage());

        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;    
    }

    public function addCategory(PostCategory $category): void
    {
        if($category->getId() === null)
            throw new LogicException("Category added to post must be already saved");
        $this->categories[] = $category;
    }

    /** @param PostCategory[] $categories */
    public function addCategories(array $categories): void
    {
        foreach($categories as $category)
        {
            if(!$category instanceof PostCategory)
                throw new InvalidArgumentException("All categories must be of PostCategory type");
            $this->addCategory($category);
        }
    }

    public function deleteCategory(int $categoryId): bool
    {
        return ArrayHelper::deleteByItem
        (
            $this->categories,
            $categoryId,
            fn(PostCategory $category, int $categoryId) => ($category->getId() === $categoryId)
        );
    }

    /** @return PostCategory[] */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function like(Like $like): void
    {
        if($like->getId() !== null)
            throw new InvalidArgumentException("Like can not have id");
        if($like->type === LikeType::like)
            ++$this->likeCount;
        else if($like->type === LikeType::dislike)
            ++$this->dislikeCount;
    }

    public function deleteLike(Like $like): void
    {
        if($like->getId() !== null)
            throw new InvalidArgumentException("Like can not have id");
        if($like->type === LikeType::like && --$this->likeCount < 0)
            throw new LogicException("likeCount: $this->likeCount can not be negative");
        if($like->type === LikeType::dislike && --$this->dislikeCount < 0)
            throw new LogicException("dislikeCount: $this->dislikeCount can not be negative");
    }

    public function getLikeCount(): int
    {
        return $this->likeCount;
    }

    public function getDislikeCount(): int
    {
        return $this->dislikeCount;
    }

    public function incrementCommentCount(): void
    {
        ++$this->commentCount;
    }

    public function decrementCommentCount(): void
    {
        if(--$this->commentCount < 0)
            throw new LogicException("commentCount: $this->commentCount can not be negative");
    }

    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    public static function validateHeader(string $header): bool
    {
        return Validator::validateLenght($header, self::$headerMinLenght, self::$headerMaxLenght);
    }
    public static function getHeaderValidateMessage(): string
    {
        return "be (" . self::$headerMinLenght . " - " . self::$headerMaxLenght . ") long";
    }
    public static function validateContent(string $content): bool
    {
        return Validator::validateLenght($content, self::$contentMinLenght, self::$contentMaxLenght);
    }
    public static function getContentValidateMessage(): string
    {
        return "be string (" . self::$contentMinLenght . " - " . self::$contentMaxLenght . ") long";
    }
    public function __toString(): string
    {
        return
            parent::__toString() .
            " | parentPostId: " . ($this->parentPostId ?? "null") .
            " | userId: " . $this->userId .
            " | header: " . ($this->header ?? "null") .
            " | content: " . $this->content .
            " | categories: " . implode(", ", $this->categories) .
            " | likeCount: " . $this->likeCount .
            " | dislikeCount: " . $this->dislikeCount;
    }
}