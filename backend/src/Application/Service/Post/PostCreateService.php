<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

use src\Application\DTO\Post\PostCreateDTO;
use src\Domain\Entity\Post;

use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Repository\CategoryRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;

use src\Shared\Exception\BusinessException\BusinessException;
use src\Shared\Exception\BusinessException\EntityNotFoundException;
use src\Shared\Exception\BusinessException\InvalidValueException;

require_once(__DIR__ . "/../../../../autoload.php");

class PostCreateService
{
    public function __construct
    (
        private PostRepositoryInterface $postRepo,
        private UserRepositoryInterface $userRepo,
        private CategoryRepositoryInterface $categoryRepo
    )
    {}

    public function execute(PostCreateDTO $DTO): void
    {
        if($DTO->parentPostId === null && ($DTO->header === null || $DTO->categories === null))
            throw new BusinessException("Post must contain header and category list");
        if($DTO->parentPostId !== null && ($DTO->header !== null || ($DTO->categories !== null && $DTO->categories !== [])))
            throw new BusinessException("Comment can not have header or category");

        if($DTO->parentPostId !== null && !Post::validateHeader($DTO->header))
            throw new InvalidValueException("Header", $DTO->header, Post::getHeaderValidateMessage());
        if(!Post::validateContent($DTO->content))
            throw new InvalidValueException("Content", $DTO->content, Post::getContentValidateMessage());

        if(!$this->userRepo->getUserById($DTO->userId))
            throw new EntityNotFoundException("User", $DTO->userId);

        if($DTO->parentPostId !== null && !$this->postRepo->getPostById($DTO->parentPostId))
            throw new BusinessException("Comment parentPostId not found in posts", 404);

        $post = null;

        $post = new Post(null, $DTO->parentPostId, $DTO->userId, $DTO->header, $DTO->content, []);

        foreach($DTO->categories as $categoryId)
        {
            $category = $this->categoryRepo->getCategoryById($categoryId);

            if($category === null)
                throw new InvalidValueException("CategoryId", $categoryId);
            $post->addCategory($category);
        }

        $this->postRepo->savePost($post);
    }
}