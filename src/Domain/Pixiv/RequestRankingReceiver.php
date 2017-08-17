<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

interface RequestRankingReceiver
{
    public function receive(callable $callback): void;
}