<?php
declare(strict_types=1);

namespace Search2d\Usecase\Pixiv;

use Search2d\Domain\Pixiv\RequestIllust;
use Search2d\Domain\Pixiv\RequestIllustSender;

class SendRequestIllustHandler
{
    /** @var \Search2d\Domain\Pixiv\RequestIllustSender */
    private $requestSender;

    /**
     * @param \Search2d\Domain\Pixiv\RequestIllustSender $requestSender
     */
    public function __construct(RequestIllustSender $requestSender)
    {
        $this->requestSender = $requestSender;
    }

    /**
     * @param \Search2d\Usecase\Pixiv\SendRequestIllustCommand $command
     * @return void
     */
    public function handle(SendRequestIllustCommand $command): void
    {
        $this->requestSender->send(new RequestIllust($command->illustId));
    }
}