<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

use InvalidArgumentException;
use src\Domain\Entity\Post;
use src\Domain\Entity\User;
use src\Domain\Entity\UserRole;

use src\Application\Service\ServiceHelper;

use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Repository\CategoryRepositoryInterface;
use src\Application\DTO\Post\PostUpdateDTO;
use src\Domain\Entity\PostType;
use src\Shared\Exception\BusinessException\BusinessException;
use src\Shared\Exception\BusinessException\EntityNotFoundException;
use src\Shared\Exception\BusinessException\InvalidValueException;

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
        $post = $this->postRepo->getPostById($DTO->postToUpdateId);

        if(($postType = PostType::tryFrom($DTO->postType)) === null)
            throw new InvalidArgumentException("Post type $DTO->postType is not valid");

        ServiceHelper::validatePostType($postType, $post);
        
        if($postType === PostType::comment)
        {
            if($DTO->newHeader !== null)
                throw new BusinessException("Comment can not have header");
            if($DTO->categoriesToAdd !== null || $DTO->categoriesToDelete !== null)
                throw new BusinessException("Comment can not have category");
        }

        if
        (
            $DTO->newHeader === null &&
            $DTO->newContent === null &&
            $DTO->categoriesToAdd === null &&
            $DTO->categoriesToDelete === null
        )
        {
            throw new BusinessException("To update post you have to provide some of allowed data: newHeader (string), newContent(string), categoriesToAdd(array<int>), categoriesToDelete (array<int>)");
        }


        ServiceHelper::authorizeAction(
            UserRole::from($DTO->loggedUserRole),
            $DTO->loggedUserId,
            $post->userId
        );

        
        if($post === null)
            throw new EntityNotFoundException("Post", $DTO->postToUpdateId);

        if($DTO->newHeader !== null && !Post::validateHeader($DTO->newHeader))
            throw new InvalidValueException("New header", $DTO->newHeader, Post::getHeaderValidateMessage());
        if($DTO->newContent !== null && !Post::validateContent($DTO->newContent))
            throw new InvalidValueException("New content", $DTO->newContent, Post::getContentValidateMessage());


        if($DTO->newHeader !== null)
            $post->setHeader($DTO->newHeader);
        if($DTO->newContent !== null)
            $post->setContent($DTO->newContent);
        
        foreach($DTO->categoriesToAdd ?? [] as $categoryId)
        {
            $category = $this->categoryRepo->getCategoryById($categoryId);
            if
            (
                !is_int($categoryId) ||
                $categoryId < 0 ||
                $category === null
            )
            {
                throw new InvalidValueException("CategoryId", $categoryId);   
            }

            $post->addCategory($category);
        }

        foreach($DTO->categoriesToDelete ?? [] as $categoryId)
        {
            if
            (
                !is_int($categoryId) ||
                $categoryId < 0 ||
                !$post->deleteCategory($categoryId)
            )
            {
                throw new InvalidValueException("CategoryId", $categoryId);  
            }
        }
            
    }
}