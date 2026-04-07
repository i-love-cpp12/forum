<?php
declare(strict_types=1);

namespace src\Shared\Validation;

use Countable;
use InvalidArgumentException;

class Validator
{
    static function validateLenght(Countable | string $countable, ?int $min, ?int $max): bool
    {
        $lenght = is_string($countable) ? strlen($countable) : count($countable);

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

    static function validateEmail($email): bool
    {
        return boolval(filter_var($email, FILTER_VALIDATE_EMAIL));
    }

    static function validateSha256($hash): bool
    {
        return boolval(preg_match("/^[a-f0-9]{64}$/i", $hash));
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