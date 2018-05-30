<?php

namespace App\UseCase\Post\Comment\Delete;

class Command
{
    public $post;
    public $id;

    public function __construct(int $post, int $id)
    {
        $this->post = $post;
        $this->id = $id;
    }
}
