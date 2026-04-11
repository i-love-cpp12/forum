<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\Dummy;

use LogicException;
use src\Domain\Entity\User;
use src\Domain\Entity\Token;
use src\Domain\Entity\UserRole;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Array\ArrayHelper;
use src\Infrastructure\Repository\Dummy\DummyRepositoryHelper;

class DummyUserRepository implements UserRepositoryInterface
{

    /** @var User[] */
    private array $users;
    private int $nextUserId;

    /** @var Token[] */
    private array $tokens;
    private int $nextTokenId;

    public function __construct()
    {
        $this->users = [];
        $this->tokens = [];
        $this->nextUserId = 0;
        $this->nextTokenId = 0;

        for($i = 0; $i < 30; ++$i)
        {
            $this->saveUser(new User(null, "oliwier$i", "oliwier$i@gmail.com", hash("sha256", "oliwier$i")));
        }

        $this->saveUser(new User(null, "admin", "admin@gmail.com", hash("sha256", "admin123"), UserRole::admin));

        for($i = 0; $i < 20; ++$i)
        {
            $this->activateToken(new Token(null, $this->users[$i]->getId(), hash("sha256", "oliwier$i"), time() - 1 + Token::$tokenDurationS * $i));
        }
        $this->activateToken(new Token(null, $this->users[20]->getId(), hash("sha256", "oliwier20"), null));
        $this->activateToken(new Token(null, $this->users[30]->getId(), hash("sha256", "admin123"), null));
    }

    public function saveUser(User $user): void
    {
        DummyRepositoryHelper::saveEntity($user, $this->users, $this->nextUserId);
    }

    /** @return User[]*/
    public function getAllUsers(): array
    {
        return DummyRepositoryHelper::getAllEntities($this->users);
    }
    public function getUserById(int $id): ?User
    {
        return DummyRepositoryHelper::getEntityById($id, $this->users);
    }
    public function getUserByUsername(string $username): ?User
    {
        return ArrayHelper::find($this->users,
            function(User $user) use($username)
            {
                return $user->getUsername() === $username;
            }
        );
    }
    public function getUserByEmail(string $email): ?User
    {
        return ArrayHelper::find($this->users,
            function(User $user) use($email)
            {
                return $user->email === $email;
            }
        );
    }

    public function deleteUser(int $id): void
    {
        DummyRepositoryHelper::deleteEntity($id, $this->users);
    }

    public function activateToken(Token $token): void
    {
        if($token->getId() !== null)
            throw new LogicException("Token must be new token");
        DummyRepositoryHelper::saveEntity($token, $this->tokens, $this->nextTokenId);
    }

    public function deactivateTokensForUser(int $userId): void
    {
        foreach($this->tokens as &$token)
        {
            if($token->userId === $userId)
                $token->deactivate();
        }
    }

    public function getActiveTokenByValue(string $tokenValue): ?Token
    {
        return ArrayHelper::find($this->tokens,
            function(Token $token) use($tokenValue)
            {
                return $token->value === $tokenValue && $token->isActive();
            }
        );
    }
}