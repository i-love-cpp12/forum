<?php
declare(strict_types=1);

namespace src\Shared\String;

class StringHelper
{
    public static function capitalize(string $str): string
    {
        if(strlen($str) < 2)
            return strtoupper($str);
        return strtoupper(substr($str, 0, 1)) . substr($str, 1);    
    }
}