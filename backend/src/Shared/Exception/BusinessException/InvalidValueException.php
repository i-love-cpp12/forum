<?php
declare(strict_types=1);

namespace src\Shared\Exception\BusinessException;

use src\Shared\Exception\BusinessException\BusinessException;

class InvalidValueException extends BusinessException
{
    public function __construct(string $valueName, string | int $value, string $rule = "")
    {
        $ruleFullStr = !empty($rule) ? ", should $rule" : "";
        parent::__construct("$valueName: $value is not valid" . $ruleFullStr);
    }
}