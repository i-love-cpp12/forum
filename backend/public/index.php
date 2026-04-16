<?php
declare(strict_types = 1);

require_once(__DIR__ . "/../autoload.php");

use src\Application\Service\Category\CategoryCreateService;
use src\Application\Service\Category\CategoryDeleteService;
use src\Application\Service\Category\CategoryGetAllService;
use src\Application\Service\Category\CategoryUpdateService;
use src\Application\Service\Like\LikeAddService;
use src\Application\Service\Like\LikeDeleteService;
use src\Application\Service\Like\LikeStatusService;
use src\Application\Service\Post\PostCreateService;
use src\Application\Service\Post\PostDeleteService;
use src\Application\Service\Post\PostGetAllService;
use src\Application\Service\Post\PostGetCommentsService;
use src\Application\Service\Post\PostGetService;
use src\Application\Service\Post\PostUpdateService;
use src\Application\Service\User\UserDeleteService;
use src\Application\Service\User\UserGetAllService;
use src\Application\Service\User\UserGetLoggedByTokenService;
use src\Application\Service\User\UserGetService;
use src\Application\Service\User\UserGetTokenByValueService;
use src\Application\Service\User\UserUpdateService;
use src\Application\Service\User\UserLoginService;
use src\Application\Service\User\UserLogoutService;
use src\Application\Service\User\UserRegisterService;
use src\Domain\Service\UserGenerateTokenService;
use src\Domain\Service\UserGetAuthTokenService;
use src\Infrastructure\Database\DBConnection;
use src\Infrastructure\Http\Request;
use src\Infrastructure\Repository\Dummy\DummyCategoryRepository;
use src\Infrastructure\Repository\Dummy\DummyLikeRepository;
use src\Infrastructure\Repository\Dummy\DummyPostRepository;
use src\Infrastructure\Repository\Dummy\DummyUserRepository;
use src\Infrastructure\Repository\PDO\PDOCategoryRepository;
use src\Infrastructure\Repository\PDO\PDOLikeRepository;
use src\Infrastructure\Repository\PDO\PDOPostRepository;
use src\Infrastructure\Repository\PDO\PDOUserRepository;
use src\Interface\Controller\CategoryController;
use src\Interface\Controller\LikeController;
use src\Interface\Controller\PostController;
use src\Interface\Controller\UserContoller;
use src\Interface\Middleware\AuthMiddleware;
use src\Interface\Router\Router;
use src\Shared\Exception\ExceptionHandler;

$request = new Request();

$router = new Router();
try
{
    $connection = DBConnection::getConnection();
}
catch(Throwable $e)
{
    ExceptionHandler::handle($e);
}

$userRepository = new PDOUserRepository($connection);
$postRepository = new PDOPostRepository($connection);
$likeRepository = new PDOLikeRepository($connection);
$categoryRepository = new PDOCategoryRepository($connection);

$userLoginService = new UserLoginService($userRepository);
$userRegisterService = new UserRegisterService($userRepository);
$userGenerateTokenService = new UserGenerateTokenService();
$userLogoutService = new UserLogoutService($userRepository);
$userGetAllService = new UserGetAllService($userRepository);
$userGetService = new UserGetService($userRepository);
$userUpdateService = new UserUpdateService($userRepository);
$userDeleteService = new UserDeleteService($userRepository);
$userGetTokenByValueService = new UserGetTokenByValueService($userRepository);
$userGetLoggedByTokenService = new UserGetLoggedByTokenService($userRepository);
$userGetAuthTokenService = new UserGetAuthTokenService($userRepository);

$postGetAllService = new PostGetAllService($postRepository);
$postGetService = new PostGetService($postRepository);
$postCreateService = new PostCreateService($connection, $postRepository, $userRepository, $categoryRepository);
$postUpdateService = new PostUpdateService($postRepository, $categoryRepository);
$postDeleteService = new PostDeleteService($postRepository);
$postGetCommentsService = new PostGetCommentsService($postRepository);

$likeStatusService = new LikeStatusService($likeRepository, $postRepository, $userRepository);
$likeAddService = new LikeAddService($connection, $likeRepository, $postRepository, $userRepository);
$likeDeleteService = new LikeDeleteService($connection, $likeRepository, $postRepository, $userRepository);

$categoryGetAllService = new CategoryGetAllService($categoryRepository);
$categoryCreateService = new CategoryCreateService($categoryRepository);
$categoryUpdateService = new CategoryUpdateService($categoryRepository);
$categoryDeleteService = new CategoryDeleteService($categoryRepository);


$authMiddleware = new AuthMiddleware(
    $request,
    $userGetLoggedByTokenService,
    $userGetAuthTokenService
);


