<?php
declare(strict_types=1);

namespace Search2d\Test\Usecase\Pixiv;

use Cake\Chronos\Chronos;
use Prophecy\Argument;
use Psr\Log\NullLogger;
use Search2d\Domain\Pixiv\Illust;
use Search2d\Domain\Pixiv\IllustPage;
use Search2d\Domain\Pixiv\IllustPageCollection;
use Search2d\Domain\Pixiv\RemoteRepository;
use Search2d\Domain\Pixiv\RequestIllust;
use Search2d\Domain\Pixiv\RequestIllustReceiver;
use Search2d\Domain\Search\Image;
use Search2d\Test\TestCase;
use Search2d\Usecase\Pixiv\HandleRequestIllustCommand;
use Search2d\Usecase\Pixiv\HandleRequestIllustHandler;
use Search2d\Usecase\Search\IndexCommand;
use Search2d\Usecase\Search\IndexHandler;

/**
 * @covers \Search2d\Usecase\Pixiv\HandleRequestIllustHandler
 */
class HandleRequestIllustHandlerTest extends TestCase
{
    /**
     * @return void
     */
    public function testExecute(): void
    {
        $faker = $this->faker();

        $fakePage = new IllustPage(0, $faker->url);
        $fakeImage = Image::create(file_get_contents($faker->image()));
        $fakeIllust = new Illust(
            $faker->randomNumber(),
            $faker->url,
            $faker->text(128),
            new IllustPageCollection([$fakePage]),
            Chronos::now('UTC')
        );

        $success = null;
        $requestReceiver = $this->prophesize(RequestIllustReceiver::class);
        $requestReceiver->receive(Argument::type('callable'))
            ->will(function (array $args) use ($fakeIllust, &$success) {
                $success = call_user_func($args[0], new RequestIllust($fakeIllust->getId()));
            })
            ->shouldBeCalled();

        $remoteRepository = $this->prophesize(RemoteRepository::class);
        $remoteRepository->getIllust(Argument::is($fakeIllust->getId()))
            ->willReturn($fakeIllust)
            ->shouldBeCalled();
        $remoteRepository->getImage(Argument::is($fakePage->getImageUrl()))
            ->willReturn($fakeImage)
            ->shouldBeCalled();

        $indexHandler = $this->prophesize(IndexHandler::class);
        $indexHandler->handle(Argument::type(IndexCommand::class))
            ->shouldBeCalled();

        $handler = new HandleRequestIllustHandler(
            $requestReceiver->reveal(),
            $remoteRepository->reveal(),
            $indexHandler->reveal(),
            new NullLogger()
        );

        $handler->handle(new HandleRequestIllustCommand());

        $this->assertTrue($success);
    }
}