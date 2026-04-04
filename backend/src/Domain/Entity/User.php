<?php
declare(strict_types=1);

namespace src\Domain\Entity;

require_once(__DIR__ . "/../../../autoload.php");

use InvalidArgumentException;
use src\Shared\Validation\Validator;
use src\Domain\Entity\Entity;

enum UserRole: int
{
    case normal = 0;
    case admin = 1;
};

class User extends Entity
{
    private string $username;
    public static int $usernameMinLenght = 3;
    public static int $usernameMaxLenght = 50;
    public static string $usernameValidateMessage =
        "be (" . self::$usernameMinLenght . " - " . self::$usernameMaxLenght . ") character long";

    readonly public string $email;

    private string $passwordHash;
    public static int $passwordMinLenght = 8;
    public static string $passwordHashValidateMessage =
        "be valid sha256 hash";
    public static string $passwordValidateMessage =
        "contain at least one uppercase letter one lowercase letter and one special character and password must be at least (" . self::$passwordMinLenght . ") long";

    readonly public UserRole $role;

    public function __construct
    (
        ?int $id,
        string $username,
        string $email,
        string $passwordHash,
        UserRole $role = UserRole::normal,
        ?int $createdAtTimeStamp = null
    )
    {
        parent::__construct($id, $createdAtTimeStamp);
        
        $this->username = "";
        $this->passwordHash = "";

        $this->setUsername($username);
        $this->setPasswordByHash($passwordHash);

        $email = trim($email);

        if(!self::validateEmail($email))
            throw new InvalidArgumentException("email: $email is not valid email");

        $this->email = $email;
        $this->role = $role;
    }

    public function __toString(): string
    {
        return
            parent::__toString() .
            " | email: " . $this->email .
            " | username: " . $this->username .
            " | passwordHash: " . $this->passwordHash .
            " | role: " . self::roleToString($this->role);
    }

    public function getUsername(): string
    {
        return $this->username;
    }
    public function setUsername(string $username): void
    {
        $username = trim($username);

        if(!self::validateUsername($username))
            throw new InvalidArgumentException("username: $username must " . self::$usernameValidateMessage);

        $this->username = $username;
    }

    public function setPassword(string $password): void
    {
        $password = trim($password);

        if(!self::validatePassword($password))
            throw new InvalidArgumentException("password: " . self::hidePassword($password) . "must " . self::$passwordValidateMessage);

        $this->passwordHash = hash("sha256", $password);
    }

    public function setPasswordByHash(string $passwordHash): void
    {
        if(!self::validatePasswordHash($passwordHash))
            throw new InvalidArgumentException("password hash: $passwordHash must " . self::$passwordHashValidateMessage);
        $this->passwordHash = $passwordHash;
    }

    public function isPasswordCorrect(string $password): bool
    {
        return hash("sha256", $password) === $this->passwordHash;
    }

    public static function validateUsername(string $username): bool
    {
        $username = trim($username);

        return Validator::validateLenght($username, self::$usernameMinLenght, self::$usernameMaxLenght);
    }

    public static function validateEmail(string $email): bool
    {
        $email = trim($email);

        return Validator::validateEmail($email);
    }
    
    public static function validatePasswordHash(string $passwordHash): bool
    {
        return Validator::validateSha256($passwordHash);
    }

    public static function validatePassword(string $password)
    {
        $password = trim($password);

        return
            Validator::stringContain($password, true, true, true) &&
            Validator::validateLenght($password, self::$passwordMinLenght, null);
    }
    public static function hidePassword(string $password): string
    {
        return str_repeat("*", strlen($password));
    }
    public static function roleToString(UserRole $role): string
    {
        $roles = ["normal", "admin"];
        return $roles[$role->value];
    }
}