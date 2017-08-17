<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Domain\Pixiv;

use Psr\Log\NullLogger;
use Search2d\Domain\Pixiv\RequestIllust;
use Search2d\Infrastructure\Domain\Pixiv\QueueRequestIllustSender;
use Search2d\Infrastructure\Domain\QueueSender;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Infrastructure\Domain\Pixiv\QueueRequestIllustSender
 */
class QueueIllustRequestSenderTest extends TestCase
{
    /**
     * @return void
     */
    public function testReceiveAndSuccess(): void
    {
        $queueSender = $this->prophesize(QueueSender::class);
        $queueSender->send('{"illust_id":1}')->shouldBeCalled();

        $sender = new QueueRequestIllustSender($queueSender->reveal(), new NullLogger());
        $sender->send(new RequestIllust(1));
    }
}