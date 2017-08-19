<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Presentation\Api;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Search2d\Infrastructure\Presentation\Api\RouterMiddleware;
use Search2d\Test\TestCase;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

/**
 * @covers  \Search2d\Infrastructure\Presentation\Api\RouterMiddleware
 */
class RouterMiddlewareTest extends TestCase
{
    /**
     * @return void
     */
    public function testRoute(): void
    {
        $orgReq = new ServerRequest([], [], 'http://example.com/users', 'GET');
        $orgRes = new Response();

        $nextCalled = false;
        $next = function (ServerRequestInterface $newReq, ResponseInterface $newRes) use (&$nextCalled, $orgReq, $orgRes): ResponseInterface {
            $nextCalled = true;

            $routeInfo = $newReq->getAttribute(RouterMiddleware::ATTRIBUTE);
            $this->assertNotNull($routeInfo);

            list($result, $handler, $vars) = $routeInfo;
            $this->assertSame(Dispatcher::FOUND, $result);
            $this->assertSame('handler', $handler);
            $this->assertSame([], $vars);

            // RouterMiddlewareでアクションに渡す前にレスポンスを改変してはならない
            $this->assertSame($orgRes, $newRes);

            return $newRes;
        };

        $router = new RouterMiddleware(\FastRoute\simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/users', 'handler');
        }));
        $router($orgReq, $orgRes, $next);

        $this->assertTrue($nextCalled);
    }
}