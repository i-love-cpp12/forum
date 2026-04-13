<?php
declare(strict_types=1);
namespace src\Interface\Controller;

use src\Application\DTO\Post\PostCreateDTO;
use src\Application\DTO\Post\PostDeleteDTO;
use src\Application\DTO\Post\PostGetAllDTO;
use src\Application\DTO\Post\PostGetCommentsDTO;
use src\Application\DTO\Post\PostUpdateDTO;
use src\Application\Service\Post\PostCreateService;
use src\Application\Service\Post\PostDeleteService;
use src\Application\Service\Post\PostGetAllService;
use src\Application\Service\Post\PostGetCommentsService;
use src\Application\Service\Post\PostGetService;
use src\Application\Service\Post\PostUpdateService;
use src\Infrastructure\Http\Request;
use src\Interface\Mapper\PostMapper;
use src\Shared\Exception\BusinessException\RequestDataFormatException;
use src\Shared\Exception\ExceptionHandler;
use src\Infrastructure\Http\Respond;
use src\Domain\Entity\Post;
use src\Domain\Entity\User;
use src\Domain\Entity\Comment;
use src\Domain\Entity\PostType;
use Throwable;

class PostController
{
    public function __construct
    (
        private Request $request,
        private PostGetAllService $postGetAllService,
        private PostGetService $postGetService,
        private PostCreateService $postCreateService,
        private PostUpdateService $postUpdateService,
        private PostDeleteService $postDeleteService,
        private PostGetCommentsService $postGetCommentsService
    ){}
    
