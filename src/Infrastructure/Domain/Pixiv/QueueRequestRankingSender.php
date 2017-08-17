<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv;

use Psr\Log\LoggerInterface;
use Search2d\Domain\Pixiv\RequestRanking;
use Search2d\Domain\Pixiv\RequestRankingSender;
use Search2d\Infrastructure\Domain\QueueSender;

class QueueRequestRankingSender implements RequestRankingSender
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
     * @param \Search2d\Domain\Pixiv\RequestRanking $request
     * @return void
     */
    public function send(RequestRanking $request): void
    {
        $json = json_encode([
            'mode' => $request->getMode()->value,
            'date' => $request->getDate()->value->format('Y-m-d')
        ]);

        $this->queueSender->send($json);
    }
}