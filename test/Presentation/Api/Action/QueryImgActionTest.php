<?php
declare(strict_types=1);

namespace Search2d\Test\Presentation\Api\Action;

use League\Tactician\CommandBus;
use Prophecy\Argument;
use Search2d\Container;
use Search2d\Domain\Search\Image;
use Search2d\Domain\Search\QueriedImage;
use Search2d\Presentation\Api\Action\Api\QueryImgAction;
use Search2d\Presentation\Api\Helper;
use Search2d\Test\TestCase;
use Search2d\Usecase\Search\QueryImgCommand;
use Zend\Diactoros\UploadedFile;
use function GuzzleHttp\Psr7\stream_for;

/**
 * @covers \Search2d\Presentation\Api\Action\Api\QueryImgAction
 */
class QueryImgActionTest extends TestCase
{
    use HelperTrait;

    /**
     * @return void
     */
    public function testSuccess(): void
    {
        $faker = $this->faker();
        $fakeImg = file_get_contents($faker->image());

        $queriedImage = QueriedImage::create(Image::create($fakeImg));

        $commandBus = $this->prophesize(CommandBus::class);
        $commandBus->handle(Argument::type(QueryImgCommand::class))
            ->willReturn($queriedImage)
            ->shouldBeCalled();

        $this->container[QueryImgAction::class] = function (Container $container) use ($commandBus) {
            return new QueryImgAction($commandBus->reveal(), new Helper());
        };

        $file = new UploadedFile(stream_for($fakeImg), strlen($fakeImg), UPLOAD_ERR_OK);

        $response = $this->call('POST', '/api/query/img', '', [], ['img' => $file]);
        $this->assertSuccessResponse($response, 201, (object)['sha1' => (string)$queriedImage->getSha1()]);
    }

    /**
     * @return void
     */
    public function testNoFile(): void
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $commandBus->handle()->shouldNotBeCalled();

        $this->container[QueryImgAction::class] = function (Container $container) use ($commandBus) {
            return new QueryImgAction($commandBus->reveal(), new Helper());
        };

        $response = $this->call('POST', '/api/query/img');
        $this->assertFailureResponse($response, 400);
    }

    /**
     * @return void
     */
    public function testUploadErr(): void
    {
        $faker = $this->faker();
        $fakeImg = file_get_contents($faker->image());

        $file = new UploadedFile(stream_for($fakeImg), strlen($fakeImg), UPLOAD_ERR_INI_SIZE);

        $commandBus = $this->prophesize(CommandBus::class);
        $commandBus->handle()->shouldNotBeCalled();

        $this->container[QueryImgAction::class] = function (Container $container) use ($commandBus) {
            return new QueryImgAction($commandBus->reveal(), new Helper());
        };

        $response = $this->call('POST', '/api/query/img', '', [], ['img' => $file]);
        $this->assertFailureResponse($response, 400);
    }

    /**
     * @return void
     */
    public function testInvalidImage(): void
    {
        $invalidImg = 'invalid image';

        $file = new UploadedFile(stream_for($invalidImg), strlen($invalidImg), UPLOAD_ERR_OK);

        $commandBus = $this->prophesize(CommandBus::class);
        $commandBus->handle()->shouldNotBeCalled();

        $this->container[QueryImgAction::class] = function (Container $container) use ($commandBus) {
            return new QueryImgAction($commandBus->reveal(), new Helper());
        };

        $response = $this->call('POST', '/api/query/img', '', [], ['img' => $file]);
        $this->assertFailureResponse($response, 400);
    }
}