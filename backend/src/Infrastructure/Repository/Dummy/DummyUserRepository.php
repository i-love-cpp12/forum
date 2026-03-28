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

    public function saveUser(User $user): void
    {
        DummyRepositoryHelper::saveEntity($user, $this->users);
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
        DummyRepositoryHelper::saveEntity($token, $this->tokens);
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