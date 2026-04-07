<?php
declare(strict_types=1);

namespace src\Domain\Entity;

use InvalidArgumentException;
use LogicException;

class Entity
{
    private ?int $id;
    public readonly ?int $createdAtTimeStamp;
    
    public function __construct(?int $id, ?int $createdAtTimeStamp = null)
    {
        $this->id = null;
        if($id !== null)
            $this->setId($id);
        $this->createdAtTimeStamp = $createdAtTimeStamp;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        if($this->id)
            throw new LogicException("Id is already set");
        if($this->id < 0)
            throw new InvalidArgumentException("Id: $id can not be negative");
        $this->id = $id;
    }
    public function __toString(): string
    {
        return "id: $this->id | createdAtTimeStamp: " .
        ($this->createdAtTimeStamp !== null ? $this->createdAtTimeStamp : "null");
    }
}