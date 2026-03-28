<?php
declare(strict_types=1);
namespace src\Interface\Midlleware;

use src\Application\DTO\User\UserGetLoggedDTO;
use src\Application\Service\User\UserGetLoggedByTokenService;
use src\Domain\Service\GetUserAuthTokenService;
use src\Infrastructure\Http\Request;
use src\Shared\Exception\ExceptionHandler;
use Throwable;

require_once(__DIR__ . "/../../../autoload.php");


class AuthMiddleware
{
    public function __construct
    (
        private Request $request,
        private UserGetLoggedByTokenService $userGetLoggedByTokenService,
        private GetUserAuthTokenService $getUserAuthTokenService
    ){}
    public function execute(): void
    {
        try
        {
            
            $token = $this->getUserAuthTokenService->execute();
            $DTO = new UserGetLoggedDTO($token);

            $this->request->setStateItem("user", $this->userGetLoggedByTokenService->execute($DTO));
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }
    }
}