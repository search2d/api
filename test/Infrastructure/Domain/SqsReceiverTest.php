<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Domain;

use Aws\CommandInterface;
use Aws\MockHandler;
use Aws\Result;
use Aws\Sqs\SqsClient;
use Psr\Http\Message\RequestInterface;
use Psr\Log\NullLogger;
use Search2d\Infrastructure\Domain\SqsReceiver;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Infrastructure\Domain\SqsReceiver
 */
class SqsReceiverTest extends TestCase
{
    private const QUEUE_URL = 'http://example.com/';
    private const RECEIPT_HANDLE = 'dummy-handle';
    private const MESSAGE_BODY = 'dummy-body';

    /**
     * @return void
     */
    public function testReceiveAndSuccess(): void
    {
        $mock = new MockHandler();

        $mock->append(function (CommandInterface $cmd, RequestInterface $req) {
            $this->assertSame('ReceiveMessage', $cmd->getName());
            $this->assertSame(self::QUEUE_URL, $cmd['QueueUrl']);
            return new Result([
                'Messages' => [
                    ['Body' => self::MESSAGE_BODY, 'ReceiptHandle' => self::RECEIPT_HANDLE]
                ],
            ]);
        });

        $mock->append(function (CommandInterface $cmd, RequestInterface $req) {
            $this->assertSame('DeleteMessage', $cmd->getName());
            $this->assertSame(self::QUEUE_URL, $cmd['QueueUrl']);
            $this->assertSame(self::RECEIPT_HANDLE, $cmd['ReceiptHandle']);
            return new Result([]);
        });

        $client = $this->createClient($mock);

        $receiver = new SqsReceiver($client, self::QUEUE_URL, new NullLogger());

        $receiver->receive(function (string $message) {
            $this->assertSame(self::MESSAGE_BODY, $message);
            return true;
        });
    }

    /**
     * @return void
     */
    public function testReceiveAndFailure(): void
    {
        $mock = new MockHandler();

        $mock->append(function (CommandInterface $cmd, RequestInterface $req) {
            $this->assertSame('ReceiveMessage', $cmd->getName());
            $this->assertSame(self::QUEUE_URL, $cmd['QueueUrl']);
            return new Result([
                'Messages' => [
                    ['Body' => self::MESSAGE_BODY, 'ReceiptHandle' => self::RECEIPT_HANDLE]
                ],
            ]);
        });

        $client = $this->createClient($mock);

        $receiver = new SqsReceiver($client, self::QUEUE_URL, new NullLogger());

        $receiver->receive(function (string $message) {
            $this->assertSame(self::MESSAGE_BODY, $message);
            return false;
        });
    }

    /**
     * @return void
     */
    public function testReceiveAndThrowException(): void
    {
        $mock = new MockHandler();

        $mock->append(function (CommandInterface $cmd, RequestInterface $req) {
            $this->assertSame('ReceiveMessage', $cmd->getName());
            $this->assertSame(self::QUEUE_URL, $cmd['QueueUrl']);
            return new Result([
                'Messages' => [
                    ['Body' => self::MESSAGE_BODY, 'ReceiptHandle' => self::RECEIPT_HANDLE]
                ],
            ]);
        });

        $client = $this->createClient($mock);

        $receiver = new SqsReceiver($client, self::QUEUE_URL, new NullLogger());

        // RuntimeExceptionなどでは本当にコールバックから発生したものか区別できないので無名クラスで独自の例外を作成する。
        $exception = new class extends \Exception
        {
        };

        $this->expectException(get_class($exception));

        $receiver->receive(function (string $message) use ($exception) {
            $this->assertSame(self::MESSAGE_BODY, $message);
            throw $exception;
        });
    }

    /**
     * @return void
     */
    public function testReceiveNoMessage(): void
    {
        $mock = new MockHandler();

        $mock->append(function (CommandInterface $cmd, RequestInterface $req) {
            $this->assertSame('ReceiveMessage', $cmd->getName());
            $this->assertSame(self::QUEUE_URL, $cmd['QueueUrl']);
            return new Result([
                'Messages' => [],
            ]);
        });

        $client = $this->createClient($mock);

        $receiver = new SqsReceiver($client, self::QUEUE_URL, new NullLogger());

        $receiver->receive(function () {
            $this->fail();
        });
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