    public function getAllPosts(): void
    {
        $page = $this->request->body["page"] ?? null;
        $limit = $this->request->body["limit"] ?? null;
        $search = $this->request->body["search"] ?? null;
        $category = $this->request->body["category"] ?? null;
        $author = $this->request->body["author"] ?? null;
        $sort = $this->request->body["sort"] ?? null;

        /** @var Post[] $posts */
        $posts = [];

        try
        {
            if($page !== null)
            {
                if(!is_numeric($page))
                    throw new RequestDataFormatException("page", "int", true);
                $page = intval($page);
            }
            if($limit !== null)
            {
                if(!is_numeric($limit))
                    throw new RequestDataFormatException("limit", "int", true);
                $limit = intval($limit);
            }

            if($search !== null && !is_string($search))
                throw new RequestDataFormatException("search", "string", true);
            if($category !== null && !is_string($category))
                throw new RequestDataFormatException("category", "string", true);
            if($author !== null && !is_string($author))
                throw new RequestDataFormatException("author", "string", true);
            if($sort !== null && !is_string($sort))
                throw new RequestDataFormatException("sort", "string", true);

            $DTO = new PostGetAllDTO
            (
                $page,
                $limit,
                $search,
                $category,
                $author,
                $sort
            );

            $posts = $this->postGetAllService->execute($DTO);

            
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        $postsMapped = [];

        foreach($posts as $post)
        {
            $postsMapped[] = PostMapper::map($post);
        }

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Getting all posts successful",
                        "posts" => $postsMapped
                    ]
            ]
        );
    }
    public function getPost(string $postId): void
    {
        $post = null;
        try
        {
            if(!ctype_digit($postId))
                throw new RequestDataFormatException("userId", "int", true);
            $postId = intval($postId);
            $post = $this->postGetService->execute($postId);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        $postMapped = PostMapper::map($post);

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Getting post with id: $postId successful",
                        "user" => $postMapped
                    ]
            ]
        );
    }
    public function createPost(): void
    {
        $this->createPostHelper(PostType::post);
    }
    public function createComment(): void
    {
        $this->createPostHelper(PostType::comment);
    }

    public function updatePost(string $postId): void
    {
        $this->updatePostHelper(PostType::post, $postId);
    }
    public function updateComment(string $postId): void
    {
        $this->updatePostHelper(PostType::comment, $postId);
    }

    public function deletePost(string $postId): void
    {
        $this->deletePostHelper(PostType::post, $postId);
    }
    public function deleteComment(string $postId): void
    {
        $this->deletePostHelper(PostType::comment, $postId);
    }

    public function getComments(string $postId): void
    {
        /** @var Comment[] $comments */
        $comments = [];
        
        $page = $this->request->body["page"] ?? null;
        $limit = $this->request->body["limit"] ?? null;

        try
        {
            if(!ctype_digit($postId))
                throw new RequestDataFormatException("postId", "int", true);
            
            if($page !== null)
            {
                if(!is_numeric($page))
                    throw new RequestDataFormatException("page", "int", true);
                $page = intval($page);
            }
            if($limit !== null)
            {
                if(!is_numeric($limit))
                    throw new RequestDataFormatException("limit", "int", true);
                $limit = intval($limit);
            }

            $postId = intval($postId);

            $postGetCommentDTO = new PostGetCommentsDTO($postId, $page, $limit);
            $comments = $this->postGetCommentsService->execute($postGetCommentDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        $commentsMapped = [];

        foreach($comments as $comment)
        {
            $commentsMapped[] = PostMapper::map($comment);
        }

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => "Getting all comments for postId: $postId successful",
                        "comments" => $commentsMapped
                    ]
            ]
        );
    }

    private function createPostHelper(PostType $type): void
    {
        $parentPostId = $this->request->body["parentPostId"] ?? null;
        $header = $this->request->body["header"] ?? null;
        $content = $this->request->body["content"] ?? null;
        $categories = $this->request->body["categories"] ?? null;

        /** @var User $user */
        $loggedUser = $this->request->getFromState("user");

        try
        {

            if($parentPostId !== null && !is_int($parentPostId))
                throw new RequestDataFormatException("parentPostId", "int");
            if($header !== null && !is_string($header))
                throw new RequestDataFormatException("header", "string");
            if($content === null || !is_string($content))
                throw new RequestDataFormatException("content", "string");
            if($categories !== null && !is_array($categories))
                throw new RequestDataFormatException("categories", "array");

            foreach($categories ?? [] as $category)
            {
                if(!is_int($category))
                    throw new RequestDataFormatException("category item", "int");
            }

            $postCreateDTO = new PostCreateDTO
            (
                $type->value,
                $parentPostId,
                $loggedUser->getId(),
                $header,
                $content,
                $categories
            );

            $this->postCreateService->execute($postCreateDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => ($parentPostId === null ? "Post with header: $header created successfully": "Comment with content: $content created successfully")
                    ]
            ]
        );
    }
    private function updatePostHelper(PostType $type, string $postId): void
    {
        $header = $this->request->body["header"] ?? null;
        $content = $this->request->body["content"] ?? null;
        $newCategories = $this->request->body["categoriesToAdd"] ?? null;
        $categoriesToDelete = $this->request->body["categoriesToDelete"] ?? null;

        /** @var User $user */
        $loggedUser = $this->request->getFromState("user");

        try
        {
            if(!ctype_digit($postId))
                throw new RequestDataFormatException("postId", "int", true);

            $postId = intval($postId);

            if($header !== null && !is_string($header))
                throw new RequestDataFormatException("header", "string");
            if($content !== null && !is_string($content))
                throw new RequestDataFormatException("content", "string");
            if($newCategories !== null && !is_array($newCategories))
                throw new RequestDataFormatException("newCategories", "array");
            if($categoriesToDelete !== null && !is_array($categoriesToDelete))
                throw new RequestDataFormatException("categoriesToDelete", "array");

            foreach([...($newCategories !== null ?$newCategories : []), ...($categoriesToDelete !== null ?$categoriesToDelete : [])] as $category)
            {
                if(!is_int($category))
                    throw new RequestDataFormatException("category item", "int");
            }

            $postUpdateDTO = new PostUpdateDTO
            (
                $type->value,
                $postId,
                $loggedUser->getId(),
                $loggedUser->role->value,
                $header,
                $content,
                $newCategories,
                $categoriesToDelete
            );

            $this->postUpdateService->execute($postUpdateDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }

        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" => ($type === PostType::post ? "Post" : "Comment") . " with id: $postId updated successfully"
                    ]
            ]
        );
    }
    private function deletePostHelper(PostType $type, string $postId): void
    {
        try
        {
            if(!ctype_digit($postId))
                throw new RequestDataFormatException("postId", "int", true);

            $postId = intval($postId);

            /** @var User $loggedUser */
            $loggedUser = $this->request->getFromState("user");

            $postDeleteDTO = new PostDeleteDTO
            (
                $type->value,
                $postId,
                $loggedUser->getId(),
                $loggedUser->role->value
            );
            $this->postDeleteService->execute($postDeleteDTO);
        }
        catch(Throwable $e)
        {
            ExceptionHandler::handle($e);
        }


        Respond::json(
            [
                "error" => "",
                "data" =>
                    [
                        "message" =>
                            "Deleting " . ($type === PostType::post ? "post" : "comment") . " with id: $postId successful"
                    ]
            ]
        );
    }
}