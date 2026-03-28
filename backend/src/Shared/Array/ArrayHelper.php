<?php
declare(strict_types=1);

namespace src\Shared\Array;

class ArrayHelper
{
    public static function deleteByIndex(array& $array, int $index): void
    {
        unset($array[$index]);
        $array = array_values($array);
    }

    public static function deleteByItem(array& $array, mixed $item, ?callable $equal = null): void
    {
        if(!$equal)
            $equal = fn($i1, $i2) => ($i1 === $i2);

        self::find($array, function($elem) use ($item, $equal){
            return $equal($elem, $item);
        }, $index);

        if($index !== null)
            self::deleteByIndex($array, $index);
    }

    public static function &find(array& $array, callable $callback, ?int &$index = null): mixed
    {
        $lenght = count($array);

        for($i = 0; $i < $lenght; ++$i)
        {
            if($callback($array[$i]))
            {
                $index = $i;
                return $array[$i];
            }
        }
        $index = null;
        $null = null;
        return $null;
    }
}