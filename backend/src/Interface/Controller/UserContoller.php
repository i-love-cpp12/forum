<?php
declare(strict_types=1);
namespace src\Interface\Controller;

require_once(__DIR__ . "/../../../autoload.php");



class UserContoller
{
    public function __construct(){}
    public function login()
    {
        
    }
    public function logout(){}
    public function register(){}
    public function getAllUsers(){}
    public function updateUsername(int $userId){}
    public function deleteUser(int $userId){}
}