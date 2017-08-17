<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Domain\Search;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Search2d\Domain\Search\Image;
use Search2d\Infrastructure\Domain\Search\GuzzleImageFetcher;
use Search2d\Infrastructure\Domain\Search\UrlValidator;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Infrastructure\Domain\Search\GuzzleImageFetcher
 */
class GuzzleImageFetcherTest extends TestCase
{
    /**
     * @return void
     */
    public function testFetch(): void
    {
        $faker = $this->faker();

        $targetUrl = $faker->url;
        $targetImage = Image::create(file_get_contents($faker->image()));

        $stack = HandlerStack::create(new MockHandler([
            new Response(200, [], $targetImage->getData()),
        ]));

        $transactions = [];
        $stack->push(Middleware::history($transactions));

        $validator = $this->prophesize(UrlValidator::class);
        $validator->validate($targetUrl)->shouldBeCalled();

        $fetcher = new GuzzleImageFetcher(new Client(['handler' => $stack]), $validator->reveal());
        $image = $fetcher->fetch($targetUrl);

        $this->assertEquals($targetImage, $image);

        /** @var \GuzzleHttp\Psr7\Request $request */
        $request = $transactions[0]['request'];
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame($targetUrl, (string)$request->getUri());
    }
}