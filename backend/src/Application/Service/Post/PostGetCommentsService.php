<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

use src\Application\DTO\Post\PostGetCommentsDTO;
use src\Domain\Entity\Post;
use src\Domain\Repository\PostRepositoryInterface;
use src\Shared\Exception\BusinessException\BusinessException;
use src\Shared\Exception\BusinessException\EntityNotFoundException;

class PostGetCommentsService
{
    public function __construct(private PostRepositoryInterface $postRepo){}

    /** @return Post[] */
    public function execute(PostGetCommentsDTO $DTO): array
    {
        if($DTO->page !== $DTO->limit && ($DTO->limit === null || $DTO->page === null))
            throw new BusinessException("Page and limit must be both provided or not");
        $post = $this->postRepo->getCommentsForPost($DTO);
        if($post === null)
            throw new EntityNotFoundException("Post", $DTO->postId);
        $comments = $this->postRepo->getCommentsForPost($DTO);
        return $comments;
    }
}