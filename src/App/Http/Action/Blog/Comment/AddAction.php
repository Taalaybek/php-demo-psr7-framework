<?php

namespace App\Http\Action\Blog\Comment;

use App\ReadModel\PostReadRepository;
use App\UseCase\Post\Comment\Add\Command;
use App\UseCase\Post\Comment\Add\Form;
use App\UseCase\Post\Comment\Add\Handler;
use Framework\Http\Router\Router;
use Framework\Template\TemplateRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class AddAction implements RequestHandlerInterface
{
    private $posts;
    private $template;
    private $handler;
    private $router;
    private $factory;

    public function __construct(
        PostReadRepository $posts,
        TemplateRenderer $template,
        Handler $handler,
        FormFactory $factory,
        Router $router
    )
    {
        $this->posts = $posts;
        $this->template = $template;
        $this->handler = $handler;
        $this->router = $router;
        $this->factory = $factory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!$post = $this->posts->find($request->getAttribute('id'))) {
            return new EmptyResponse(404);
        }

        $command = new Command($post->id);

        $form = $this->factory->createForm(Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handler->handle($command);
            return new RedirectResponse($this->router->generate('blog.show', [
                'id' => $post->id,
            ]));
        }

        return new HtmlResponse($this->template->render('blog/comment', [
            'post' => $post,
            'form' => $form->createView()
        ]), 400);
    }
}
