<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Presentation\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Search2d\Infrastructure\Presentation\Api\Frontend;
use Search2d\Infrastructure\Presentation\Api\ShouldNotBeCalledException;
use Search2d\Test\TestCase;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class FrontendTest extends TestCase
{
    /**
     * @return void
     */
    public function testMiddlewaresOrder(): void
    {
        $counter = 0;

        $frontend = new Frontend([
            function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use (&$counter): ResponseInterface {
                // 1番目に呼ばれなければならない
                $counter++;
                $this->assertSame(1, $counter);
                return $next($request, $response);
            },
            function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use (&$counter): ResponseInterface {
                // 2番目に呼ばれなければならない
                $counter++;
                $this->assertSame(2, $counter);
                return $next($request, $response);
            },
            function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use (&$counter): ResponseInterface {
                // 3番目に呼ばれなければならない
                $counter++;
                $this->assertSame(3, $counter);
                return $response;
            },
        ]);

        $frontend->handle(new ServerRequest(), new Response());

        // 3つ全てのミドルウェアが呼ばれたら$counterは3になっている
        $this->assertSame(3, $counter);
    }

    /**
     * @return void
     */
    public function testShouldNotBeCalledException(): void
    {
        $this->expectException(ShouldNotBeCalledException::class);

        $frontend = new Frontend([]);
        $frontend->handle(new ServerRequest(), new Response());
    }
}