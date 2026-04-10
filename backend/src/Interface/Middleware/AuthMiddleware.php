<?php
declare(strict_types=1);
namespace src\Interface\Middleware;

use src\Application\Service\User\UserGetLoggedByTokenService;
use src\Domain\Entity\User;
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

            $this->request->setStateItem("user", $this->userGetLoggedByTokenService->execute($token));
            // $this->request->setStateItem("user", new User(1, "oliwier1", "oliwier1@gmail.com", "7860affef74a32972a1cb4b55c77baec3e5f56f4e2f34866c9b1fced4605da76"));
            // echo json_encode($this->request->getFromState("user"));
            // die();
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }
    }
}