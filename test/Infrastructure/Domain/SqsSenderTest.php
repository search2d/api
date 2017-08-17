<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Domain;

use Aws\CommandInterface;
use Aws\MockHandler;
use Aws\Result;
use Aws\Sqs\SqsClient;
use Psr\Http\Message\RequestInterface;
use Psr\Log\NullLogger;
use Search2d\Infrastructure\Domain\SqsSender;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Infrastructure\Domain\SqsSender
 */
class SqsSenderTest extends TestCase
{
    const QUEUE_URL = 'http://example.com/';
    const RECEIPT_HANDLE = 'dummy-handle';
    const MESSAGE_BODY = 'dummy-body';

    /**
     * @return void
     */
    public function testReceiveAndSuccess(): void
    {
        $mock = new MockHandler();

        $mock->append(function (CommandInterface $cmd, RequestInterface $req) {
            $this->assertSame('SendMessage', $cmd->getName());
            $this->assertSame(self::QUEUE_URL, $cmd['QueueUrl']);
            $this->assertSame(self::MESSAGE_BODY, $cmd['MessageBody']);
            return new Result([]);
        });

        $client = $this->createClient($mock);

        $sender = new SqsSender($client, self::QUEUE_URL, new NullLogger());

        $sender->send(self::MESSAGE_BODY);
    }

    /**
     * @param \Aws\MockHandler $mock
     * @return \Aws\Sqs\SqsClient
     */
    private function createClient(MockHandler $mock): SqsClient
    {
        return new SqsClient([
            'region' => 'ap-northeast-1',
            'version' => '2012-11-05',
            'credentials' => ['key' => '', 'secret' => ''],
            'handler' => $mock,
        ]);
    }
}