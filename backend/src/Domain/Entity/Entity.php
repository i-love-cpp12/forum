<?php
declare(strict_types=1);

namespace src\Domain\Entity;

use LogicException;

require_once(__DIR__ . "/../../../autoload.php");

class Entity
{
    private ?int $id;
    
    public function __construct(?int $id)
    {
        $this->id;
        $this->setId($id);
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        if($this->id)
            throw new LogicException("Id is already set");
        $this->id = $id;
    }
}