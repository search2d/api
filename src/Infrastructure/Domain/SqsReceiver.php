<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain;

use Aws\Sqs\SqsClient;
use Psr\Log\LoggerInterface;

class SqsReceiver implements QueueReceiver
{
    /** @var \Aws\Sqs\SqsClient */
    private $sqsClient;

    /** @var string */
    private $sqsUrl;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /**
     * @param \Aws\Sqs\SqsClient $sqsClient
     * @param string $sqsUrl
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(SqsClient $sqsClient, string $sqsUrl, LoggerInterface $logger)
    {
        $this->sqsClient = $sqsClient;
        $this->sqsUrl = $sqsUrl;
        $this->logger = $logger;
    }

    /**
     * @param callable $callback
     * @return void
     * @throws \Throwable
     */
    public function receive(callable $callback): void
    {
        $result = $this->sqsClient->receiveMessage([
            'QueueUrl' => $this->sqsUrl,
            'WaitTimeSeconds' => 20,
            'MaxNumberOfMessages' => 1,
        ]);

        $this->logger->debug('SQS Receive Message', $result->toArray());

        $messages = $result->get('Messages');
        if (count($messages) === 0) {
            return;
        }

        $message = $messages[0];

        try {
            $success = call_user_func($callback, $message['Body']);
        } catch (\Throwable $t) {
            $success = false;
            throw $t;
        } finally {
            if ($success) {
                $result = $this->sqsClient->deleteMessage([
                    'QueueUrl' => $this->sqsUrl,
                    'ReceiptHandle' => $message['ReceiptHandle']
                ]);
                $this->logger->debug('SQS Delete Message', $result->toArray());
            }
        }
    }
}