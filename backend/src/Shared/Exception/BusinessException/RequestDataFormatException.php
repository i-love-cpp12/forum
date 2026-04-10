<?php
declare(strict_types=1);

namespace src\Shared\Exception\BusinessException;

use src\Shared\Exception\BusinessException\BusinessException;

class RequestDataFormatException extends BusinessException
{
    public function __construct(string $varName, string $dataType, bool $isGetRequest = false)
    {
        if(!$isGetRequest)
            parent::__construct("Request body must contain `$varName` of ($dataType) type");
        else
            parent::__construct("URL praram `$varName` must be ($dataType) type");
    }
}