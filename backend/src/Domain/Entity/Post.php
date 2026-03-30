<?php
declare(strict_types=1);

namespace src\Domain\Entity;

require_once(__DIR__ . "/../../../autoload.php");

use InvalidArgumentException;
use LogicException;
use src\Domain\Entity\Entity;
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

    /** @var string[] $categories */
    // to think about
    public array $categories;

    private int $likeCount;
    private int $dislikeCount;

    public function __construct
    (
        ?int $id,
        ?int $parentPostId,
        int $userId,
        ?string $header,
        string $content,
        array $categories,
        int $likeCount = 0,
        int $dislikeCount = 0
    )
    {
        parent::__construct($id);

        if($userId < 0)
            throw new InvalidArgumentException("userId: $userId can not be negative");

        if($parentPostId !== null && $parentPostId < 0)
            throw new InvalidArgumentException("parentPostId: $parentPostId can not be negative");

        $this->parentPostId = $parentPostId;
        $this->userId = $userId;
    
        $this->header = null;
        $this->content = "";
        $this->likeCount = 0;
        $this->dislikeCount = 0;

        $this->setHeader($header);
        $this->setContent($content);
        $this->setLikeCount($likeCount);
        $this->setDislikeCount($dislikeCount);
    }

    public function setHeader(string $header): void
    {
        if($header !== null && !self::validateHeader($header))
            throw new InvalidArgumentException("header must be null or string (" . self::$headerMinLenght . " - " . self::$headerMaxLenght . ") long");
        if($this->parentPostId !== null)
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
            throw new InvalidArgumentException("content must be (" . self::$contentMinLenght . " - " . self::$contentMaxLenght . ") long");

        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;    
    }

    public function setLikeCount(int $likeCount): void
    {
        if($likeCount < 0)
            throw new InvalidArgumentException("likeCount: $likeCount can not be negative");

        $this->$likeCount = $likeCount;
    }

    public function getLikeCount(): int
    {
        return $this->likeCount;    
    }

    public function setDislikeCount(int $dislikeCount): void
    {
        if($dislikeCount < 0)
            throw new InvalidArgumentException("dislikeCount: $dislikeCount can not be negative");

        $this->$dislikeCount = $dislikeCount;
    }

    public function getDislikeCount(): int
    {
        return $this->dislikeCount;
    }

    public static function validateHeader(string $header): bool
    {
        return Validator::validateLenght($header, self::$headerMinLenght, self::$headerMaxLenght);
    }
    public static function validateContent(string $content): bool
    {
        return Validator::validateLenght($content, self::$contentMinLenght, self::$contentMaxLenght);
    }
    public function __toString(): string
    {
        return
            "id: " . $this->getId() .
            " | parentPostId: " . ($this->parentPostId ?? "null") .
            " | userId: " . $this->userId .
            " | header: " . ($this->header ?? "null") .
            " | content: " . $this->content .
            " | likeCount: " . $this->likeCount .
            " | dislikeCount: " . $this->dislikeCount;
    }
}