<?php
declare(strict_types=1);

namespace Search2d\Test\Presentation\Api\Action;

use League\Tactician\CommandBus;
use Search2d\Container;
use Search2d\Domain\Search\ResultCollection;
use Search2d\Domain\Search\Sha1;
use Search2d\Presentation\Api\Action\Api\SearchAction;
use Search2d\Presentation\Api\Helper;
use Search2d\Test\TestCase;
use Search2d\Usecase\Search\SearchCommand;

/**
 * @covers \Search2d\Presentation\Api\Action\Api\SearchAction
 */
class SearchActionTest extends TestCase
{
    /**
     * @return void
     */
    public function testSuccess(): void
    {
        $faker = $this->faker();

        $sha1 = new Sha1($faker->sha1);

        $results = new ResultCollection([]);

        $commandBus = $this->prophesize(CommandBus::class);
        $commandBus->handle(new SearchCommand($sha1, 5, 10))
            ->willReturn($results)
            ->shouldBeCalled();

        $this->container[SearchAction::class] = function (Container $container) use ($commandBus) {
            return new SearchAction($commandBus->reveal(), $container[Helper::class]);
        };

        $response = $this->call('GET', '/search/' . $sha1);

        $this->assertSame(200, $response->getStatusCode());
    }
}