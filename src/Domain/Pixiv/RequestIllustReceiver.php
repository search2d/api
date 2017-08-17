<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

interface RequestIllustReceiver
{
    public function receive(callable $callback): void;
}