<?php
declare(strict_types=1);

namespace src\Shared\Validation;

use Countable;
use InvalidArgumentException;

require_once(__DIR__ . "/../../../autoload.php");

class Validator
{
    static function validateLenght(Countable | string $countable, ?int $min, ?int $max): bool
    {
        $lenght = $countable instanceof string ? strlen($countable) : count($countable);

        if($min !== null && $max !== null && $min > $max)
            throw new InvalidArgumentException("min can not be grater then max");

        if($min !== null && $max !== null)
            return $lenght <= $max && $lenght >= $min;

        if($min !== null)
            return $lenght >= $min;

        if($max !== null)
            return $lenght >= $max; 

        return true;
    }
    static function validateSha256($hash): bool
    {
        return preg_match("/^[a-f0-9]{64}$/i", $hash);
    }
    static function stringContain
    (
        string $string,
        bool $lowerCaseLetters,
        bool $upperCaseLetters,
        bool $specialChars
    ): bool
    {
       $lowerCaseLettersRegExp = $lowerCaseLetters ? "(?=.*[a-z])":"";
       $upperCaseLettersRegExp = $upperCaseLetters ? "(?=.*[A-Z])":"";
       $specialCharsRegExp = $specialChars ? "(?=.*[\W_])":"";

       $regExp = "/^" . $lowerCaseLettersRegExp . $upperCaseLettersRegExp . $specialCharsRegExp . ".+$/";

       return preg_match($regExp, $string);
    }
}