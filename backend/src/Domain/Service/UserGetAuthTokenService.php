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
        $authHeader = explode(" ", (new Request())->headers["Authorization"] ?? "");
        if
        (
            count($authHeader) !== 2 ||
            $authHeader[0] !== "Bearer" ||
            !Validator::validateSha256($authHeader[1])
        )
        {
            throw new BusinessException("Authentication falied, invalid authentication headers: " . implode(" ", $authHeader), 401);
        }
        return $authHeader[1];
    }
}