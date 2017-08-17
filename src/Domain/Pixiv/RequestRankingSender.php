<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

interface RequestRankingSender
{
    /**
     * @param \Search2d\Domain\Pixiv\RequestRanking $request
     * @return void
     */
    public function send(RequestRanking $request): void;
}