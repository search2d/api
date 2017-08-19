<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Presentation\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Frontend
{
    /** @var callable[] */
    private $middlewares;

    /**
     * @param callable[] $middlewares
     */
    public function __construct(array $middlewares)
    {
        $this->middlewares = $middlewares;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $shouldNotBeCalled = function () {
            throw new ShouldNotBeCalledException();
        };

        $reducer = function (callable $next, callable $middleware): callable {
            return function (ServerRequestInterface $request, ResponseInterface $response) use ($next, $middleware): ResponseInterface {
                return $middleware($request, $response, $next);
            };
        };

        $handler = array_reduce(array_reverse($this->middlewares), $reducer, $shouldNotBeCalled);

        return $handler($request, $response);
    }
}