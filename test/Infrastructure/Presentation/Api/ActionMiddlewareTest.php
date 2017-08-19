<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Presentation\Api;

use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Search2d\Infrastructure\Presentation\Api\ActionMiddleware;
use Search2d\Infrastructure\Presentation\Api\RouterMiddleware;
use Search2d\Test\TestCase;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class ActionMiddlewareTest extends TestCase
{
    /**
     * @return void
     */
    public function testRouteFound(): void
    {
        $routeInfo = [Dispatcher::FOUND, 'handler', ['p' => 1]];

        $orgReq = (new ServerRequest())->withAttribute(RouterMiddleware::ATTRIBUTE, $routeInfo);
        $orgRes = new Response();

        $actionCalled = false;
        $action = function (ServerRequestInterface $newReq, ResponseInterface $newRes, array $vars) use (&$actionCalled): ResponseInterface {
            $actionCalled = true;
            $this->assertSame(['p' => 1], $vars);
            return $newRes;
        };

        $resolverCalled = false;
        $resolver = function (string $name) use (&$resolverCalled, $action) {
            $resolverCalled = true;
            $this->assertSame('handler', $name);
            return $action;
        };

        $shouldNotBeCalled = function () {
            $this->fail();
        };

        $actionMiddleware = new ActionMiddleware(
            $resolver,
            $shouldNotBeCalled,
            $shouldNotBeCalled
        );
        $actionMiddleware($orgReq, $orgRes, $shouldNotBeCalled);

        $this->assertTrue($actionCalled);
        $this->assertTrue($resolverCalled);
    }

    /**
     * @return void
     */
    public function testRouteNotFound(): void
    {
        $routeInfo = [Dispatcher::NOT_FOUND];

        $orgReq = (new ServerRequest())->withAttribute(RouterMiddleware::ATTRIBUTE, $routeInfo);
        $orgRes = new Response();

        $handler404Called = false;
        $handler404 = function (ServerRequestInterface $newReq, ResponseInterface $newRes) use (&$handler404Called): ResponseInterface {
            $handler404Called = true;
            return $newRes;
        };

        $shouldNotBeCalled = function () {
            $this->fail();
        };

        $actionMiddleware = new ActionMiddleware(
            $shouldNotBeCalled,
            $handler404,
            $shouldNotBeCalled
        );
        $actionMiddleware($orgReq, $orgRes, $shouldNotBeCalled);

        $this->assertTrue($handler404Called);
    }

    /**
     * @return void
     */
    public function testRouteMethodNotAllowed(): void
    {
        $routeInfo = [Dispatcher::METHOD_NOT_ALLOWED];

        $orgReq = (new ServerRequest())->withAttribute(RouterMiddleware::ATTRIBUTE, $routeInfo);
        $orgRes = new Response();

        $handler405Called = false;
        $handler405 = function (ServerRequestInterface $newReq, ResponseInterface $newRes) use (&$handler405Called): ResponseInterface {
            $handler405Called = true;
            return $newRes;
        };

        $shouldNotBeCalled = function () {
            $this->fail();
        };

        $actionMiddleware = new ActionMiddleware(
            $shouldNotBeCalled,
            $shouldNotBeCalled,
            $handler405
        );
        $actionMiddleware($orgReq, $orgRes, $shouldNotBeCalled);

        $this->assertTrue($handler405Called);
    }
}