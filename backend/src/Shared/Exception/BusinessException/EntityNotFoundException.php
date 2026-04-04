<?php
declare(strict_types=1);

namespace src\Shared\Exception\BussinessException;

use src\Shared\Exception\BussinessException\BusinessException;

class EntityNotFoundException extends BusinessException
{
    public function __construct(string $entityName, string | int $identifier, string $identifierName = "id")
    {
        parent::__construct("$entityName with $identifierName: $identifier not found", 404);
    }
}