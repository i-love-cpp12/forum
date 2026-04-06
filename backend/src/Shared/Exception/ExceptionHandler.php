<?php
declare(strict_types=1);

namespace src\Shared\Exception;

use src\Shared\Exception\BusinessException\BusinessException;
use PDOException;
use src\Infrastructure\Http\Respond;
use Throwable;

class ExceptionHandler
{
    public static function handle(Throwable $exception): void
    {
        if($exception instanceof BusinessException || $exception instanceof PDOException)
            Respond::json(["error" => $exception->getMessage(), "data" => []], $exception->getCode());
        throw $exception;
    }
}