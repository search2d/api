<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Domain\Search;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Search2d\Domain\Search\Sha1;
use Search2d\Infrastructure\Domain\Search\GuzzleNnsRepository;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Infrastructure\Domain\Search\GuzzleNnsRepository
 */
class GuzzleNnsRepositoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testSearchNoPoint(): void
    {
        $faker = $this->faker();

        $sha1 = new Sha1($faker->sha1);

        $stack = HandlerStack::create(new MockHandler([
            new Response(200, [], '{"points": []}'),
        ]));

        $transactions = [];
        $stack->push(Middleware::history($transactions));

        $repository = new GuzzleNnsRepository(new Client(['handler' => $stack]));
        $points = $repository->search($sha1, 5, 10);

        $this->assertCount(0, $points);

        /** @var \GuzzleHttp\Psr7\Request $request */
        $request = $transactions[0]['request'];
        $this->assertSame('GET', $request->getMethod());

        parse_str($request->getUri()->getQuery(), $params);
        $this->assertSame($sha1->value, $params['sha1']);
        $this->assertSame('5', $params['radius']);
        $this->assertSame('10', $params['count']);
    }
}