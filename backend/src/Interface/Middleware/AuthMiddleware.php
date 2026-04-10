<?php
declare(strict_types=1);
namespace src\Interface\Middleware;

use src\Application\Service\User\UserGetLoggedByTokenService;
use src\Domain\Service\UserGetAuthTokenService;
use src\Infrastructure\Http\Request;
use src\Infrastructure\Http\Respond;
use src\Shared\Exception\ExceptionHandler;
use Throwable;

class AuthMiddleware
{
    public function __construct
    (
        private Request $request,
        private UserGetLoggedByTokenService $userGetLoggedByTokenService,
        private UserGetAuthTokenService $getUserAuthTokenService
    ){}
    public function execute(): void
    {
        try
        {
            
            $token = $this->getUserAuthTokenService->execute();

            $this->request->setStateItem
                ("user", $this->userGetLoggedByTokenService->execute($token));
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }
    }
}