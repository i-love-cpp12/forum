<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

use src\Application\Service\ServiceHelper;
use src\Domain\Repository\PostRepositoryInterface;
use src\Shared\Exception\BusinessException;
use src\Application\DTO\Post\PostUpdateDTO;
use src\Domain\Entity\Post;
use src\Domain\Entity\PostCategory;
use src\Domain\Repository\CategoryRepositoryInterface;

require_once(__DIR__ . "/../../../../autoload.php");

class PostUpdateService
{
    public function __construct
    (
        private PostRepositoryInterface $postRepo,
        private CategoryRepositoryInterface $categoryRepo
    )
    {}

    public function execute(PostUpdateDTO $DTO): void
    {
        if
        (
            $DTO->newHeader === null &&
            $DTO->newContent === null &&
            $DTO->newCategories === null &&
            $DTO->categoriesToDelete === null
        )
        {
            return;
        }

        if(!ServiceHelper::authUserAction($DTO->loggedUserId, $DTO->loggedUserRole, $DTO->postAuthorId))
            throw new BusinessException("You are not authorized user to make this action", 401);

        $post = $this->postRepo->getPostById($DTO->postToUpdateId);
        
        if($post === null)
            throw new BusinessException("Post with id: $DTO->postToUpdateId not found", 404);

        if($DTO->newHeader !== null && !Post::validateHeader($DTO->newHeader))
            throw new BusinessException("Header: $DTO->newHeader is not valid");
        if($DTO->newContent !== null && !Post::validateContent($DTO->newContent))
            throw new BusinessException("Header: $DTO->newContent is not valid");

        $post->setHeader($DTO->newHeader);
        $post->setContent($DTO->newContent);
        
        foreach($DTO->newCategories as $categoryId)
        {
            $category = "";
            if
            (
                !is_int($categoryId) ||
                $categoryId < 0 ||
                ($category = $this->categoryRepo->getCategoryById($categoryId)) === null
            )
            {
                throw new BusinessException("categoryId: $categoryId is not valid");   
            }

            $post->addCategory($category);
        }

        foreach($DTO->categoriesToDelete as $categoryId)
        {
            if
            (
                !is_int($categoryId) ||
                $categoryId < 0 ||
                !$post->deleteCategory($categoryId)
            )
            {
                throw new BusinessException("categoryId: $categoryId is not valid");   
            }
        }
            
    }
}