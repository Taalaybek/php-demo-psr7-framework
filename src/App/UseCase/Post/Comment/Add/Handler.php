<?php

namespace App\UseCase\Post\Comment\Add;

use App\Repository\PostRepository;
use App\Repository\UnitOfWork;

class Handler
{
    private $posts;
    private $uow;

    public function __construct(PostRepository $posts, UnitOfWork $uow)
    {
        $this->posts = $posts;
        $this->uow = $uow;
    }

    public function handle(Command $command): void
    {
        $post = $this->posts->get($command->post);

        $post->addComment(
            $command->author,
            $command->content
        );

        $this->uow->commit();
    }
}
