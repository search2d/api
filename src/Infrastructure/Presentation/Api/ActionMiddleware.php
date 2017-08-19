<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Presentation\Api;

use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ActionMiddleware
{
    /** @var callable */
    private $resolver;

    /** @var callable */
    private $handle404;

    /** @var callable */
    private $handle405;

    /**
     * @param callable $resolver
     * @param callable $handle404
     * @param callable $handle405
     */
    public function __construct(
        callable $resolver,
        callable $handle404,
        callable $handle405
    )
    {
        $this->resolver = $resolver;
        $this->handle404 = $handle404;
        $this->handle405 = $handle405;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $routeInfo = $request->getAttribute(RouterMiddleware::ATTRIBUTE);
        if ($routeInfo === null) {
            throw new \LogicException('ルーティングが完了していない');
        }

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                $action = $this->resolveAction($routeInfo[1]);
                return call_user_func($action, $request, $response, $routeInfo[2]);
            case Dispatcher::NOT_FOUND:
                return call_user_func($this->handle404, $request, $response);
            case Dispatcher::METHOD_NOT_ALLOWED:
                return call_user_func($this->handle405, $request, $response);
        }

        throw new \LogicException();
    }

    /**
     * @param string $name
     * @return callable
     */
    private function resolveAction(string $name): callable
    {
        return call_user_func($this->resolver, $name);
    }
}