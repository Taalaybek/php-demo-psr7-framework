<?php

namespace App\Http\Action\Blog\Comment;

use App\ReadModel\PostReadRepository;
use App\UseCase\Post\Comment\Delete\Command;
use App\UseCase\Post\Comment\Delete\Handler;
use Framework\Http\Router\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\RedirectResponse;

class DeleteAction implements RequestHandlerInterface
{
    private $posts;
    private $handler;
    private $router;

    public function __construct(
        PostReadRepository $posts,
        Handler $handler,
        Router $router
    )
    {
        $this->posts = $posts;
        $this->handler = $handler;
        $this->router = $router;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!$post = $this->posts->find($request->getAttribute('post_id'))) {
            return new EmptyResponse(404);
        }

        $this->handler->handle(new Command($post->id, $request->getAttribute('id')));

        return new RedirectResponse($this->router->generate('blog.show', [
            'id' => $post->id,
        ]));
    }
}
