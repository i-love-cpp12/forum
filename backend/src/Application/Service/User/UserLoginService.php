<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Application\DTO\User\LoginDTO;
use src\Domain\Entity\Token;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Exception\BusinessException;

class UserLoginService
{
    public static int $tokenDurationS = 60 * 60 * 5;

    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(LoginDTO $DTO): void
    {
        $user = $this->userRepo->getUserByEmail($DTO->email);

        $paswordHash = password_hash($DTO->password, "sha256");

        if($user === null || $user->passwordHash !== $paswordHash)
            throw new BusinessException("Invalid credentials email: $DTO->email password: " . str_repeat("*", strlen($DTO->password)), 401);

        $token = new Token(null, $user->getId(), $DTO->token, self::$tokenDurationS);

        $this->userRepo->activateToken($token);
    }
}