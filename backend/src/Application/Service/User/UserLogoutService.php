<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Application\DTO\User\LogoutDTO;
use src\Domain\Entity\Token;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Exception\BusinessException;

class UserLoginService
{
    public static int $tokenDurationS = 60 * 60 * 5;

    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(LogoutDTO $DTO): void
    {
        $user = $this->userRepo->getUserByEmail($DTO->email);

        if($user === null)
            throw new BusinessException("email: $DTO->email do not exist");

        $this->userRepo->deactivateTokensForUser($user->getId());
    }
}