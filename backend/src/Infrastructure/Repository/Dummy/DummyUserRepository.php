<?php
declare(strict_types=1);

namespace src\Infrastructure\Repository\Dummy;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Entity\User;
use src\Domain\Entity\Token;
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

        for($i = 0; $i < 20; ++$i)
        {
            $this->saveToken(new Token(null, $this->users[$i]->getId(), hash("sha256", "oliwier$i"), $i * $i * 1000));
        }
        $this->saveToken(new Token(null, $this->users[20]->getId(), hash("sha256", "oliwier20"), null));
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

    public function saveToken(Token $token): void
    {
        DummyRepositoryHelper::saveEntity($token, $this->tokens, $this->nextTokenId);
    }
    public function deactivateToken(int $tokenId): void
    {
        $tokenToDeactivate =& ArrayHelper::find($this->tokens,
            function(Token $token) use($tokenId)
            {
                return $token->getId() === $tokenId;
            }
        );

        if($tokenToDeactivate !== null)
            $tokenToDeactivate->deactivate();
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