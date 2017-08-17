<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

interface RequestIllustSender
{
    /**
     * @param \Search2d\Domain\Pixiv\RequestIllust $request
     * @return void
     */
    public function send(RequestIllust $request): void;
}