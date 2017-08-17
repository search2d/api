<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain;

use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;
use Psr\Log\LoggerInterface;

class SqsSender implements QueueSender
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
     * @param string $body
     * @return void
     */
    public function send(string $body): void
    {
        try {
            $result = $this->sqsClient->sendMessage(array(
                'QueueUrl' => $this->sqsUrl,
                'MessageBody' => $body,
            ));
        } catch (AwsException $exception) {
            throw $exception;
        }

        $this->logger->debug('SQS Send Message', $result->toArray());
    }
}