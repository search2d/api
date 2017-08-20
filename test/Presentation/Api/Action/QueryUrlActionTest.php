<?php
declare(strict_types=1);

namespace Search2d\Test\Presentation\Api\Action;

use League\Tactician\CommandBus;
use Search2d\Container;
use Search2d\Domain\Search\Image;
use Search2d\Domain\Search\QueriedImage;
use Search2d\Presentation\Api\Action\Api\QueryUrlAction;
use Search2d\Presentation\Api\Helper;
use Search2d\Test\TestCase;
use Search2d\Usecase\Search\QueryUrlCommand;

/**
 * @covers \Search2d\Presentation\Api\Action\Api\QueryUrlAction
 */
class QueryUrlActionTest extends TestCase
{
    use HelperTrait;

    /**
     * @return void
     */
    public function testSuccess(): void
    {
        $faker = $this->faker();

        $fakeUrl = $faker->url;
        $fakeImg = Image::create(file_get_contents($faker->image()));

        $queriedImage = QueriedImage::create($fakeImg);

        $commandBus = $this->prophesize(CommandBus::class);
        $commandBus->handle(new QueryUrlCommand($fakeUrl))
            ->willReturn($queriedImage)
            ->shouldBeCalled();

        $this->container[QueryUrlAction::class] = function (Container $container) use ($commandBus) {
            return new QueryUrlAction($commandBus->reveal(), new Helper());
        };

        $response = $this->call('POST', '/api/query/url', ['url' => $fakeUrl]);
        $this->assertSuccessResponse($response, 201, (object)['sha1' => (string)$queriedImage->getSha1()]);
    }

    /**
     * @return void
     */
    public function testInvalidJSON(): void
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $commandBus->handle()->shouldNotBeCalled();

        $this->container[QueryUrlAction::class] = function (Container $container) use ($commandBus) {
            return new QueryUrlAction($commandBus->reveal(), new Helper());
        };

        $response = $this->call('POST', '/api/query/url', 'invalid json');
        $this->assertFailureResponse($response, 400);
    }

    /**
     * @return void
     */
    public function testInvalidURL(): void
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $commandBus->handle()->shouldNotBeCalled();

        $this->container[QueryUrlAction::class] = function (Container $container) use ($commandBus) {
            return new QueryUrlAction($commandBus->reveal(), new Helper());
        };

        $response = $this->call('POST', '/api/query/url', ['url' => 'invalid url']);
        $this->assertFailureResponse($response, 400);
    }
}