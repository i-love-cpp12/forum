<?php
declare(strict_types=1);

namespace src\Application\Service\Post;

use src\Application\DTO\Post\PostGetAllDTO;
use src\Domain\Repository\PostRepositoryInterface;
use src\Domain\Entity\Post;
use src\Shared\Exception\BusinessException\BusinessException;

class PostGetAllService
{
    public function __construct(private PostRepositoryInterface $postRepo){}

    /** @return Post[] */
    public function execute(PostGetAllDTO $DTO): array
    {
        if($DTO->page !== $DTO->limit && ($DTO->limit === null || $DTO->page === null))
            throw new BusinessException("Page and limit must be both provided or not");
        if($DTO->page <= 0 || $DTO->limit <= 0)
            throw new BusinessException("Page and limit must not be negative");
        return $this->postRepo->getAllPosts($DTO);
    }
}