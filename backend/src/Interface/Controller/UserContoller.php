<?php
declare(strict_types=1);
namespace src\Interface\Controller;

use src\Application\Service\User\UserLogoutService;

require_once(__DIR__ . "/../../../autoload.php");



class UserContoller
{
    public function __construct(private UserLogoutService $userLogoutService){}
    public function login(): void
    {

    }
    public function logout(): void
    {
        
    }
    public function register(): void
    {

    }
    public function getAllUsers(): void
    {

    }
    public function getUser(int $userId): void
    {

    }
    public function updateUser(int $userId): void
    {

    }
    public function getLoggedUser(): void
    {

    }
    public function deleteUser(int $userId): void
    {

    }
}