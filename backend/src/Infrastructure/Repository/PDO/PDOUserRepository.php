<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\PDO;

use LogicException;
use PDO;
use src\Domain\Entity\User;
use src\Domain\Entity\Token;
use src\Domain\Entity\UserRole;
use src\Domain\Repository\UserRepositoryInterface;
use src\Interface\Mapper\TokenMapper;

class PDOUserRepository implements UserRepositoryInterface
{

    public function __construct(private PDO $conn)
    {
    }

    public function saveUser(User $user): void
    {
        //insert
        if(($id = $user->getId()) === null)
        {
            $sql = "INSERT INTO _user (email, username, password_hash, user_role_id) VALUES (:email, :username, :password_hash, :user_role_id);";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["email" => $user->email, "username" => $user->getUsername(), "password_hash" => $user->getPasswordHash(), "user_role_id" => $user->role->value + 1]);
        }
        //update
        else
        {
            $sql = "UPDATE _user SET username = :username, password_hash = :password_hash WHERE user_id = :user_id AND deleted_at IS NULL;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["username" => $user->getUsername(), "password_hash" => $user->getPasswordHash(), "user_id" => $id]);
        }
    }

    /** @return User[]*/
    public function getAllUsers(): array
    {
        $data = $this->conn->query("SELECT user_id, email, username, password_hash, user_role_id, UNIX_TIMESTAMP(created_at) AS created_at FROM _user WHERE deleted_at IS NULL;")->fetchAll(PDO::FETCH_ASSOC);

        /** @var User[] $result */
        $result = [];

        foreach($data as $row)
        {
            $result[] = new User($row["user_id"], $row["username"], $row["email"], $row["password_hash"], UserRole::from($row["user_role_id"] - 1), $row["created_at"]);            
        }

        return $result;
    }
    public function getUserById(int $id): ?User
    {
        $sql = "SELECT user_id, email, username, password_hash, user_role_id, UNIX_TIMESTAMP(created_at) AS created_at FROM _user WHERE user_id = :user_id AND deleted_at IS NULL;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["user_id" => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$data) return null;
        
        return new User($data["user_id"], $data["username"], $data["email"], $data["password_hash"], UserRole::from($data["user_role_id"] - 1), $data["created_at"]);
    }
    public function getUserByEmail(string $email): ?User
    {
        $sql = "SELECT user_id, email, username, password_hash, user_role_id, UNIX_TIMESTAMP(created_at) AS created_at FROM _user WHERE email = :email AND deleted_at IS NULL;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["email" => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$data) return null;
        
        return new User($data["user_id"], $data["username"], $data["email"], $data["password_hash"], UserRole::from($data["user_role_id"] - 1), $data["created_at"]);
    }

    public function deleteUser(int $id): void
    {
        $sql = "UPDATE _user SET deleted_at = NOW() WHERE user_id = :id AND deleted_at IS NULL;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    public function activateToken(Token $token): void
    {
        if($token->getId() !== null)
            throw new LogicException("To save token, it must not be saved yet");
        if(!$token->isActive())
            throw new LogicException("To save token, it must be active");

        $sql = "INSERT INTO user_token (user_id, `value`, created_at, expire_at) VALUES (:user_id, :token_value, FROM_UNIXTIME(:created_at), FROM_UNIXTIME(:expire_at));";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["user_id" => $token->userId, "token_value" => $token->value,"created_at" => $token->createdAtTimeStamp, "expire_at" => $token->expireTimeStamp]);
    }

    public function deactivateTokensForUser(int $userId): void
    {
        $sql = "UPDATE user_token SET is_active = 0 WHERE user_id = :id AND expire_at IS NULL || expire_at >= NOW();";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $userId]);
    }

    public function getActiveTokenByValue(string $tokenValue): ?Token
    {
        $sql = "SELECT user_token_id, user_id, `value`, UNIX_TIMESTAMP(created_at) AS expire_at, UNIX_TIMESTAMP(expire_at) AS expire_at FROM user_token WHERE `value` = :token_value AND (expire_at IS NULL OR expire_at >= NOW()) AND is_active = 1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["token_value" => $tokenValue]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$data) return null;
        
        return new Token($data["user_token_id"], $data["user_id"], $data["value"], $data["created_at"], $data["expire_at"]);
    }
}