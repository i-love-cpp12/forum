<?php
declare(strict_types=1);

namespace src\Application\Service\User;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Domain\Entity\Token;
use src\Domain\Repository\UserRepositoryInterface;
use src\Shared\Exception\BusinessException\BusinessException;

class UserGetTokenByValueService
{
    public function __construct(private UserRepositoryInterface $userRepo){}

    public function execute(string $token): Token
    {
        $tokenFound = $this->userRepo->getActiveTokenByValue($token);
        if($tokenFound === null)
            throw new BusinessException("Token: $token is not active");
        return $tokenFound;
    }
}