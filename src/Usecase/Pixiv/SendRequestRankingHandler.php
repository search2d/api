<?php
declare(strict_types=1);

namespace Search2d\Usecase\Pixiv;

use Search2d\Domain\Pixiv\RequestRanking;
use Search2d\Domain\Pixiv\RequestRankingSender;

class SendRequestRankingHandler
{
    /** @var \Search2d\Domain\Pixiv\RequestRankingSender */
    private $requestSender;

    /**
     * @param \Search2d\Domain\Pixiv\RequestRankingSender $requestSender
     */
    public function __construct(RequestRankingSender $requestSender)
    {
        $this->requestSender = $requestSender;
    }

    /**
     * @param \Search2d\Usecase\Pixiv\SendRequestRankingCommand $command
     * @return void
     */
    public function __invoke(SendRequestRankingCommand $command): void
    {
        $this->requestSender->send(
            new RequestRanking($command->mode, $command->date)
        );
    }
}