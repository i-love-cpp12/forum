<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

use src\Domain\Entity\Post;
use src\Domain\Entity\User;
use src\Domain\Entity\UserRole;

use src\Application\Service\ServiceHelper;

use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Repository\CategoryRepositoryInterface;
use src\Application\DTO\Post\PostUpdateDTO;

use src\Shared\Exception\BusinessException\EntityNotFoundException;
use src\Shared\Exception\BusinessException\InvalidValueException;

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

        ServiceHelper::authorizeAction(
            UserRole::from($DTO->loggedUserRole),
            $DTO->loggedUserId,
            $DTO->postAuthorId
        );

        $post = $this->postRepo->getPostById($DTO->postToUpdateId);
        
        if($post === null)
            throw new EntityNotFoundException("Post", $DTO->postToUpdateId);

        if($DTO->newHeader !== null && !Post::validateHeader($DTO->newHeader))
            throw new InvalidValueException("New header", $DTO->newHeader, Post::getHeaderValidateMessage());
        if($DTO->newContent !== null && !Post::validateContent($DTO->newContent))
            throw new InvalidValueException("New content", $DTO->newContent, Post::getContentValidateMessage());


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
                throw new InvalidValueException("CategoryId", $category);   
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
                throw new InvalidValueException("CategoryId", $category);  
            }
        }
            
    }
}