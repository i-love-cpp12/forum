<?php
declare(strict_types=1);

namespace src\Shared\File;

class FileHelper
{
    public static function readJSON(string $path): array | null
    {
        return json_decode(file_get_contents($path), true);
    }
    public static function writeJSON(string $path, array $data): void
    {
        file_put_contents($path, json_encode($data));
    }
}