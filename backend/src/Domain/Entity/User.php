<?php
declare(strict_types=1);

namespace src\Domain\Entity;

require_once(__DIR__ . "/../../../autoload.php");

use InvalidArgumentException;
use src\Shared\Validation\Validator;
use src\Domain\Entity\Entity;

class User extends Entity
{
    public string $username;
    public static $usernameMinLenght = 3;
    public static $usernameMaxLenght = 50;

    readonly public string $email;

    public string $passwordHash;

    public static $passwordMinLenght = 8;

    public function __construct(?int $id, string $username, string $email, string $passwordHash)
    {
        parent::__construct($id);
        
        $username = trim($username);
        $email = trim($username);

        if(!self::validateUsername($username))
            throw new InvalidArgumentException("username: $username must be (" . self::$usernameMinLenght . " - " . self::$usernameMaxLenght . ") character long");

        if(!self::validateEmail($email))
            throw new InvalidArgumentException("email: $email is not valid email");

        if(!self::validatePasswordHash($passwordHash))
            throw new InvalidArgumentException("password hash: $passwordHash is not valid sha256 hash");

        $this->username = $username;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public static function validateUsername(string $username): bool
    {
        return Validator::validateLenght($username, self::$usernameMinLenght, self::$usernameMaxLenght);
    }
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    public static function validatePasswordHash(string $passwordHash): bool
    {
        return Validator::validateSha256($passwordHash);
    }
    public static function validatePassword(string $password)
    {
        return
            Validator::stringContain($password, true, true, true) &&
            Validator::validateLenght($password, self::$passwordMinLenght, null);
    }
}