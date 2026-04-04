<?php
declare(strict_types=1);

namespace src\Shared\Exception\BussinessException;

use src\Shared\Exception\BussinessException\BusinessException;

class AuthException extends BusinessException
{
    public function __construct(string $role = "")
    {
        $roleFullStr = !empty($role) ?  " with role: $role" : ""; 
        parent::__construct("You are not authorized user" . $roleFullStr . " to make this action", 401);
    }
}