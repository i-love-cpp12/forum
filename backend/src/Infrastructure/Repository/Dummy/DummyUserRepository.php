<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\Dummy;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Entity\User;
use src\Domain\Entity\Token;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Array\ArrayHelper;

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
        if($user->getId() === null)
        {
            $this->users[] = $user;
            return;   
        }

        for($i = 0; $i < count($this->users); ++$i)
        {
            if($this->users[$i]->getId() === $user->getId())
            {
                $this->users[$i] = $user;
                return;    
            }
        }
    }

    /** @return User[]*/
    public function getAllUsers(): array
    {
        return $this->users;
    }
    public function getUserById(int $id): ?User
    {
        return ArrayHelper::find($this->users,
            function(User $user) use($id)
            {
                return $user->getId() === $id;
            }, $index
        );
    }
    public function getUserByEmail(string $email): ?User
    {
        return ArrayHelper::find($this->users,
            function(User $user) use($email)
            {
                return $user->email === $email;
            }, $index
        );
    }

    public function deleteUser(int $id): void
    {
        $userToDelete = ArrayHelper::find($this->users,
            function(User $user) use($id)
            {
                return $user->getId() === $id;
            }, $index
        );

        if($userToDelete !== null && $index !== null)
            ArrayHelper::deleteByIndex($this->users, $index);
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
                $token->isActive()
            )
            {
                $tokens[] = $token;
            }
        }

        return $tokens;
    }
}