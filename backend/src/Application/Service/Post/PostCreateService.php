<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

use InvalidArgumentException;
use PDO;
use src\Domain\Entity\Comment;
use src\Application\DTO\Post\PostCreateDTO;
use src\Domain\Entity\Post;
use src\Domain\Entity\PostType;
use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Repository\CategoryRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;

use src\Shared\Exception\BusinessException\BusinessException;
use src\Shared\Exception\BusinessException\EntityNotFoundException;
use src\Shared\Exception\BusinessException\InvalidValueException;
use Throwable;

class PostCreateService
{
    public function __construct
    (
        private PDO $conn,
        private PostRepositoryInterface $postRepo,
        private UserRepositoryInterface $userRepo,
        private CategoryRepositoryInterface $categoryRepo
    )
    {}

    public function execute(PostCreateDTO $DTO): void
    {
        if(!Post::validateContent($DTO->content))
            throw new InvalidValueException("Content", $DTO->content, Post::getContentValidateMessage());

        if(!$this->userRepo->getUserById($DTO->userId))
            throw new EntityNotFoundException("User", $DTO->userId);

        if(($postType = PostType::tryFrom($DTO->postType)) === null)
            throw new InvalidArgumentException("Post type $DTO->postType is not valid");
        
        /** @var Post $post */
        $post = null;
        if($postType === PostType::post) //is post
        {
            if($DTO->parentPostId !== null)
                throw new BusinessException("Post can not have parentPostId");
            if($DTO->header === null)
                throw new BusinessException("Post must contain header");
            if(!Post::validateHeader($DTO->header))
                throw new InvalidValueException("Header", $DTO->header, Post::getHeaderValidateMessage());
            if($DTO->categories === null)
                throw new BusinessException("Post must contain categories");
            $post = new Post(null, null, $DTO->userId, $DTO->header, $DTO->content, []);
        }
        else //is comment
        {
            if($DTO->parentPostId === null)
                throw new BusinessException("Comment must contain parentPostId");
            if($DTO->header !== null || $DTO->categories !== null)
                throw new BusinessException("Comment can not have header or category");
            if(!$this->postRepo->getPostById($DTO->parentPostId))
                throw new EntityNotFoundException("Parent post", $DTO->parentPostId);
            $post = new Comment(null, $DTO->parentPostId, $DTO->userId, $DTO->content);
        }            


        foreach($DTO->categories ?? [] as $categoryId)
        {
            $category = $this->categoryRepo->getCategoryById($categoryId);

            if($category === null)
                throw new InvalidValueException("CategoryId", $categoryId);
            $post->addCategory($category);
        }
        $this->conn->beginTransaction();
        try
        {
            if($DTO->postType === PostType::comment)
                $this->postRepo->addComment($post->parentPostId);
            $this->postRepo->savePost($post);
            $this->conn->commit();
        }
        catch(Throwable $e)
        {
            $this->conn->rollBack();
            throw $e;
        }
    }
}