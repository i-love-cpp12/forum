<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

require_once(__DIR__ . "/../../../../autoload.php");

use src\Application\DTO\Post\PostGetAllDTO;
use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Entity\Post;

class PostGetAllService
{
    public function __construct(private PostRepositoryInterface $postRepo){}

    /** @return Post[] */
    public function execute(PostGetAllDTO $DTO): array
    {
        return $this->postRepo->getAllPosts($DTO);
    }
}