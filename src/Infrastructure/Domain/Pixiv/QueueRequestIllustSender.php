<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv;

use Psr\Log\LoggerInterface;
use Search2d\Domain\Pixiv\RequestIllust;
use Search2d\Domain\Pixiv\RequestIllustSender;
use Search2d\Infrastructure\Domain\QueueSender;

class QueueRequestIllustSender implements RequestIllustSender
{
    /** @var \Search2d\Infrastructure\Domain\QueueSender */
    private $queueSender;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /**
     * @param \Search2d\Infrastructure\Domain\QueueSender $queueSender
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(QueueSender $queueSender, LoggerInterface $logger)
    {
        $this->queueSender = $queueSender;
        $this->logger = $logger;
    }

    /**
     * @param \Search2d\Domain\Pixiv\RequestIllust $request
     * @return void
     */
    public function send(RequestIllust $request): void
    {
        $json = json_encode(['illust_id' => $request->getIllustId()]);

        $this->queueSender->send($json);
    }
}