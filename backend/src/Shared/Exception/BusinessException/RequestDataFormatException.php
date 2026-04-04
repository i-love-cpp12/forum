<?php
declare(strict_types=1);

namespace src\Shared\Exception\BussinessException;

use src\Shared\Exception\BussinessException\BusinessException;

class RequestDataFormatException extends BusinessException
{
    public function __construct(string $varName, string $dataType)
    {
        parent::__construct("Request body must contain `$varName` of ($dataType) type");
    }
}