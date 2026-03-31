<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Application\DTO\User\UserLoginDTO;
use src\Domain\Entity\Token;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Exception\BusinessException;
use src\Domain\Entity\User;

class UserLoginService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(UserLoginDTO $DTO): void
    {
        $user = $this->userRepo->getUserByEmail($DTO->email);

        if($user === null || !$user->isPasswordCorrect($DTO->password))
            throw new BusinessException("Invalid credentials email: $DTO->email password: " . User::hidePassword($DTO->password), 401);

        $token = new Token(null, $user->getId(), $DTO->token, time() + Token::$tokenDurationS);
        $this->userRepo->activateToken($token);
    }
}