<?php
declare(strict_types=1);
namespace src\Domain\Service;

use src\Infrastructure\Http\Request;
use src\Shared\Exception\BusinessException\BusinessException;
use src\Shared\Validation\Validator;


class UserGetAuthTokenService
{
    public static function execute(): string
    {
        //Authorization: Bearer TOKEN_CONTENT
        $authHeader = (new Request())->headers["Authorization"] ?? "";

        preg_match("/^(Bearer) ([0-9a-f]{64})$/", $authHeader, $args);
        // die(json_encode($args));
        if
        (
            count($args) !== 3 ||
            !Validator::validateSha256($args[2])
        )
        {
            throw new BusinessException("Authentication falied, invalid authentication headers: " . $authHeader, 401);
        }
        return $args[2];
    }
}