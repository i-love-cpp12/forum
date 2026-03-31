<?php
declare(strict_types=1);

namespace src\Interface\Mapper;

use src\Domain\Entity\Entity;

class EntityMapper
{
    public static function map(Entity $entity): array
    {
        return [
            "id" => $entity->getId(),
            "createdAtTimeStamp" => $entity->createdAtTimeStamp
        ];
    }
}