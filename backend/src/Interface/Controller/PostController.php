<?php
declare(strict_types=1);
namespace src\Interface\Controller;

require_once(__DIR__ . "/../../../autoload.php");

use src\Application\DTO\Post\PostGetAllDTO;
use src\Application\Service\Post\PostGetAllService;
use src\Infrastructure\Http\Request;
use src\Interface\Mapper\PostMapper;
use src\Shared\Exception\BusinessException\RequestDataFormatException;
use src\Shared\Exception\ExceptionHandler;
use src\Infrastructure\Http\Respond;
use src\Domain\Entity\Post;
use Throwable;

class PostContoller
{
    public function __construct
    (
        private Request $request,
        private PostGetAllService $postGetAllService
    ){}
    
    public function getPosts(): void
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
            if(!$page && !is_int($page))
                throw new RequestDataFormatException("page", "int", true);
            if(!$limit && !is_int($limit))
                throw new RequestDataFormatException("limit", "int", true);
            if(!$search && !is_string($search))
                throw new RequestDataFormatException("search", "string", true);
            if(!$category && !is_string($category))
                throw new RequestDataFormatException("category", "string", true);
            if(!$author && !is_string($author))
                throw new RequestDataFormatException("author", "string", true);
            if(!$sort && !is_string($sort))
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
    public function getPost(string $postId): void {}
    public function createPost(): void {}
    public function updatePost(string $postId): void {}
    public function deletePost(string $postId): void {}

    public function getComments(string $postId): void {}
    public function commentPost(string $postId): void {}
    public function updateComment(string $commentId): void {}
    public function deleteComment(string $commentId): void {}

    public function likePost(string $postId): void {}
    public function removeLike(string $postId): void {}
    public function dislikePost(string $postId): void {}
    public function removeDislike(string $postId): void {}
}