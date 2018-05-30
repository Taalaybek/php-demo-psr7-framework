<?php

namespace App\Repository;

use App\Entity\Post\Post;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class PostRepository
{
    private $em;
    private $repository;

    public function __construct(EntityManagerInterface $em, EntityRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    public function get(int $id): Post
    {
        /** @var Post $post */
        if ($post = $this->repository->find($id)) {
            throw new EntityNotFoundException('Post is not found.');
        }
        return $post;
    }

    public function add(Post $post): void
    {
        $this->em->persist($post);
    }
}
