<?php
declare(strict_types=1);
namespace src\Interface\Controller;

require_once(__DIR__ . "/../../../autoload.php");

use src\Application\Service\Like\LikeAddService;
use src\Application\Service\Like\LikeDeleteService;
use src\Application\Service\Like\LikeStatusService;
use src\Application\Service\Post\PostAddLikeSerivce;
use src\Application\Service\Post\PostDeleteLikeService;
use src\Infrastructure\Http\Request;


class LikeController
{
    public function __construct
    (
        private Request $request,
        private LikeStatusService $likeStatusService,
        private LikeAddService $likeAddService,
        private PostAddLikeSerivce $postAddLikeSerivce,
        private LikeDeleteService $likeDeleteService,
        private PostDeleteLikeService $postDeleteLikeService
    ){}
    
    public function likePost(string $postId): void {}
    public function removeLike(string $postId): void {}
    public function dislikePost(string $postId): void {}
    public function removeDislike(string $postId): void {}
}