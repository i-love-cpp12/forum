<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\Dummy;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Entity\User;
use src\Domain\Entity\Token;
use src\Domain\Repository\UserRepositoryInterface;

class DummyUserRepository implements UserRepositoryInterface
{

    /** @var User[] */
    private array $users;
    /** @var Token[] */
    private array $tokens;

    public function __construct()
    {
        for($i = 0; $i < 30; ++$i)
        {
            $this->users[] =
                (new User($i, "oliwier$i", "oliwier$i@gmail.com", hash("sha256", "oliwier$i")));
        }
        for($i = 0; $i < 20; ++$i)
        {
            $this->tokens[] =
                (new Token($i, $this->users[$i]->getId(), hash("sha256", "oliwier$i"), $i * $i * 1000));
        }
        $this->tokens[] =
            (new Token(20, $this->users[20]->getId(), hash("sha256", "oliwier20"), null));
    }
    public function save(User $user): void
    {

    }
    /** @return User[]*/
    public function getAllUsers(): array
    {
        return $this->users;
    }
    public function getUserById(int $id): ?User
    {
        foreach($this->users as $user)
        {
            if($user->getId() === $id)
                return $user;
        }
        return null;
    }
    public function getUserByEmail(string $email): ?User
    {
        foreach($this->users as $user)
        {
            if($user->email === $email)
                return $user;
        }
        return null;
    }

    public function deleteUser(int $id): void
    {
        $userIndex = null;
        foreach($this->users as $i => $user)
        {
            if($user->getId() === $id)
            {
                $userIndex = $i;
                break;
            }   
        }

        if($userIndex === null)
            return;
        $this->users =
            [...array_slice($this->users, 0, $userIndex), ...array_slice($this->users, $userIndex + 1)];
    }

    public function saveToken(Token $token, int $userId): void
    {

    }
    public function deactivateToken(int $tokenId): void
    {

    }
    /** @return Token[] */
    public function getActiveTokensForUser(int $userId): array
    {
        $tokens = [];

        foreach($this->tokens as $token)
        {
            if
            (
                $token->userId === $userId &&
                ($token->expireTimeStamp === null || $token->expireTimeStamp >= time())
            )
            {
                $tokens[] = $token;
            }
        }

        return $tokens;
    }
}