$userController = new UserContoller(
    $request,
    $userLoginService,
    $userRegisterService,
    $userGenerateTokenService,
    $userLogoutService,
    $userGetAllService,
    $userGetService,
    $userUpdateService,
    $userDeleteService,
    $userGetTokenByValueService
);

$postController = new PostController(
    $request,
    $postGetAllService,
    $postGetService,
    $postCreateService,
    $postUpdateService,
    $postDeleteService,
    $postGetCommentsService
);

$likeController = new LikeController(
    $request,
    $likeStatusService,
    $likeAddService,
    $likeDeleteService
);

$categoryController = new CategoryController(
    $request,
    $categoryGetAllService,
    $categoryCreateService,
    $categoryUpdateService,
    $categoryDeleteService
);

$router->bind("POST", "api/register", [$userController, "register"]);
$router->bind("POST", "api/login", [$userController, "login"]);
$router->bind("POST", "api/logout", [$userController, "logout"],[
    [$authMiddleware, "execute"]
]);
$router->bind("GET", "api/users/{id}", [$userController, "getUser"]);
$router->bind("GET", "api/users", [$userController, "getAllUsers"]);
$router->bind("GET", "api/me", [$userController, "getLoggedUser"],[
    [$authMiddleware, "execute"]
]);
$router->bind("PUT", "api/users/{id}", [$userController, "updateUser"], [
    [$authMiddleware, "execute"]
]);
$router->bind("DELETE", "api/users/{id}", [$userController, "deleteUser"], [
    [$authMiddleware, "execute"]
]);


$router->bind("GET", "api/posts", [$postController, "getAllPosts"]);
$router->bind("GET", "api/posts/{id}", [$postController, "getPost"]);
$router->bind("POST", "api/posts", [$postController, "createPost"], [
    [$authMiddleware, "execute"]
]);
$router->bind("PUT", "api/posts/{id}", [$postController, "updatePost"], [
    [$authMiddleware, "execute"]
]);
$router->bind("DELETE", "api/posts/{id}", [$postController, "deletePost"], [
    [$authMiddleware, "execute"]
]);

$router->bind("GET", "api/posts/{id}/comments", [$postController, "getComments"]);
$router->bind("POST", "api/posts/{id}/comments", [$postController, "createComment"], [
    [$authMiddleware, "execute"]
]);
$router->bind("PUT", "api/comments/{id}", [$postController, "updateComment"], [
    [$authMiddleware, "execute"]
]);
$router->bind("DELETE", "api/comments/{id}", [$postController, "deleteComment"], [
    [$authMiddleware, "execute"]
]);

$router->bind("GET", "api/posts/{id}/like", [$likeController, "likeStatusPost"], [
    [$authMiddleware, "execute"]
]);
$router->bind("POST", "api/posts/{id}/like", [$likeController, "likePost"], [
    [$authMiddleware, "execute"]
]);
$router->bind("DELETE", "api/posts/{id}/like", [$likeController, "removePostLike"], [
    [$authMiddleware, "execute"]
]);
$router->bind("POST", "api/posts/{id}/dislike", [$likeController, "dislikePost"], [
    [$authMiddleware, "execute"]
]);
$router->bind("DELETE", "api/posts/{id}/dislike", [$likeController, "removePostDislike"], [
    [$authMiddleware, "execute"]
]);

$router->bind("GET", "api/comments/{id}/like", [$likeController, "likeStatusComment"], [
    [$authMiddleware, "execute"]
]);
$router->bind("POST", "api/comments/{id}/like", [$likeController, "likeComment"], [
    [$authMiddleware, "execute"]
]);
$router->bind("DELETE", "api/comments/{id}/like", [$likeController, "removeCommentLike"], [
    [$authMiddleware, "execute"]
]);
$router->bind("POST", "api/comments/{id}/dislike", [$likeController, "dislikeComment"], [
    [$authMiddleware, "execute"]
]);
$router->bind("DELETE", "api/comments/{id}/dislike", [$likeController, "removeCommentDislike"], [
    [$authMiddleware, "execute"]
]);


$router->bind("GET", "api/categories", [$categoryController, "getAllCategories"]);
$router->bind("POST", "api/categories", [$categoryController, "createCategory"], [
    [$authMiddleware, "execute"]
]);
$router->bind("PUT", "api/categories/{id}", [$categoryController, "updateCategory"], [
    [$authMiddleware, "execute"]
]);
$router->bind("DELETE", "api/categories/{id}", [$categoryController, "deleteCategory"], [
    [$authMiddleware, "execute"]
]);


$router->route($request->method, $request->uri);
