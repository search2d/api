<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Domain\Pixiv;

use Prophecy\Argument;
use Psr\Log\NullLogger;
use Search2d\Domain\Pixiv\RequestIllust;
use Search2d\Infrastructure\Domain\Pixiv\QueueRequestIllustReceiver;
use Search2d\Infrastructure\Domain\QueueReceiver;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Infrastructure\Domain\Pixiv\QueueRequestIllustReceiver
 */
class QueueIllustRequestReceiverTest extends TestCase
{
    /**
     * @return void
     */
    public function testReceiveAndSuccess(): void
    {
        $success = null;

        $queueReceiver = $this->prophesize(QueueReceiver::class);
        $queueReceiver->receive(Argument::type('callable'))
            ->will(function (array $args) use (&$success) {
                $success = call_user_func($args[0], '{"illust_id": 1}');
            })
            ->shouldBeCalled();

        $receiver = new QueueRequestIllustReceiver($queueReceiver->reveal(), new NullLogger());
        $receiver->receive(function (RequestIllust $request) {
            $this->assertSame(1, $request->getIllustId());
            return true;
        });

        $this->assertTrue($success);
    }
}