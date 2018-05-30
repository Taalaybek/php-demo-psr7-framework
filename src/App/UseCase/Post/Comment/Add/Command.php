<?php

namespace App\UseCase\Post\Comment\Add;

class Command
{
    public $post;
    public $author;
    public $content;

    public function __construct(int $post)
    {
        $this->post = $post;
    }
}
