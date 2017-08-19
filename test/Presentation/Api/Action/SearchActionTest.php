<?php
declare(strict_types=1);

namespace Search2d\Test\Presentation\Api\Action;

use League\Tactician\CommandBus;
use Prophecy\Argument;
use Search2d\Container;
use Search2d\Domain\Search\ResultCollection;
use Search2d\Domain\Search\Sha1;
use Search2d\Presentation\Api\Action\Api\SearchAction;
use Search2d\Presentation\Api\Helper;
use Search2d\Test\TestCase;
use Search2d\Usecase\Search\QueriedImageNotFoundException;
use Search2d\Usecase\Search\SearchCommand;

/**
 * @covers \Search2d\Presentation\Api\Action\Api\SearchAction
 */
class SearchActionTest extends TestCase
{
    use HelperTrait;

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
            return new SearchAction($commandBus->reveal(), new Helper());
        };

        $response = $this->call('GET', '/search/' . $sha1);
        $this->assertSuccessResponse($response, 200, []);
    }

    /**
     * @return void
     */
    public function testInvalidSHA1(): void
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $commandBus->handle(Argument::any())->shouldNotBeCalled();

        $this->container[SearchAction::class] = function (Container $container) use ($commandBus) {
            return new SearchAction($commandBus->reveal(), new Helper());
        };

        $response = $this->call('GET', '/search/0000000000');
        $this->assertFailureResponse($response, 404);
    }

    /**
     * @return void
     */
    public function testQueriedImageNotFound(): void
    {
        $faker = $this->faker();

        $sha1 = new Sha1($faker->sha1);

        $commandBus = $this->prophesize(CommandBus::class);
        $commandBus->handle(new SearchCommand($sha1, 5, 10))
            ->willThrow(QueriedImageNotFoundException::class)
            ->shouldBeCalled();

        $this->container[SearchAction::class] = function (Container $container) use ($commandBus) {
            return new SearchAction($commandBus->reveal(), new Helper());
        };

        $response = $this->call('GET', '/search/' . $sha1);
        $this->assertFailureResponse($response, 404);
    }
}