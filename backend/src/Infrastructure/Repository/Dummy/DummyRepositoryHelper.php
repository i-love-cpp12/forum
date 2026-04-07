<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\Dummy;

use src\Domain\Entity\Entity;
use src\Shared\Array\ArrayHelper;

class DummyRepositoryHelper
{
    /** @param Entity[] $entityStorage */
    public static function saveEntity(Entity $entity, array& $entityStorage, ?int& $nextId = null): void
    {
        if($entity->getId() === null)
        {
            if($nextId === null)
                return;
            $entity->setId($nextId++);
            $entityStorage[] = $entity;
            return;   
        }

        $entityToUpdate =& ArrayHelper::find($entityStorage,
            function(Entity $entityItem) use($entity)
            {
                return $entityItem->getId() === $entity->getId();
            }
        );

        if($entityToUpdate !== null)
            $entityToUpdate = $entity;
    }

    /** @param Entity[] $entityStorage */
    public static function deleteEntity(int $entityId, array& $entityStorage): void
    {
        ArrayHelper::deleteByItem(
            $entityStorage, $entityId,
            fn(Entity $entity, int $id) => ($entity->getId() === $id)
        );
    }

    /** @param Entity[] $entityStorage @return Entity[]*/
    public static function getAllEntities(array& $entityStorage): array
    {
        return $entityStorage;
    }

    /** @param Entity[] $entityStorage */
    public static function getEntityById(int $entityId, array& $entityStorage): ?Entity
    {
        return ArrayHelper::find($entityStorage,
            function(Entity $entity) use($entityId)
            {
                return $entity->getId() === $entityId;
            }
        );
    }
}