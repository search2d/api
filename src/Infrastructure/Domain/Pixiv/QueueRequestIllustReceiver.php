<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv;

use JsonMapper;
use Psr\Log\LoggerInterface;
use Search2d\Domain\Pixiv\RequestIllust;
use Search2d\Domain\Pixiv\RequestIllustReceiver;
use Search2d\Infrastructure\Domain\QueueReceiver;

class QueueRequestIllustReceiver implements RequestIllustReceiver
{
    /** @var \Search2d\Infrastructure\Domain\QueueReceiver */
    private $queueReceiver;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /** @var \JsonMapper */
    private $jsonMapper;

    /**
     * @param \Search2d\Infrastructure\Domain\QueueReceiver $queueReceiver
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(QueueReceiver $queueReceiver, LoggerInterface $logger)
    {
        $this->queueReceiver = $queueReceiver;
        $this->logger = $logger;

        $this->jsonMapper = new JsonMapper();
        $this->jsonMapper->bExceptionOnMissingData = true;
    }

    /**
     * @param callable $callback
     * @return void
     * @throws \Throwable
     */
    public function receive(callable $callback): void
    {
        $this->queueReceiver->receive(function (string $message) use ($callback): bool {
            $data = json_decode($message);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \RuntimeException(json_last_error_msg(), json_last_error());
            }

            /** @var \Search2d\Infrastructure\Domain\Pixiv\JsonRequestIllust $request */
            $request = $this->jsonMapper->map($data, new JsonRequestIllust());

            return call_user_func(
                $callback,
                new RequestIllust($request->illust_id)
            );
        });
    }
}