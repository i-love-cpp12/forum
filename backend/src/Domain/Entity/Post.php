<?php
declare(strict_types=1);

namespace src\Domain\Entity;

require_once(__DIR__ . "/../../../autoload.php");

use InvalidArgumentException;
use src\Domain\Entity\Entity;
use src\Shared\Validation\Validator;

class Post extends Entity
{
    public readonly int $userId;

    private ?string $header;
    public static $headerMinLenght = 1;
    public static $headerMaxLenght = 100;

    private string $content;
    public static $contentMinLenght = 1;
    public static $contentMaxLenght = 1000;

    /** @var Post[] $comments */
    private array $comments;

    private int $likeCount;
    private int $dislikeCount;

    public function __construct
    (
        ?int $id,
        int $userId,
        ?string $header,
        string $content,
        array $comments,
        int $likeCount = 0,
        int $dislikeCount = 0
    )
    {
        parent::__construct($id);

        if($userId < 0)
            throw new InvalidArgumentException("userId: $userId can not be negative");

        $this->header = null;
        $this->content = "";
        $this->comments = [];

        $this->setHeader($header);
        $this->setContent($content);
        foreach($comments as $comment)
        {
            if(!$comment instanceof Post)
                throw new InvalidArgumentException("Comment must be type of Post");
            $this->addComment($comment);
        }

    }

    public function setHeader(string $header): void
    {
        if($header !== null && !self::validateHeader($header))
            throw new InvalidArgumentException("header must be null or string (" . self::$headerMinLenght . " - " . self::$headerMaxLenght . ") long");
        $this->header = $header;
    }

    public function getHeader(): string
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

    public function addComment(Post $comment): void
    {
        $this->comments[] = $comment;
    }

    /** @return Post[] */
    public function getComments(): array
    {
        return $this->comments;
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
        return "";
    }
}